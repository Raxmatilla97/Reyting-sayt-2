<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Employee::paginate(15);



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

         // Har bir massiv elementiga "key" nomli yangi maydonni qo'shish
         $arrayKey = [];
         foreach ($jadvallarCodlari as $key => $value) {
             $arrayKey[$key . 'id'] = $key; // $key . 'id' qiymatini o'rnating
         }

         // Ma'lumotlar massivini tekshirish
         foreach ($pointUserInformations as $faculty_item) {
             foreach ($arrayKey as $column => $originalKey) {
                 // column tekshiriladi
                 if (isset($faculty_item->$column)) {
                     // $murojaat_nomi o'rnatiladi
                     $faculty_item->murojaat_nomi = $jadvallarCodlari[$originalKey];
                     $faculty_item->murojaat_codi = $originalKey;
                     break;
                 }
             }
         }


    //    dd($pointUserInformations);

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
        $pointUserInformations = PointUserDeportament::where('user_id', $user->id)->paginate('15');

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

        // Umumiy ballar yig'indisini saqlash uchun o'zgaruvchi
        $totalPoints = 0;

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
            if (isset($pointUserInformation->point)) {
                $totalPoints += $pointUserInformation->point;
            }
        }

        // $totalPoints;

        return view('dashboard.my_submited_info', compact('pointUserInformations', 'totalPoints'));
    }


}
