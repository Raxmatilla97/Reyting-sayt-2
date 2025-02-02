<?php

namespace App\Services;

use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
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


        //O'qituvchilar sonini departments bo'yicha hisoblash
        $departmentCounts = DB::table('users')
            ->select('department_id', DB::raw('count(*) as teachers_count'))
            ->whereNotNull('department_id')  // department_id null bo'lmagan
            ->where('status', 1)
            ->groupBy('department_id')       // department_id bo'yicha guruhlash
            ->pluck('teachers_count', 'department_id')
            ->toArray();

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
        $totalInfos = $department->point_user_deportaments()
            ->whereHas('employee', function ($q) {  // Faqat aktiv hodimlar
                $q->where('status', 1);
            })
            ->where('status', 1)
            ->count();

        // Qo'shimcha ballar - faqat aktiv xodimlarning tasdiqlangan ma'lumotlaridan
        $departmentExtraPoints = $this->calculateExtraPoints($department);

        // O'qituvchilar umumiy balli
        $teacherTotalPoints = $totalDepartmentEmployees > 0
            ? $totalN * $totalDepartmentEmployees
            : 0;

        // O'qituvchilar umumiy ballini to'g'ri hisoblash
        $teacherTotalPoints = $department->point_user_deportaments()
            ->whereHas('employee', function ($q) {
                $q->where('status', 1);
            })
            ->where('status', 1)
            ->sum('point');

        $totalEmployees = $totalDepartmentEmployees;
        $totalWithExtra = round($teacherTotalPoints + $departmentExtraPoints, 2);

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
            $totalStudents = 0; // Yangi qo'shildi
            $departmentsData = [];
            $totalInfos = 0;

            // Debug
            \Log::info('Starting faculty calculation:', ['faculty_id' => $faculty->name]);

            // Fakultet talabalar sonini hisoblash - YANGI QO'SHILDI
            $totalStudents = StudentsCountForDepart::whereIn(
                'departament_id',
                $faculty->departments->where('status', 1)->pluck('id')
            )
                ->where('status', 1)
                ->sum('number');

            // Har bir kafedra uchun ballarni hisoblash
            foreach ($faculty->departments->where('status', 1) as $department) {
                $calculationResult = $this->calculateDepartmentPoints($department);

                // Debug
                \Log::info('Department calculation:', [
                    'department_id' => $department->name,
                    'total_n' => $calculationResult['total_n'],
                    'students' => $calculationResult['student_count'] // Yangi qo'shildi
                ]);

                // Umumiy balga qo'shish
                $totalPoints += $calculationResult['total_n'];
                $totalTeachers += $calculationResult['teacher_count'];
                $totalInfos += $calculationResult['total_infos'];

                $departmentsData[] = [
                    'department_name' => $department->name,
                    'total_n' => $calculationResult['total_n'],
                    'teacher_count' => $calculationResult['teacher_count'],
                    'student_count' => $calculationResult['student_count'], // Yangi qo'shildi
                    'extra_points' => $calculationResult['extra_points'],
                    'calculations' => $calculationResult['calculations']
                ];
            }

            // Debug
            \Log::info('Faculty calculation result:', [
                'faculty_id' => $faculty->id,
                'total_points' => $totalPoints,
                'total_students' => $totalStudents // Yangi qo'shildi
            ]);

            return [
                'total_points' => round($totalPoints, 2),
                'total_teachers' => $totalTeachers,
                'total_students' => $totalStudents, // Yangi qo'shildi
                'departments_data' => $departmentsData,
                'total_infos' => $totalInfos
            ];
        } catch (\Exception $e) {
            \Log::error('Error in calculateFacultyPoints:', [
                'faculty_id' => $faculty->id,
                'error' => $e->getMessage()
            ]);

            return [
                'total_points' => 0,
                'total_teachers' => 0,
                'total_students' => 0,
                'departments_data' => [],
                'total_infos' => 0
            ];
        }
    }

    /**
     * Yo'nalishlar bo'yicha ballarni hisoblash
     */


    /**
     * Qo'shimcha ballarni hisoblash
     */
    protected function calculateExtraPoints($department)
    {

        return $department->point_user_deportaments()
            ->whereHas('employee', function ($q) {
                $q->where('status', 1); // Faqat aktiv xodimlar
            })
            ->where('status', 1)  // Tasdiqlangan ma'lumotlar
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
        return $departmentCounts[$departmentId] ?? 0;
    }

    /**
     * HTML generatsiya
     */
    protected function calculateDirectionalPoints($department, $allMaxPoints, $teacherCount, $studentCount)
    {
        $calculations = [];

        $uniqueColumns = [
            'table_1_6_1_a_' => ['table' => 'table_1_6_1_a_', 'field' => 'ilmiy_maqola_nomi'],
            'table_2_2_1_' => ['table' => 'table_2_2_1_', 'field' => 'darslik_nomi'],
            'table_2_2_2_' => ['table' => 'table_2_2_2_', 'field' => 'qollanma_nomi']
        ];

        foreach ($allMaxPoints as $direction => $points) {
            $columnName = $direction . 'id';
            $records = 0;

            if (array_key_exists($direction, $uniqueColumns)) {
                $tableInfo = $uniqueColumns[$direction];
                $tableName = $tableInfo['table'];
                $fieldName = $tableInfo['field'];

                // Umumiy soni
                $totalCount = $department->point_user_deportaments()
                    ->whereHas('employee', function ($q) {
                        $q->where('status', 1);
                    })
                    ->where('status', 1)
                    ->whereNotNull($columnName)
                    ->count();

                // Unikal sonni hisoblash
                $uniqueCount = DB::table('point_user_deportaments as p')
                    ->select(DB::raw("COUNT(DISTINCT t.{$fieldName}) as count"))
                    ->join("{$tableName} as t", "t.id", "=", "p.{$columnName}")
                    ->join('users as u', 'u.id', '=', 'p.user_id')
                    ->where('p.departament_id', $department->id)
                    ->where('p.status', 1)
                    ->where('u.status', 1)
                    ->whereNotNull("p.{$columnName}")
                    ->value('count');

                $records = $uniqueCount; // Unikal sonni ishlatamiz

                $additionalInfo = [
                    'total_count' => $totalCount,
                    'unique_count' => $uniqueCount,
                    'is_unique' => true
                ];
            } else {
                $records = $department->point_user_deportaments()
                    ->whereHas('employee', function ($q) {
                        $q->where('status', 1);
                    })
                    ->where('status', 1)
                    ->whereNotNull($columnName)
                    ->count();

                $additionalInfo = [
                    'total_count' => $records,
                    'unique_count' => $records,
                    'is_unique' => false
                ];
            }

            if ($records > 0) {
                $maxPoint = $points['max'];
                $directionBase = rtrim($direction, '_');
                $divisor = in_array($directionBase, $this->studentDivisorTables)
                    ? ($studentCount ?: 1)
                    : ($teacherCount ?: 1);

                $subtotal = $records * $maxPoint; // Unikal son bilan ko'paytiramiz
                $N = min($subtotal / $divisor, $maxPoint);

                $calculations[] = [
                    'direction' => $direction,
                    'records_count' => $records, // Unikal son
                    'max_point' => $maxPoint,
                    'sub_total' => $subtotal,
                    'divisor' => $divisor,
                    'N' => round($N, 2),
                    'original_N' => round($subtotal / $divisor, 2),
                    'is_limited' => $subtotal / $divisor > $maxPoint,
                    'divisor_type' => in_array($directionBase, $this->studentDivisorTables)
                        ? 'Talabalar soni'
                        : "O'qituvchilar soni",
                    'additional_info' => $additionalInfo
                ];
            }
        }

        return $calculations;
    }

    public function generateCalculationHTML($calculations, $teacherCount, $studentCount, $totalN, $totalInfos)
    {
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

        <div class="mb-4 text-sm flex flex-wrap gap-4">
            <span class="flex items-center">
                <div class="w-4 h-4 bg-green-200 mr-2"></div>
                Yo\'nalish ma\'lumotlar soni
            </span>
            <span class="flex items-center">
                <div class="w-4 h-4 bg-red-200 mr-2"></div>
               Ham mualliflik maqolalar soni (qisqartirma)
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
        </div>';

        // O'qituvchilar soniga bo'linadigan yo'nalishlar
        $html .= '
        <div class="mb-6">
            <h3 class="font-semibold mb-2 text-gray-700">O\'qituvchilar soniga bo\'linadigan yo\'nalishlar:</h3>
            <div class="flex flex-wrap gap-4">';

        foreach ($calculations as $calc) {
            $directionBase = rtrim($calc['direction'], '_');
            if (!in_array($directionBase, $studentDivisorTables)) {
                $limitExplanation = $calc['is_limited'] ?
                    "<span class='text-red-600'>(${calc['original_N']} > ${calc['max_point']} = ${calc['N']})</span>" : '';

                $countDisplay = !empty($calc['additional_info']) && $calc['additional_info']['is_unique'] ?
                    "<span class='bg-green-200 px-2 py-1 rounded'>{$calc['additional_info']['total_count']}</span>
                 <span class='text-gray-600 mx-1'>→</span>
                 <span class='bg-red-200 px-2 py-1 rounded'>{$calc['additional_info']['unique_count']}</span>" :
                    "<span class='bg-green-200 px-2 py-1 rounded'>{$calc['records_count']}</span>";

                $html .= "
            <div class='bg-white p-2 rounded shadow-sm flex items-center gap-2 text-sm'>
                <span class='text-gray-700'>{$calc['direction']}:</span>
                {$countDisplay}
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

                $countDisplay = !empty($calc['additional_info']) && $calc['additional_info']['is_unique'] ?
                    "<span class='bg-green-200 px-2 py-1 rounded'>{$calc['additional_info']['total_count']}</span>
                 <span class='text-gray-600 mx-1'>→</span>
                 <span class='bg-red-200 px-2 py-1 rounded'>{$calc['additional_info']['unique_count']}</span>" :
                    "<span class='bg-green-200 px-2 py-1 rounded'>{$calc['records_count']}</span>";

                $html .= "
            <div class='bg-white p-2 rounded shadow-sm flex items-center gap-2 text-sm'>
                <span class='text-gray-700'>{$calc['direction']}:</span>
                {$countDisplay}
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

        // Hisoblanmagan ma'lumotlar sonini hisoblash
        $totalNonUnique = 0;
        foreach ($calculations as $calc) {
            if (!empty($calc['additional_info']) && $calc['additional_info']['is_unique']) {
                $totalNonUnique += ($calc['additional_info']['total_count'] - $calc['additional_info']['unique_count']);
            }
        }

        // Yakuniy statistika
        $uniqueInfoCount = $totalInfos - $totalNonUnique;

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
                    <div class='flex items-center gap-2'>
                        <span class='bg-green-200 px-3 py-1 rounded font-medium'>{$totalInfos}</span>
                        <span class='text-gray-600'>→</span>
                        <span class='bg-red-200 px-3 py-1 rounded font-medium'>{$uniqueInfoCount}</span>
                        <span class='text-sm text-gray-500'>(Hisoblanmagan: {$totalNonUnique})</span>
                    </div>
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
