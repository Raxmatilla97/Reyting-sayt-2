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
         $departments = Department::with(['point_user_deportaments' => function($query) {
             $query->where('status', 1)->with('departPoint');
         }])->where('status', 1);

         // Agar ism berilgan bo'lsa, qidirish
         if ($request->filled('name')) {
             $name = '%' . $request->name . '%';
             $departments->where('name', 'like', $name);
         }

         // Tartiblash va sahifalash
         $departments = $departments->orderBy('created_at', 'desc')->paginate(21);

         // Har bir departament uchun umumiy ballni hisoblash
         foreach ($departments as $department) {
             $departmentPointTotal = 0;
             foreach ($department->point_user_deportaments as $pointEntry) {
                 // point_user_deportaments jadvalidagi ballni qo'shish
                 $departmentPointTotal += $pointEntry->point;

                 // departPoint jadvalidagi ballni qo'shish (agar mavjud bo'lsa)
                 if ($pointEntry->departPoint) {
                     $departmentPointTotal += $pointEntry->departPoint->point;
                 }
             }
             $department->totalPoints = round($departmentPointTotal, 2);
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

        // Fakultet umumiy ballari soni
        $totalPoints = round($department->point_user_deportaments()->where('status', 1)->sum('point'), 2);

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


        return view('dashboard.department.show', compact(
            'department',
            'pointUserInformations',
            'totalEmployees',
            'totalPoints',
            'totalInfos',
            'timeAgo',
            'fullName'



        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
    }
}
