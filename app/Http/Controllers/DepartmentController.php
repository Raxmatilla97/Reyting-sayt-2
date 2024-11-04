<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\PointUserDeportament;
use App\Models\StudentsCountForDepart;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        // Departamentlar uchun so'rov yaratish va point_user_deportaments ma'lumotlarini oldindan yuklash
        $departments = Department::with(['point_user_deportaments' => function ($query) {
            $query->where('status', 1)->with('departPoint');
        }])->where('status', 1);

        // Agar ism berilgan bo'lsa, qidirish
        if ($request->filled('name')) {
            $name = '%' . $request->name . '%';
            $departments->where('name', 'like', $name);
        }

        // Tartiblash va sahifalash
        $departments = $departments->orderBy('created_at', 'desc')->paginate(21);

        // Kafedrada nechta o'qituvchi borligi haqidagi massiv
        $departmentCounts = config('departament_tichers_count');

        if ($departmentCounts === null) {
            $departmentCounts = include(config_path('departament_tichers_count.php'));
        }

        // Har bir departament uchun hisoblarni amalga oshirish
        foreach ($departments as $department) {
            $departmentPointTotal = 0;
            // Departamentga tegishli barcha balllarni hisoblab chiqish
            foreach ($department->point_user_deportaments as $pointEntry) {
                // point_user_deportaments jadvalidagi ballni qo'shish
                $departmentPointTotal += $pointEntry->point;

                // Agar qo'shimcha departPoint mavjud bo'lsa, uni ham qo'shish
                if ($pointEntry->departPoint) {
                    $departmentPointTotal += $pointEntry->departPoint->point;
                }
            }

            // Departamentdagi o'qituvchilar sonini olish
            $teacherCount = null;
            foreach ($departmentCounts as $facultyId => $facultyDepartments) {
                if (isset($facultyDepartments[$department->id])) {
                    $teacherCount = $facultyDepartments[$department->id];
                    break;
                }
            }

            // Agar o'qituvchilar soni mavjud bo'lsa, o'rtacha ballni hisoblash
            if ($teacherCount) {
                // Umumiy ballni o'qituvchilar soniga bo'lib, 2 xonagacha yaxlitlash
                $department->totalPoints = round($departmentPointTotal / $teacherCount, 2);
            } else {
                // Agar o'qituvchilar soni ma'lum bo'lmasa, 'N/A' qiymatini berish
                $department->totalPoints = 'N/A';
            }

            // O'qituvchilar sonini saqlash
            $department->teacherCount = $teacherCount ?? 'N/A';
        }

        // Ko'rinishni departamentlar ma'lumotlari bilan qaytarish
        return view('dashboard.department.index', compact('departments'));
    }


    public function departmentFormChose()
    {

        // Kafedra ma'lumotlarini yuklash uchun uni bo'limlari ro'yxati
        $jadvallar_codlari = Config::get('dep_emp_tables.department');;;

        return view('dashboard.department_category_choose', compact('jadvallar_codlari'));
    }

    public function departmentShow(string $slug)
    {
        $department = Department::where('slug', $slug)->firstOrFail();
        $pointUserInformations = PointUserDeportament::where('departament_id', $department->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Department va Employee konfiguratsiyalarini olish
        $departmentCodlari = Config::get('dep_emp_tables.department');
        $employeeCodlari = Config::get('dep_emp_tables.employee');
        $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

        // Talabalar sonini olish
        $studentCounts = StudentsCountForDepart::with('department')->get();
        $departmentStudentCount = StudentsCountForDepart::where('departament_id', $department->id)
            ->where('status', 1)  // aktiv talabalar sonini olish
            ->value('number') ?? 0;

        $arrayKey = [];
        foreach ($jadvallarCodlari as $key => $value) {
            $arrayKey[$key . 'id'] = $key;
        }

        foreach ($pointUserInformations as $departament_item) {
            foreach ($arrayKey as $column => $originalKey) {
                if (isset($departament_item->$column)) {
                    $departament_item->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $departament_item->murojaat_codi = $originalKey;
                    break;
                }
            }
        }

        $totalEmployees = $department->employee()->count();
        $departmentCounts = config('departament_tichers_count') ?? include(config_path('departament_tichers_count.php'));

        // Kafedradagi o'qituvchilar sonini olish
        $totalDepartmentEmployees = null;
        foreach ($departmentCounts as $facultyId => $facultyDepartments) {
            if (isset($facultyDepartments[$department->id])) {
                $totalDepartmentEmployees = $facultyDepartments[$department->id];
                break;
            }
        }

        $totalDepartmentEmployees = $totalDepartmentEmployees ?? 0;
        $unregisteredEmployees = $totalDepartmentEmployees;

        // Konfiguratsiya faylidan maksimal ballarni olish
        $maxPoints = Config::get('max_points_dep_emp');
        $departmentMaxPoints = $maxPoints['department'];
        $employeeMaxPoints = $maxPoints['employee'];
        $allMaxPoints = array_merge($departmentMaxPoints, $employeeMaxPoints);

        // Debug: Talaba sonini tekshirish
        \Log::info('Student Count:', ['count' => $departmentStudentCount]);


        // Talaba soniga bo'linadigan yo'nalishlar
        $studentDivisorTables = [
            'table_2_3_2',
            'table_2_4_1',
            'table_2_4_2_b',
            'table_3_4_1',
            'table_3_4_2',
            'table_4_1'
        ];

        $directionalCalculations = [];
        $totalN = 0;
        $totalInfos = $department->point_user_deportaments()->where('status', 1)->count();

        // Kafedraga o'tgan qo'shimcha ballarni hisoblash
        $departmentExtraPoints = $department->point_user_deportaments()
            ->where('status', 1)
            ->whereHas('departPoint')
            ->with('departPoint')
            ->get()
            ->sum(function ($pointEntry) {
                return $pointEntry->departPoint->point;
            });

        foreach ($allMaxPoints as $direction => $points) {
            $columnName = $direction . 'id';

            // Debug: Har bir yo'nalish uchun SQL so'rovni tekshirish
            \Log::info('Checking direction:', [
                'direction' => $direction,
                'column' => $columnName,
                'is_student_table' => in_array(rtrim($direction, '_'), $studentDivisorTables)
            ]);


            $recordsCount = $department->point_user_deportaments()
                ->where('status', 1)
                ->whereNotNull($columnName)
                ->count();

            // Debug: Ma'lumotlar sonini tekshirish
            \Log::info('Records found:', [
                'direction' => $direction,
                'count' => $recordsCount
            ]);


            if ($recordsCount > 0) {
                $maxPoint = $points['max'];

                // Bo'linuvchi sonni aniqlash - yo'nalish nomidan oxirgi _ belgisini olib tashlab tekshiramiz
                $divisor = in_array(rtrim($direction, '_'), $studentDivisorTables) ?
                    ($departmentStudentCount ?: 1) : ($totalDepartmentEmployees ?: 1);

                // Debug: Bo'linuvchi sonni tekshirish
                \Log::info('Divisor check:', [
                    'direction' => $direction,
                    'is_student_table' => in_array(rtrim($direction, '_'), $studentDivisorTables),
                    'divisor' => $divisor,
                    'student_count' => $departmentStudentCount,
                    'teacher_count' => $totalDepartmentEmployees
                ]);
                $subtotal = $recordsCount * $maxPoint;
                $N = $subtotal / $divisor;
                $N = min($N, $maxPoint);

                $totalN += $N;

                $directionalCalculations[] = [
                    'direction' => $direction,
                    'records_count' => $recordsCount,
                    'max_point' => $maxPoint,
                    'sub_total' => $subtotal,
                    'divisor' => $divisor,
                    'N' => round($N, 2),
                    'original_N' => round($subtotal / $divisor, 2),
                    'is_limited' => $subtotal / $divisor > $maxPoint,
                    'divisor_type' => in_array(rtrim($direction, '_'), $studentDivisorTables) ?
                        'Talabalar soni' : "O'qituvchilar soni"
                ];
            }
        }

        // Debug: Yakuniy calculations massivini tekshirish
        \Log::info('Final calculations:', [
            'calculations' => $directionalCalculations
        ]);

        $totalPoints = round($totalN, 2);

        // O'qituvchilar ballari hisobi
        if ($totalDepartmentEmployees > 0) {
            $teacherTotalPoints = $totalN * $totalDepartmentEmployees;
            $totalWithExtra = round($teacherTotalPoints + $departmentExtraPoints, 2);
        } else {
            $teacherTotalPoints = 0;
            $totalWithExtra = round($departmentExtraPoints, 2);
        }

        $pointsCalculationExplanation = $this->generateCalculationHTML(
            $directionalCalculations,
            $totalDepartmentEmployees,
            $departmentStudentCount,
            $totalN,
            $totalInfos
        );

        $timeAgo = $this->getTimeAgo($department);
        $fullName = $this->getLastUserFullName($department);

        return view('dashboard.department.show', compact(
            'department',
            'pointUserInformations',
            'totalEmployees',
            'totalPoints',
            'totalInfos',
            'unregisteredEmployees',
            'pointsCalculationExplanation',
            'departmentExtraPoints',
            'teacherTotalPoints',
            'totalWithExtra',
            'timeAgo',
            'fullName'
        ));
    }

    private function getTimeAgo($department)
    {
        $mostRecentTimestamp = $department->point_user_deportaments()->max('created_at');

        if (!$mostRecentTimestamp) {
            return "Ma'lumot yo'q";
        }

        $mostRecentTime = Carbon::parse($mostRecentTimestamp);
        $now = Carbon::now();

        $diffInSeconds = $now->diffInSeconds($mostRecentTime);
        $diffInMinutes = $now->diffInMinutes($mostRecentTime);
        $diffInHours = $now->diffInHours($mostRecentTime);
        $diffInDays = $now->diffInDays($mostRecentTime);

        if ($diffInSeconds < 60) {
            return $diffInSeconds . ' soniya oldin';
        } elseif ($diffInMinutes < 60) {
            return $diffInMinutes . ' daqiqa oldin';
        } elseif ($diffInHours < 24) {
            return $diffInHours . ' soat oldin';
        } else {
            return $diffInDays . ' kun oldin';
        }
    }

    private function getLastUserFullName($department)
    {
        $mostRecentEntry = $department->point_user_deportaments()
            ->orderBy('created_at', 'desc')
            ->first();

        return $mostRecentEntry ? $mostRecentEntry->employee->full_name : "Ma'lumot topilmadi!";
    }

    private function generateCalculationHTML($calculations, $teacherCount, $studentCount, $totalN, $totalInfos)
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
