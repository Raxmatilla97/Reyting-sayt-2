<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\departPoints;

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


    public function employeeShow($employee_id)
    {
        try {
            // employee_id bo'yicha xodimni topishga harakat qilamiz
            $employee = Employee::where('employee_id_number', $employee_id)->firstOrFail();

            // Xodimga tegishli point ma'lumotlarini olamiz
            $pointUserInformations = PointUserDeportament::where('user_id', $employee->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

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
            foreach ($pointUserInformations as $item) {
                foreach ($arrayKey as $column => $originalKey) {
                    if (isset($item->$column)) {
                        // Config faylidan label nomini olish
                        $labelKey = $originalKey . '_';
                        $label = isset($allLabels[$labelKey]) ? $allLabels[$labelKey]['label'] : $jadvallarCodlari[$originalKey];

                        $item->murojaat_nomi = $label;
                        $item->murojaat_codi = $originalKey;
                        break;
                    }
                }
            }

            // Umumiy ballarni hisoblash
            $totalPoints = PointUserDeportament::where('user_id', $employee->id)
                ->where('status', 1)
                ->sum('point');


            // O'qituvchining barcha departamentga o'tib ketgan ballari yi'gindisi
            $pointUserInformation = PointUserDeportament::where('user_id', $employee->id)
            ->where('status', 1)->get();

            $departamentPointTotal = 0;
            foreach ($pointUserInformation as $pointEntry) {
                $departamentPointTotal += DepartPoints::where('point_user_deport_id', $pointEntry->id)->sum('point');
            }

            // $totalPoints = $employee->department ? $totalPoints : 'N/A';

            return view('dashboard.employee.show', compact('employee', 'pointUserInformations', 'totalPoints', 'departamentPointTotal'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Xodim topilmasa, xato xabarini ko'rsatamiz
            return back()->with('error', "ID: $employee_id bo'lgan xodim topilmadi.");
        } catch (\Exception $e) {
            // Boshqa xatoliklar uchun
            \Log::error('Xodim ma\'lumotlarini olishda xatolik: ' . $e->getMessage());
            return back()->with('error', 'Ma\'lumotlarni olishda xatolik yuz berdi. Iltimos, keyinroq qayta urinib ko\'ring.');
        }
    }


    public function mySubmittedInformation()
    {
        $user = auth()->user();
        $pointUserInformations = PointUserDeportament::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate('15');

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

        // Umumiy ballar yig'indisini saqlash uchun o'zgaruvchilar
        $totalPoints = 0;
        $departamentPointTotal = 0;

        // Ma'lumotlar massivini tekshirish
        foreach ($pointUserInformations as $pointUserInformation) {
            foreach ($arrayKey as $column => $originalKey) {
                // column tekshiriladi
                if (isset($pointUserInformation->$column)) {
                    // $murojaat_nomi o'rnatiladi
                    $pointUserInformation->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $pointUserInformation->murojaat_codi = $originalKey;
                    break;
                }
            }

            // Foydalanuvchining har bir itemidagi ballarni yig'indiga qo'shish
            if ($pointUserInformation->status === 1) {
                if (isset($pointUserInformation->point)) {
                    $totalPoints += $pointUserInformation->point;
                }
            }

            // DepartPoints ballarini qo'shish (statusdan qat'i nazar)
            $departamentPointTotal += DepartPoints::where('point_user_deport_id', $pointUserInformation->id)->sum('point');
        }


        return view('dashboard.my_submited_info', compact('pointUserInformations', 'totalPoints', 'departamentPointTotal'));
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
