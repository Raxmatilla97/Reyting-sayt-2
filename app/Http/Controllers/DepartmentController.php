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

        // Konfiguratsiya faylidan maksimal ballarni olish
        $maxPoints = Config::get('max_points_dep_emp');
        $departmentMaxPoints = $maxPoints['department'];
        $employeeMaxPoints = $maxPoints['employee'];

        // Barcha maksimal ballarni birlashtirish
        $allMaxPoints = array_merge($departmentMaxPoints, $employeeMaxPoints);

        $totalWeightedPoints = 0; // (ball * max_ball) yig'indisi
        $directionalCalculations = []; // Har bir yo'nalish bo'yicha hisob-kitoblarni saqlash uchun

        // Har bir yo'nalish bo'yicha ballarni hisoblash
        foreach ($allMaxPoints as $direction => $points) {
            $columnName = $direction . 'id'; // Bazadagi ustun nomi

            // Yo'nalish bo'yicha umumiy ballarni hisoblash
            $directionPoints = $department->point_user_deportaments()
                ->where('status', 1)
                ->whereNotNull($columnName)
                ->sum('point');

            if ($directionPoints > 0) {
                // Yo'nalish ballini max_ball ga ko'paytirish
                $maxPoint = $points['max'];
                $weightedPoints = $directionPoints * $maxPoint;
                $totalWeightedPoints += $weightedPoints;

                // Hisob-kitob ma'lumotlarini saqlash
                $directionalCalculations[] = [
                    'direction' => $direction,
                    'points' => $directionPoints,
                    'max_point' => $maxPoint,
                    'weighted_points' => $weightedPoints
                ];
            }
        }

        // Barcha yo'nalishlar yig'indisini o'qituvchilar soniga bo'lish
        $totalN = $totalDepartmentEmployees > 0 ? $totalWeightedPoints / $totalDepartmentEmployees : 0;

        // Kafedraga o'tgan qo'shimcha ballarni hisoblash
        $departmentExtraPoints = $department->point_user_deportaments()
            ->where('status', 1)
            ->whereHas('departPoint')
            ->with('departPoint')
            ->get()
            ->sum(function ($pointEntry) {
                return $pointEntry->departPoint->point;
            });

        $departmentPointTotal = $totalN + $departmentExtraPoints;

          // Yakuniy natijani hisoblash
          if ($totalDepartmentEmployees > 0) {
            $totalWithExtra = $totalWeightedPoints + $departmentExtraPoints;
            $totalN = $totalWithExtra / $totalDepartmentEmployees;
            $totalPoints = round($totalN, 2);

            // HTML formatidagi hisobot
            $pointsCalculationExplanation = '
            <div class="w-full p-4 bg-gray-50 rounded-lg">
                <h2 class="text-xl font-bold mb-4 text-gray-800">KAFEDRA BALI HISOBLASH TARTIBI</h2>

                <!-- Ranglar haqida ma\'lumot -->
                <div class="mb-4 text-sm flex gap-4">
                    <span class="flex items-center">
                        <div class="w-4 h-4 bg-green-200 mr-2"></div>
                        Yig\'ilgan ball
                    </span>
                    <span class="flex items-center">
                        <div class="w-4 h-4 bg-blue-200 mr-2"></div>
                        Maksimal ball
                    </span>
                    <span class="flex items-center">
                        <div class="w-4 h-4 bg-purple-200 mr-2"></div>
                        Ko\'paytirilgan ball
                    </span>
                </div>

                <!-- Yo\'nalishlar bo\'yicha ballar -->
                <div class="flex flex-wrap gap-2 mb-6">';

            // Har bir yo'nalish uchun kartochka
            foreach ($directionalCalculations as $calc) {
                $points = number_format($calc['points'], 2, '.', '');
                $weighted = number_format($calc['weighted_points'], 2, '.', '');

                $pointsCalculationExplanation .= "
                    <div class='bg-white p-2 rounded shadow-sm flex items-center gap-2 text-sm'>
                        <span class='text-gray-700'>{$calc['direction']}:</span>
                        <span class='bg-green-200 px-2 py-1 rounded'>{$points}</span>
                        <span>×</span>
                        <span class='bg-blue-200 px-2 py-1 rounded'>{$calc['max_point']}</span>
                        <span>=</span>
                        <span class='bg-purple-200 px-2 py-1 rounded'>{$weighted}</span>
                    </div>";
            }

            // Yakuniy natija
            $totalWeightedFormatted = number_format($totalWeightedPoints, 2, '.', '');
            $departmentExtraFormatted = number_format($departmentExtraPoints, 2, '.', '');
            $totalWithExtraFormatted = number_format($totalWithExtra, 2, '.', '');
            $totalNFormatted = number_format($totalN, 2, '.', '');

            $pointsCalculationExplanation .= "
                </div>

                <!-- Yakuniy natija -->
                <div class='bg-white p-4 rounded shadow-sm'>
                    <div class='flex flex-wrap gap-4 items-center text-gray-700'>
                        <span>
                            Yo'nalishlar yig'indisi:
                            <span class='bg-purple-200 px-2 py-1 rounded ml-2'>{$totalWeightedFormatted}</span>
                        </span>
                        <span>
                            Qo'shimcha ball:
                            <span class='bg-green-200 px-2 py-1 rounded ml-2'>+{$departmentExtraFormatted}</span>
                        </span>
                        <span>
                            O'qituvchilar soni:
                            <span class='bg-blue-200 px-2 py-1 rounded ml-2'>{$totalDepartmentEmployees}</span>
                        </span>
                    </div>

                    <div class='mt-4 text-lg font-semibold'>
                        JAMI: {$totalWithExtraFormatted} / {$totalDepartmentEmployees} =
                        <span class='bg-yellow-200 px-2 py-1 rounded ml-2'>{$totalNFormatted} ball</span>
                    </div>

                    <div class='mt-4 text-xl font-bold text-center bg-gray-800 text-white py-2 rounded'>
                        KAFEDRA REYTINGI: {$totalNFormatted} BALL
                    </div>
                </div>
            </div>";
        } else {
            $pointsCalculationExplanation = "
            <div class='p-4 bg-red-100 text-red-700 rounded'>
                Kafedra bali hisoblab bo'lmadi: O'qituvchilar soni 0 ga teng
            </div>";
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
