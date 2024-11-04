<?php
namespace App\Services;

use App\Models\Department;
use App\Models\StudentsCountForDepart;
use Illuminate\Support\Facades\Config;

class PointCalculationService
{
    protected $studentDivisorTables = [
        'table_2_3_2',
        'table_2_4_1',
        'table_2_4_2_b',
        'table_3_4_1',
        'table_3_4_2',
        'table_4_1'
    ];

    /**
     * Kafedra ballarini hisoblash
     */
    public function calculateDepartmentPoints(Department $department)
    {
        $departmentCodlari = Config::get('dep_emp_tables.department');
        $employeeCodlari = Config::get('dep_emp_tables.employee');
        $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

        // Talabalar sonini olish
        $departmentStudentCount = StudentsCountForDepart::where('departament_id', $department->id)
            ->where('status', 1)
            ->value('number') ?? 0;

        // O'qituvchilar sonini olish
        $departmentCounts = config('departament_tichers_count') ?? include(config_path('departament_tichers_count.php'));
        $totalDepartmentEmployees = $this->getDepartmentEmployeeCount($department->id, $departmentCounts);

        // Maksimal ballarni olish
        $maxPoints = Config::get('max_points_dep_emp');
        $allMaxPoints = array_merge($maxPoints['department'], $maxPoints['employee']);

        $directionalCalculations = $this->calculateDirectionalPoints(
            $department,
            $allMaxPoints,
            $totalDepartmentEmployees,
            $departmentStudentCount
        );

        $totalN = collect($directionalCalculations)->sum('N');
        $totalInfos = $department->point_user_deportaments()->where('status', 1)->count();

        // Qo'shimcha ballarni hisoblash
        $departmentExtraPoints = $this->calculateExtraPoints($department);

        // O'qituvchilar umumiy balli
        $teacherTotalPoints = $totalDepartmentEmployees > 0
            ? $totalN * $totalDepartmentEmployees
            : 0;

        $totalWithExtra = round($teacherTotalPoints + $departmentExtraPoints, 2);

        return [
            'calculations' => $directionalCalculations,
            'teacher_count' => $totalDepartmentEmployees,
            'student_count' => $departmentStudentCount,
            'total_infos' => $totalInfos,
            'total_n' => round($totalN, 2),
            'extra_points' => $departmentExtraPoints,
            'teacher_total_points' => round($teacherTotalPoints, 2),
            'total_with_extra' => $totalWithExtra,
            'html' => $this->generateCalculationHTML(
                $directionalCalculations,
                $totalDepartmentEmployees,
                $departmentStudentCount,
                $totalN,
                $totalInfos
            )
        ];
    }

    /**
     * Fakultet ballarini hisoblash
     */
    public function calculateFacultyPoints($facultyId)
    {
        $departments = Department::where('faculty_id', $facultyId)->get();
        $facultyPoints = 0;
        $departmentResults = [];

        foreach ($departments as $department) {
            $result = $this->calculateDepartmentPoints($department);
            $facultyPoints += $result['total_with_extra'];
            $departmentResults[] = [
                'department_name' => $department->name,
                'points' => $result['total_with_extra']
            ];
        }

        return [
            'faculty_total_points' => round($facultyPoints, 2),
            'departments' => $departmentResults
        ];
    }

    /**
     * Yo'nalishlar bo'yicha ballarni hisoblash
     */
    protected function calculateDirectionalPoints($department, $allMaxPoints, $teacherCount, $studentCount)
    {
        $calculations = [];

        foreach ($allMaxPoints as $direction => $points) {
            $columnName = $direction . 'id';
            $recordsCount = $department->point_user_deportaments()
                ->where('status', 1)
                ->whereNotNull($columnName)
                ->count();

            if ($recordsCount > 0) {
                $maxPoint = $points['max'];
                $directionBase = rtrim($direction, '_');
                $divisor = in_array($directionBase, $this->studentDivisorTables)
                    ? ($studentCount ?: 1)
                    : ($teacherCount ?: 1);

                $subtotal = $recordsCount * $maxPoint;
                $N = min($subtotal / $divisor, $maxPoint);

                $calculations[] = [
                    'direction' => $direction,
                    'records_count' => $recordsCount,
                    'max_point' => $maxPoint,
                    'sub_total' => $subtotal,
                    'divisor' => $divisor,
                    'N' => round($N, 2),
                    'original_N' => round($subtotal / $divisor, 2),
                    'is_limited' => $subtotal / $divisor > $maxPoint,
                    'divisor_type' => in_array($directionBase, $this->studentDivisorTables)
                        ? 'Talabalar soni'
                        : "O'qituvchilar soni"
                ];
            }
        }

        return $calculations;
    }

    /**
     * Qo'shimcha ballarni hisoblash
     */
    protected function calculateExtraPoints($department)
    {
        return $department->point_user_deportaments()
            ->where('status', 1)
            ->whereHas('departPoint')
            ->with('departPoint')
            ->get()
            ->sum(function ($pointEntry) {
                return $pointEntry->departPoint->point;
            });
    }

    /**
     * O'qituvchilar sonini olish
     */
    protected function getDepartmentEmployeeCount($departmentId, $departmentCounts)
    {
        foreach ($departmentCounts as $facultyDepartments) {
            if (isset($facultyDepartments[$departmentId])) {
                return $facultyDepartments[$departmentId];
            }
        }
        return 0;
    }

    /**
     * HTML generatsiya
     */
    public function generateCalculationHTML($calculations, $teacherCount, $studentCount, $totalN, $totalInfos)
    {
        // Sizning mavjud generateCalculationHTML metodi kodingiz
    }
}
