<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Support\Facades\DB;
use App\Models\StudentsCountForDepart;
use Illuminate\Support\Facades\Config;
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

        // Fakultetlar, bo'limlar va xodimlar uchun point'larni hisoblash
        $topFaculties = $this->calculateFacultyPoints($faculties);
        $topDepartments = $this->calculateDepartmentPoints($faculties);
        $topEmployees = $this->calculateEmployeePoints($faculties);

        return view('welcome', compact('topFaculties', 'topDepartments', 'topEmployees'));
    }

    private function calculateFacultyPoints(Collection $faculties): SupportCollection
    {
        $facultiesWithPoints = new SupportCollection();

        // Talabalar soniga bo'linadigan jadvallar
        $studentDivisorTables = [
            'table_2_3_2',
            'table_2_4_1',
            'table_2_4_2_b',
            'table_3_4_1',
            'table_3_4_2',
            'table_4_1'
        ];

        foreach ($faculties as $faculty) {
            // Department va Employee konfiguratsiyalarini olish
            $departmentCodlari = Config::get('dep_emp_tables.department');
            $employeeCodlari = Config::get('dep_emp_tables.employee');
            $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

            // Fakultet o'qituvchilari sonini hisoblash
            $totalTeachers = $faculty->departments
                ->where('status', 1)
                ->sum(function ($department) {
                    return DB::table('users')
                        ->where('department_id', $department->id)
                        ->where('status', 1)
                        ->count();
                });

            // Fakultet talabalar sonini hisoblash
            $totalStudents = StudentsCountForDepart::whereIn('departament_id',
                $faculty->departments->where('status', 1)->pluck('id'))
                ->where('status', 1)
                ->sum('number');

            // Talabalar soni 0 bo'lmasligi uchun
            $totalStudents = $totalStudents ?: 1;

            // Fakultet umumiy ballini hisoblash
            $totalN = 0;
            foreach ($jadvallarCodlari as $code => $name) {
                $recordsCount = 0;
                $maxPoint = Config::get('max_points_dep_emp.department.' . $code . '.max') ??
                    Config::get('max_points_dep_emp.employee.' . $code . '.max') ?? 0;

                foreach ($faculty->departments->where('status', 1) as $department) {
                    $columnName = $code . 'id';
                    $count = $department->point_user_deportaments()
                        ->whereHas('employee', function ($q) {
                            $q->where('status', 1);
                        })
                        ->where($columnName, '!=', null)
                        ->where('status', 1)
                        ->count();

                    $recordsCount += $count;
                }

                if ($recordsCount > 0) {
                    $subtotal = $recordsCount * $maxPoint;
                    // Talabalar soniga bo'linadigan jadvallarni tekshirish
                    $isDivisorStudent = in_array(rtrim($code, '_'), $studentDivisorTables);
                    $divisor = $isDivisorStudent ? $totalStudents : $totalTeachers;
                    $divisor = $divisor ?: 1; // 0 ga bo'linishni oldini olish

                    $N = min($subtotal / $divisor, $maxPoint);
                    $totalN += $N;
                }
            }

            $facultyData = [
                'id' => $faculty->id,
                'name' => $faculty->name,
                'total_points' => round($totalN, 2),
                'total_teachers' => $totalTeachers,
                'custom_points' => $faculty->custom_points,
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
                        'custom_points' => $department->custom_points,
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
