<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\PointUserDeportament;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::with('point_user_deportaments')->paginate(15);

        foreach ($departments as $department) {
            $department->totalPoints = 0; // Yangi xususiyat yaratish
            foreach ($department->point_user_deportaments as $test) {
                $department->totalPoints += $test->point; // Har bir `point_user_deportament` uchun `point` qiymatini qo'shish

            }
        }

        return view('livewire.pages.dashboard.department.index', compact('departments'));
    }

    public function departmentFormChose()
    {

        // Kafedra ma'lumotlarini yuklash uchun uni bo'limlari ro'yxati
        $jadvallar_codlari = Config::get('dep_emp_tables.department');;;

        return view('livewire.pages.dashboard.department_category_choose', compact('jadvallar_codlari'));
    }

    public function departmentShow($slug)
    {
        $department = Department::where('slug', $slug)->firstOrFail();

        $departament_items = PointUserDeportament::whereIn('departament_id', $department->pluck('id'))->paginate('15');

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
          foreach ($departament_items as $departament_item) {
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

          dd($departament_items);


        return view('livewire.pages.dashboard.department.show', compact(
            'department',



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
