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
        // Query builderni yaratish va point_user_deportaments bilan bog'lanish
        $departments = Department::with('point_user_deportaments')->where('status', 1);

        // Agar 'name' parametri mavjud bo'lsa, nomga mos keladigan xodimlarni qidirish
        if ($request->filled('name')) {
            $name = '%' . $request->name . '%'; // LIKE qidiruvlari uchun qidiruv terminini tayyorlash
            $departments->where(function ($q) use ($name) {
                $q->where('name', 'like', $name);
            });
        }

        // Tartiblash va paginatsiya qilish
        $departments = $departments->orderBy('created_at', 'desc')->where('status', 1)->paginate(21);

        // Natijani ko'rsatish uchun ko'rinishni qaytarish
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
