<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Faculty;
use App\Models\PointUserDeportament;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
     {
         // Fakultetlar statusi 1 bo'lganlarini olamiz
         $faculties = Faculty::where('status', 1)
             ->with(['departments.employee', 'departments.point_user_deportaments.departPoint'])
             ->get();

         // Config faylidan o'qituvchilar sonini olish
         $departmentCounts = config('departament_tichers_count');

         if ($departmentCounts === null) {
             $departmentCounts = include(config_path('departament_tichers_count.php'));
         }

         // Fakultetlar, bo'limlar va xodimlar uchun point'larni hisoblash
         $topFaculties = $this->calculateFacultyPoints($faculties, $departmentCounts);
         $topDepartments = $this->calculateDepartmentPoints($faculties, $departmentCounts);
         $topEmployees = $this->calculateEmployeePoints($faculties);

         return view('welcome', compact('topFaculties', 'topDepartments', 'topEmployees'));
     }

     private function calculateFacultyPoints(Collection $faculties, array $departmentCounts): SupportCollection
     {
         return $faculties->map(function ($faculty) use ($departmentCounts) {
             $faculty->total_points = 0;
             $faculty->total_teachers = 0;

             foreach ($faculty->departments as $department) {
                 $teacherCount = $departmentCounts[$faculty->id][$department->id] ?? 0;
                 $faculty->total_teachers += $teacherCount;

                 $departmentPoints = $department->point_user_deportaments
                     ->where('status', 1)
                     ->reduce(function ($carry, $pointEntry) {
                         return $carry + $pointEntry->point + ($pointEntry->departPoint ? $pointEntry->departPoint->point : 0);
                     }, 0);

                 $faculty->total_points += $departmentPoints;
             }

             $faculty->average_points = $faculty->total_teachers > 0
                 ? round($faculty->total_points / $faculty->total_teachers, 2)
                 : 0;

             return $faculty;
         })->sortByDesc('average_points')->take(3);
     }

     private function calculateDepartmentPoints(Collection $faculties, array $departmentCounts): SupportCollection
     {
         $departmentsWithPoints = new SupportCollection();

         foreach ($faculties as $faculty) {
             foreach ($faculty->departments as $department) {
                 $teacherCount = $departmentCounts[$faculty->id][$department->id] ?? 0;

                 $departmentPoints = $department->point_user_deportaments
                     ->where('status', 1)
                     ->sum('point');

                 $departmentPoints += $department->point_user_deportaments
                     ->where('status', 1)
                     ->sum(function ($pointEntry) {
                         return $pointEntry->departPoint ? $pointEntry->departPoint->point : 0;
                     });

                 $department->average_points = $teacherCount > 0
                     ? round($departmentPoints / $teacherCount, 2)
                     : 0;

                 $departmentsWithPoints->push($department);
             }
         }

         return $departmentsWithPoints->sortByDesc('average_points')->where('status', 1)->take(5);
     }

     private function calculateEmployeePoints(Collection $faculties): SupportCollection
     {
         $employeesWithPoints = new SupportCollection();

         foreach ($faculties as $faculty) {
             foreach ($faculty->departments as $department) {
                 foreach ($department->employee as $employee) {
                     $employeePoints = $department->point_user_deportaments
                         ->where('user_id', $employee->id)
                         ->where('status', 1)
                         ->sum('point');

                     $employee->total_points = $employeePoints;
                     $employeesWithPoints->push($employee);
                 }
             }
         }

         return $employeesWithPoints->sortByDesc('total_points')->where('status', 1)->take(12);
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
}
