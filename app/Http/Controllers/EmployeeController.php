<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Employee modelidan queryni boshlash
        $query = Employee::query();

        // Agar 'name' parametri mavjud bo'lsa, nomga mos keladigan xodimlarni qidirish
        if ($request->filled('name')) {
            $name = '%' . $request->name . '%'; // LIKE qidiruvlari uchun qidiruv terminini tayyorlash
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', $name)
                    ->orWhere('second_name', 'like', $name)
                    ->orWhere('third_name', 'like', $name);
            });
        }

        // Tartiblash va paginatsiya qilish
        $employee = $query->orderBy('created_at', 'desc')->paginate(30);





        return view('dashboard.employee.index', compact('employee'));
    }

    public function employeeFormChose()
    {


        $jadvallar_codlari = Config::get('dep_emp_tables.employee');;

        return view('dashboard.employee_category_choose', compact('jadvallar_codlari'));
    }


    public function employeeShow($id_employee)
    {
        $employee = Employee::where('employee_id_number', $id_employee)->firstOrFail();

        $pointUserInformations = PointUserDeportament::where('user_id', $employee->id)->paginate('15');

        // Department va Employee konfiguratsiyalarini olish
        $departmentCodlari = Config::get('dep_emp_tables.department');
        $employeeCodlari = Config::get('dep_emp_tables.employee');

        // Ikkala massivni birlashtirish
        $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

        // Config fayllaridan label nomlarini olish
        $employeeLabels = Config::get('employee_form_fields');
        $departmentLabels = Config::get('department_forms_fields');
        $allLabels = array_merge($employeeLabels, $departmentLabels);

        // Har bir massiv elementiga "key" nomli yangi maydonni qo'shish
        $arrayKey = [];
        foreach ($jadvallarCodlari as $key => $value) {
            $arrayKey[$key . 'id'] = $key;
        }

        // Ma'lumotlar massivini tekshirish
        foreach ($pointUserInformations as $faculty_item) {
            foreach ($arrayKey as $column => $originalKey) {
                // column tekshiriladi
                if (isset($faculty_item->$column)) {
                    // Config faylidan label nomini olish
                    $labelKey = $originalKey . '_';
                    $label = isset($allLabels[$labelKey]) ? $allLabels[$labelKey]['label'] : $jadvallarCodlari[$originalKey];

                    $faculty_item->murojaat_nomi = $label;
                    $faculty_item->murojaat_codi = $originalKey;
                    break;
                }
            }
        }

        return view('dashboard.employee.show', compact('employee', 'pointUserInformations'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function mySubmittedInformation()
    {
        $user = auth()->user();

        // Pagination uchun so'rov
        $pointUserInformations = PointUserDeportament::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Department va Employee konfiguratsiyalarini olish
        $departmentCodlari = Config::get('dep_emp_tables.department');
        $employeeCodlari = Config::get('dep_emp_tables.employee');

        // Ikkala massivni birlashtirish
        $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

        // Har bir massiv elementiga "key" nomli yangi maydonni qo'shish
        $arrayKey = [];
        foreach ($jadvallarCodlari as $key => $value) {
            $arrayKey[$key . 'id'] = $key;
        }

        // Ma'lumotlarni tekshirish va murojaat nomini o'rnatish
        foreach ($pointUserInformations as $pointUserInformation) {
            foreach ($arrayKey as $column => $originalKey) {
                if (isset($pointUserInformation->$column)) {
                    $pointUserInformation->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $pointUserInformation->murojaat_codi = $originalKey;
                    break;
                }
            }
        }

        // Umumiy ballarni hisoblash (pagination qilinmagan)
        $totalPoints = PointUserDeportament::where('user_id', $user->id)
            ->where('status', 1)
            ->sum('point');

        return view('dashboard.my_submited_info', compact('pointUserInformations', 'totalPoints'));
    }

    public function list(Request $request)
    {
        // Employee modelidan queryni boshlash
        $query = Employee::query();

        // Agar 'name' parametri mavjud bo'lsa, nomga mos keladigan xodimlarni qidirish
        if ($request->filled('name')) {
            $name = '%' . $request->name . '%'; // LIKE qidiruvlari uchun qidiruv terminini tayyorlash
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', $name)
                    ->orWhere('second_name', 'like', $name)
                    ->orWhere('third_name', 'like', $name);
            });
        }

        // Tartiblash va paginatsiya qilish
        $employees = $query->orderBy('created_at', 'desc')->paginate(30);

        // Natijani ko'rsatish uchun ko'rinishni qaytarish
        return view('dashboard.employee.index', compact('employees'));
    }
}
