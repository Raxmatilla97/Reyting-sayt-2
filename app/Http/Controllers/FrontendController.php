<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Services\PointCalculationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class FrontendController extends Controller
{
    protected $pointCalculationService;

    public function __construct(PointCalculationService $pointCalculationService)
    {
        $this->pointCalculationService = $pointCalculationService;
    }

    public function index()
    {
        // Fakultetlar statusi 1 bo'lganlarini olamiz
        $faculties = Faculty::where('status', 1)
            ->with(['departments.employee', 'departments.point_user_deportaments.departPoint'])
            ->get();

        // Config faylidan o'qituvchilar sonini olish
        $departmentCounts = config('departament_tichers_count') ?? include(config_path('departament_tichers_count.php'));

        // Fakultetlar, bo'limlar va xodimlar uchun point'larni hisoblash
        $topFaculties = $this->calculateFacultyPoints($faculties);
        $topDepartments = $this->calculateDepartmentPoints($faculties);
        $topEmployees = $this->calculateEmployeePoints($faculties);

        return view('welcome', compact('topFaculties', 'topDepartments', 'topEmployees'));
    }

    private function calculateFacultyPoints(Collection $faculties): SupportCollection
    {
        $facultiesWithPoints = new SupportCollection();

        foreach ($faculties as $faculty) {
            // Fakultet ballarini hisoblash
            $points = $this->pointCalculationService->calculateFacultyPoints($faculty);

            // Fakultet o'qituvchilari sonini hisoblash
            $facultyTotalTeachers = $faculty->departments
                ->where('status', 1)
                ->sum(function ($department) {
                    return $department->employee
                        ->where('status', 1)
                        ->count();
                });

            // Fakultet reytingini hisoblash - umumiy ball / o'qituvchilar soni
            $facultyRating = $facultyTotalTeachers > 0
                ? round($points['total_points'] / $facultyTotalTeachers, 2)
                : 0;

            $facultyData = [
                'id' => $faculty->id,
                'name' => $faculty->name,
                'total_points' => $facultyRating, // o'zgartirildi
                'total_teachers' => $facultyTotalTeachers,
                'image' => $faculty->image ?? null,
                'status' => $faculty->status
            ];

            $facultiesWithPoints->push($facultyData);
        }

        return $facultiesWithPoints->where('status', 1)->sortByDesc('total_points');
    }

    private function calculateDepartmentPoints(Collection $faculties): SupportCollection
    {
        $departmentsWithPoints = new SupportCollection();

        foreach ($faculties as $faculty) {
            foreach ($faculty->departments as $department) {
                $points = $this->pointCalculationService->calculateDepartmentPoints($department);

                if ($department->status == 1) {
                    $departmentData = [
                        'id' => $department->id,
                        'name' => $department->name,
                        'faculty_name' => $faculty->name,
                        'total_points' => $points['total_n'],
                        'image' => $department->image ?? null,
                        'status' => $department->status
                    ];

                    $departmentsWithPoints->push($departmentData);
                }
            }
        }

        return $departmentsWithPoints->sortByDesc('total_points');
    }

    private function calculateEmployeePoints(Collection $faculties): SupportCollection
    {
        $employeesWithPoints = new SupportCollection();

        foreach ($faculties as $faculty) {
            foreach ($faculty->departments as $department) {
                foreach ($department->employee as $employee) {
                    if ($employee->status == 1) {
                        $employeePoints = $department->point_user_deportaments
                            ->where('user_id', $employee->id)
                            ->where('status', 1)
                            ->sum('point');

                        $employeeData = [
                            'id' => $employee->id,
                            'first_name' => $employee->first_name,
                            'second_name' => $employee->second_name,
                            'department_name' => $department->name,
                            'faculty_name' => $faculty->name,
                            'total_points' => $employeePoints,
                            'image' => $employee->image ?? null
                        ];

                        $employeesWithPoints->push($employeeData);
                    }
                }
            }
        }

        return $employeesWithPoints->sortByDesc('total_points')->take('16');
    }
}
