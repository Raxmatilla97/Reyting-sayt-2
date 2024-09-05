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
        $faculties = Faculty::where('status', 1)->with(['departments.employee', 'departments.point_user_deportaments'])->get();

        // Fakultetlar, bo'limlar va xodimlar uchun point'larni hisoblash
        $topFaculties = $this->calculateFacultyPoints($faculties);
        $topDepartments = $this->calculateDepartmentPoints($faculties);
        $topEmployees = $this->calculateEmployeePoints($faculties);

        return view('welcome', compact('topFaculties', 'topDepartments', 'topEmployees'));
    }

    private function calculateFacultyPoints(Collection $faculties): SupportCollection
    {
        return $faculties->map(function ($faculty) {
            $faculty->total_points = $faculty->departments->sum(function ($department) {
                return $department->point_user_deportaments->sum('point');
            });
            return $faculty;
        })->sortByDesc('total_points')->take(3);
    }

    private function calculateDepartmentPoints(Collection $faculties): SupportCollection
    {
        $departmentsWithPoints = new SupportCollection();

        foreach ($faculties as $faculty) {
            foreach ($faculty->departments as $department) {
                $department->total_points = $department->point_user_deportaments->sum('point');
                $departmentsWithPoints->push($department);
            }
        }

        return $departmentsWithPoints->sortByDesc('total_points')->where('status', 1)->take(5);
    }

    private function calculateEmployeePoints(Collection $faculties): SupportCollection
    {
        $employeesWithPoints = new SupportCollection();

        foreach ($faculties as $faculty) {
            foreach ($faculty->departments as $department) {
                foreach ($department->employee as $employee) {
                    $employee->total_points = $department->point_user_deportaments
                        ->where('user_id', $employee->id)
                        ->sum('point');
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
