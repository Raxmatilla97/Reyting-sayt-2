<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\DepartPoints;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Employee modelidan queryni boshlash
        $query = Employee::query();

        // Agar 'name' parametri mavjud bo'lsa
        if ($request->filled('name')) {
            $searchTerms = explode(' ', trim($request->name));

            $query->where(function ($mainQuery) use ($searchTerms) {
                // Har bir so'z uchun
                foreach ($searchTerms as $term) {
                    $term = '%' . $term . '%';

                    $mainQuery->where(function ($q) use ($term) {
                        $q->where('first_name', 'like', $term)
                            ->orWhere('second_name', 'like', $term)
                            ->orWhere('third_name', 'like', $term);
                    });
                }
            });

            // To'liq ism bilan qidirish
            $fullName = '%' . $request->name . '%';
            $query->orWhere(DB::raw("CONCAT(first_name, ' ', second_name, ' ', third_name)"), 'like', $fullName)
                ->orWhere(DB::raw("CONCAT(second_name, ' ', first_name, ' ', third_name)"), 'like', $fullName);
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
            $totalPointsSuccess = PointUserDeportament::where('user_id', $employee->id)
                ->where('status', 1);

            $totalPoints = $totalPointsSuccess->sum('point');
            $totalInfos = $totalPointsSuccess->count() ? $totalPointsSuccess->count() : 0;


            // O'qituvchining barcha departamentga o'tib ketgan ballari yi'gindisi
            $pointUserInformation = PointUserDeportament::where('user_id', $employee->id)
                ->where('status', 1)->get();

            $departamentPointTotal = 0;
            foreach ($pointUserInformation as $pointEntry) {
                $departamentPointTotal += DepartPoints::where('point_user_deport_id', $pointEntry->id)->sum('point');
            }

            // $totalPoints = $employee->department ? $totalPoints : 'N/A';

            // Statistika uchun barcha ma'lumotlarni olish (paginatsiyasiz)
            $allUserInformations = PointUserDeportament::where('user_id', $employee->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Kunlik statistika uchun ma'lumotlarni guruhlash
            $dailyStats = $allUserInformations
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                })
                ->map(function ($group) {
                    return [
                        'total' => $group->count(),
                        'accepted' => $group->where('status', 1)->count(),
                        'rejected' => $group->where('status', 0)->count()
                    ];
                });


            return view('dashboard.employee.show', compact('employee', 'pointUserInformations', 'totalPoints', 'departamentPointTotal', 'totalInfos', 'dailyStats'));
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

        // Umumiy ballar yig'indisini hisoblash (pagination dan tashqari)
        $totalPointsSuccess = PointUserDeportament::where('user_id', $user->id)
            ->where('status', 1);

        $totalPoints = $totalPointsSuccess->sum('point') ? $totalPointsSuccess->sum('point') : 0;
        $totalInfos = $totalPointsSuccess->count() ? $totalPointsSuccess->count() : 0;

        // DepartPoints ballarini hisoblash (pagination dan tashqari)
        $departamentPointTotal = DepartPoints::whereIn('point_user_deport_id', function ($query) use ($user) {
            $query->select('id')
                ->from('point_user_deportaments')
                ->where('user_id', $user->id);
        })->sum('point');

        // Statistika uchun barcha ma'lumotlar (paginatsiyasiz)
        $allUserInformations = PointUserDeportament::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kunlik statistika uchun ma'lumotlarni guruhlash
        $dailyStats = $allUserInformations
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function ($group) {
                return [
                    'total' => $group->count(),
                    'accepted' => $group->where('status', 1)->count(),
                    'rejected' => $group->where('status', 0)->count()
                ];
            });


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
        }

        return view('dashboard.my_submited_info', compact('pointUserInformations', 'totalPoints', 'departamentPointTotal', 'totalInfos', 'dailyStats'));
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
