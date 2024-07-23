<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\PointUserDeportament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculties = Faculty::with('departments.point_user_deportaments')->paginate(15);

        foreach ($faculties as $faculty) {
            $faculty->totalPoints = 0;
            foreach ($faculty->departments as $department) {
                foreach ($department->point_user_deportaments as $test) {
                    $faculty->totalPoints += $test->point;
                }
            }
        }
        return view('livewire.pages.dashboard.faculty.index', compact('faculties'));
    }

    public function facultyShow($slug)
    {
        $faculty = Faculty::where('slug', $slug)->firstOrFail();

        $faculty_items = PointUserDeportament::whereIn('departament_id', $faculty->departments->pluck('id'))->paginate('15');

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
          foreach ($faculty_items as $faculty_item) {
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

        return view('livewire.pages.dashboard.faculty.show', compact('faculty', 'faculty_items'));
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
    public function show(Faculty $faculty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faculty $faculty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        //
    }
}
