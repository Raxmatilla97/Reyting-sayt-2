<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\PointUserDeportament;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        // Departamentlar uchun so'rov yaratish va point_user_deportaments ma'lumotlarini oldindan yuklash
        $departments = Department::with(['point_user_deportaments' => function ($query) {
            $query->where('status', 1)->with('departPoint');
        }])->where('status', 1);

        // Agar ism berilgan bo'lsa, qidirish
        if ($request->filled('name')) {
            $name = '%' . $request->name . '%';
            $departments->where('name', 'like', $name);
        }

        // Tartiblash va sahifalash
        $departments = $departments->orderBy('created_at', 'desc')->paginate(21);

        // Kafedrada nechta o'qituvchi borligi haqidagi massiv
        $departmentCounts = config('departament_tichers_count');

        if ($departmentCounts === null) {
            $departmentCounts = include(config_path('departament_tichers_count.php'));
        }

        // Har bir departament uchun hisoblarni amalga oshirish
        foreach ($departments as $department) {
            $departmentPointTotal = 0;
            // Departamentga tegishli barcha balllarni hisoblab chiqish
            foreach ($department->point_user_deportaments as $pointEntry) {
                // point_user_deportaments jadvalidagi ballni qo'shish
                $departmentPointTotal += $pointEntry->point;

                // Agar qo'shimcha departPoint mavjud bo'lsa, uni ham qo'shish
                if ($pointEntry->departPoint) {
                    $departmentPointTotal += $pointEntry->departPoint->point;
                }
            }

            // Departamentdagi o'qituvchilar sonini olish
            $teacherCount = null;
            foreach ($departmentCounts as $facultyId => $facultyDepartments) {
                if (isset($facultyDepartments[$department->id])) {
                    $teacherCount = $facultyDepartments[$department->id];
                    break;
                }
            }

            // Agar o'qituvchilar soni mavjud bo'lsa, o'rtacha ballni hisoblash
            if ($teacherCount) {
                // Umumiy ballni o'qituvchilar soniga bo'lib, 2 xonagacha yaxlitlash
                $department->totalPoints = round($departmentPointTotal / $teacherCount, 2);
            } else {
                // Agar o'qituvchilar soni ma'lum bo'lmasa, 'N/A' qiymatini berish
                $department->totalPoints = 'N/A';
            }

            // O'qituvchilar sonini saqlash
            $department->teacherCount = $teacherCount ?? 'N/A';
        }

        // Ko'rinishni departamentlar ma'lumotlari bilan qaytarish
        return view('dashboard.department.index', compact('departments'));
    }


    public function departmentFormChose()
    {

        // Kafedra ma'lumotlarini yuklash uchun uni bo'limlari ro'yxati
        $jadvallar_codlari = Config::get('dep_emp_tables.department');;;

        return view('dashboard.department_category_choose', compact('jadvallar_codlari'));
    }

    public function departmentShow(string $slug)
    {
        $department = Department::where('slug', $slug)->firstOrFail();

        $pointUserInformations = PointUserDeportament::where('departament_id', $department->id)->orderBy('created_at', 'desc')->paginate(15);

        // Department va Employee konfiguratsiyalarini olish
        $departmentCodlari = Config::get('dep_emp_tables.department');
        $employeeCodlari = Config::get('dep_emp_tables.employee');

        // Ikkala massivni birlashtirish
        $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

        // Har bir massiv elementiga "key" nomli yangi maydonni qo'shish
        $arrayKey = [];
        foreach ($jadvallarCodlari as $key => $value) {
            $arrayKey[$key . 'id'] = $key; // $key . 'id' qiymatini o'rnating
        }

        // Ma'lumotlar massivini tekshirish
        foreach ($pointUserInformations as $departament_item) {
            foreach ($arrayKey as $column => $originalKey) {
                // column tekshiriladi
                if (isset($departament_item->$column)) {
                    // $murojaat_nomi o'rnatiladi
                    $departament_item->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $departament_item->murojaat_codi = $originalKey;
                    break;
                }
            }
        }

        // Fakultetda nechta o'qituvchi borligi
        $totalEmployees = $department->employee()->count();

        // Kafedrada nechta o'qituvchi borligi haqidagi massiv
        $departmentCounts = config('departament_tichers_count');

        if ($departmentCounts === null) {
            $departmentCounts = include(config_path('departament_tichers_count.php'));
        }

        // Kafedradagi o'qituvchilar sonini olish
        $totalDepartmentEmployees = null;
        foreach ($departmentCounts as $facultyId => $facultyDepartments) {
            if (isset($facultyDepartments[$department->id])) {
                $totalDepartmentEmployees = $facultyDepartments[$department->id];
                break;
            }
        }

        // Agar kafedraning o'qituvchilar soni topilmasa, 0 ga tenglashtiramiz
        $totalDepartmentEmployees = $totalDepartmentEmployees ?? 0;

        // Ro'yxatdan o'tmagan, ammo kafedrada ishlayotgan o'qituvchilar soni
        $unregisteredEmployees = $totalDepartmentEmployees;

        // Fakultet umumiy ballari va o'rtacha ballni hisoblash
        $departmentPointTotal = 0;

        // point_user_deportaments jadvalidagi barcha tasdiqlangan (status = 1) balllarni qo'shish
        $departmentPointTotal += $department->point_user_deportaments()
            ->where('status', 1)
            ->sum('point');

        // departPoint jadvalidagi qo'shimcha balllarni qo'shish (agar mavjud bo'lsa)
        $departmentPointTotal += $department->point_user_deportaments()
            ->where('status', 1)
            ->whereHas('departPoint')
            ->with('departPoint')
            ->get()
            ->sum(function ($pointEntry) {
                return $pointEntry->departPoint->point;
            });

        // Agar o'qituvchilar soni mavjud bo'lsa, o'rtacha ballni hisoblash
        if ($totalDepartmentEmployees > 0) {
            $totalPoints = round($departmentPointTotal / $totalDepartmentEmployees, 2);
        } else {
            $totalPoints = 'N/A';
        }

        // Fakultet umumiy ma'lumotlar soni
        $totalInfos = $department->point_user_deportaments()->count();

        // Fakultet ma'lumoti eng oxirgi kelgan vaqti
        $mostRecentTimestamp = $department->point_user_deportaments()->max('created_at');

        $mostRecentTime = Carbon::parse($mostRecentTimestamp);
        $now = Carbon::now();
        $diffInSeconds = $now->diffInSeconds($mostRecentTime);
        $diffInMinutes = $now->diffInMinutes($mostRecentTime);
        $diffInHours = $now->diffInHours($mostRecentTime);
        $diffInDays = $now->diffInDays($mostRecentTime);

        // Tarjima holati
        if ($diffInSeconds < 60) {
            $timeAgo = $diffInSeconds . ' soniya oldin';
        } elseif ($diffInMinutes < 60) {
            $timeAgo = $diffInMinutes . ' daqiqa oldin';
        } elseif ($diffInHours < 24) {
            $timeAgo = $diffInHours . ' soat oldin';
        } else {
            $timeAgo = $diffInDays . ' kun oldin';
        }

        // Eng oxirgi yuborgan ma'lumotning egasi nomi
        $mostRecentEntry = $department->point_user_deportaments()->orderBy('created_at', 'desc')->first();

        if ($mostRecentEntry) {
            $fullName = $mostRecentEntry->employee->full_name;
        } else {
            $fullName = "Ma'lumot topilmadi!";
        }


        $departmentPointTotal = 0;
        $teacherPoints = 0;
        $departmentExtraPoints = 0;

        // O'qituvchilar yig'gan balllarni hisoblash
        $teacherPoints = $department->point_user_deportaments()
            ->where('status', 1)
            ->sum('point');

        // Kafedraga o'tgan qo'shimcha balllarni hisoblash
        $departmentExtraPoints = $department->point_user_deportaments()
            ->where('status', 1)
            ->whereHas('departPoint')
            ->with('departPoint')
            ->get()
            ->sum(function ($pointEntry) {
                return $pointEntry->departPoint->point;
            });

        $departmentPointTotal = $teacherPoints + $departmentExtraPoints;

        // Agar o'qituvchilar soni mavjud bo'lsa, o'rtacha ballni hisoblash
        if ($totalDepartmentEmployees > 0) {
            $totalPoints = round($departmentPointTotal / $totalDepartmentEmployees, 2);
            $pointsCalculationExplanation = "Kafedra bali hisoblash tartibi: {$teacherPoints} ball O'qituvchilar yig'gan ball + {$departmentExtraPoints} ball kafedraga o'tgan ball = {$departmentPointTotal} ball jami / {$totalDepartmentEmployees} kafedra o'qituvchilar soni = {$totalPoints} ball";
        } else {
            $totalPoints = 'N/A';
            $pointsCalculationExplanation = "Kafedra bali hisoblab bo'lmadi: O'qituvchilar soni 0 ga teng";
        }



        return view('dashboard.department.show', compact(
            'department',
            'pointUserInformations',
            'totalEmployees',
            'totalPoints',
            'totalInfos',
            'timeAgo',
            'fullName',
            'unregisteredEmployees',
            'pointsCalculationExplanation',
            'departmentExtraPoints'



        ));
    }
}
