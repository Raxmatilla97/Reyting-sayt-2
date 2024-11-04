<?php
namespace App\Services;

use App\Models\Faculty;
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
        $totalEmployees = $department->employee()->count();

        return [
            'calculations' => $directionalCalculations,
            'teacher_count' => $totalDepartmentEmployees,
            'student_count' => $departmentStudentCount,
            'total_infos' => $totalInfos,
            'total_n' => round($totalN, 2),
            'extra_points' => $departmentExtraPoints,
            'total_employees' => $totalEmployees,
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
    public function calculateFacultyPoints(Faculty $faculty)
    {
        try {
            $totalPoints = 0;
            $totalTeachers = 0;
            $departmentsData = [];

            // Debug
            \Log::info('Starting faculty calculation:', ['faculty_id' => $faculty->name]);

            foreach ($faculty->departments as $department) {
                // Har bir kafedra uchun ballarni hisoblash
                $calculationResult = $this->calculateDepartmentPoints($department);

                // Debug
                \Log::info('Department calculation:', [
                    'department_id' => $department->name,
                    'total_n' => $calculationResult['total_n']
                ]);

                // Umumiy balga qo'shish
                $totalPoints += $calculationResult['total_n'];
                $totalTeachers += $calculationResult['teacher_count'];

                $departmentsData[] = [
                    'department_name' => $department->name,
                    'total_n' => $calculationResult['total_n'],
                    'teacher_count' => $calculationResult['teacher_count'],
                    'extra_points' => $calculationResult['extra_points']
                ];
            }

            // Debug
            \Log::info('Faculty calculation result:', [
                'faculty_id' => $faculty->id,
                'total_points' => $totalPoints
            ]);

            return [
                'total_points' => round($totalPoints, 2),
                'total_teachers' => $totalTeachers,
                'departments_data' => $departmentsData
            ];

        } catch (\Exception $e) {
            \Log::error('Error in calculateFacultyPoints:', [
                'faculty_id' => $faculty->id,
                'error' => $e->getMessage()
            ]);

            return [
                'total_points' => 0,
                'total_teachers' => 0,
                'departments_data' => []
            ];
        }
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
        // Talaba soniga bo'linadigan yo'nalishlar
        $studentDivisorTables = [
            'table_2_3_2',
            'table_2_4_1',
            'table_2_4_2_b',
            'table_3_4_1',
            'table_3_4_2',
            'table_4_1'
        ];

        $html = '
        <div class="w-full p-4 bg-gray-50 rounded-lg">
            <h2 class="text-xl font-bold mb-4 text-gray-800">KAFEDRA REYTINGINI HISOBLASH TARTIBI</h2>

            <div class="mb-4 text-sm flex gap-4">
                <span class="flex items-center">
                    <div class="w-4 h-4 bg-green-200 mr-2"></div>
                    Yuborilgan yo\'nalish ma\'lumotlar soni
                </span>
                <span class="flex items-center">
                    <div class="w-4 h-4 bg-blue-200 mr-2"></div>
                    Yo\'nalish maksimal balli
                </span>
                <span class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-200 mr-2"></div>
                    Ko\'paytma
                </span>
                <span class="flex items-center">
                    <div class="w-4 h-4 bg-indigo-200 mr-2"></div>
                    O\'qituvchilar soni
                </span>
                <span class="flex items-center">
                    <div class="w-4 h-4 bg-pink-200 mr-2"></div>
                    Talabalar soni
                </span>
                <span class="flex items-center">
                    <div class="w-4 h-4 bg-purple-200 mr-2"></div>
                    Natija (N)
                </span>
            </div>

            <!-- O\'qituvchilar soniga bo\'linadigan yo\'nalishlar -->
            <div class="mb-6">
                <h3 class="font-semibold mb-2 text-gray-700">O\'qituvchilar soniga bo\'linadigan yo\'nalishlar:</h3>
                <div class="flex flex-wrap gap-4">';

        foreach ($calculations as $calc) {
            $directionBase = rtrim($calc['direction'], '_');
            if (!in_array($directionBase, $studentDivisorTables)) {
                $limitExplanation = $calc['is_limited'] ?
                    "<span class='text-red-600'>(${calc['original_N']} > ${calc['max_point']} = ${calc['N']})</span>" : '';

                $html .= "
                <div class='bg-white p-2 rounded shadow-sm flex items-center gap-2 text-sm'>
                    <span class='text-gray-700'>{$calc['direction']}:</span>
                    <span class='bg-green-200 px-2 py-1 rounded'>{$calc['records_count']}</span>
                    <span>×</span>
                    <span class='bg-blue-200 px-2 py-1 rounded'>{$calc['max_point']}</span>
                    <span>=</span>
                    <span class='bg-yellow-200 px-2 py-1 rounded'>{$calc['sub_total']}</span>
                    <span>÷</span>
                    <span class='bg-indigo-200 px-2 py-1 rounded'>{$calc['divisor']}</span>
                    <span>=</span>
                    <span class='bg-purple-200 px-2 py-1 rounded'>{$calc['N']}</span>
                    {$limitExplanation}

                </div>";
            }
        }

        $html .= '</div></div>';

        // Talaba soniga bo'linadigan yo'nalishlar
        $html .= '
            <div class="mb-6">
                <h3 class="font-semibold mb-2 text-gray-700">Talaba soniga bo\'linadigan yo\'nalishlar:</h3>
                <div class="flex flex-wrap gap-4">';

        foreach ($calculations as $calc) {
            $directionBase = rtrim($calc['direction'], '_');
            if (in_array($directionBase, $studentDivisorTables)) {
                $limitExplanation = $calc['is_limited'] ?
                    "<span class='text-red-600'>(${calc['original_N']} > ${calc['max_point']} = ${calc['N']})</span>" : '';

                $html .= "
                <div class='bg-white p-2 rounded shadow-sm flex items-center gap-2 text-sm'>
                    <span class='text-gray-700'>{$calc['direction']}:</span>
                    <span class='bg-green-200 px-2 py-1 rounded'>{$calc['records_count']}</span>
                    <span>×</span>
                    <span class='bg-blue-200 px-2 py-1 rounded'>{$calc['max_point']}</span>
                    <span>=</span>
                    <span class='bg-yellow-200 px-2 py-1 rounded'>{$calc['sub_total']}</span>
                    <span>÷</span>
                    <span class='bg-pink-200 px-2 py-1 rounded'>{$calc['divisor']}</span>
                    <span>=</span>
                    <span class='bg-purple-200 px-2 py-1 rounded'>{$calc['N']}</span>
                    {$limitExplanation}

                </div>";
            }
        }

        $html .= '</div></div>';

        // Yakuniy natijalar
        $html .= "
            <div class='bg-white p-4 rounded shadow-sm'>
                <div class='flex flex-col gap-4'>
                    <div class='flex items-center justify-between'>
                        <span class='text-gray-600'>O'qituvchilar soni:</span>
                        <span class='bg-indigo-200 px-3 py-1 rounded font-medium'>{$teacherCount}</span>
                    </div>
                    <div class='flex items-center justify-between'>
                        <span class='text-gray-600'>Talabalar soni:</span>
                        <span class='bg-pink-200 px-3 py-1 rounded font-medium'>{$studentCount}</span>
                    </div>
                    <div class='flex items-center justify-between'>
                        <span class='text-gray-600'>Yuborilgan ma'lumotlar soni:</span>
                        <span class='bg-green-200 px-3 py-1 rounded font-medium'>{$totalInfos}</span>
                    </div>
                    <div class='flex items-center justify-between'>
                        <span class='text-gray-600'>Barcha yo'nalishlar bo'yicha N yig'indisi:</span>
                        <span class='bg-purple-200 px-3 py-1 rounded font-medium'>" . round($totalN, 2) . "</span>
                    </div>
                </div>

                <div class='mt-4 text-lg font-semibold text-center bg-gray-800 text-white py-2 rounded'>
                    KAFEDRA REYTINGI: " . round($totalN, 2) . "
                </div>
            </div>
        </div>";

        return $html;
    }

}
