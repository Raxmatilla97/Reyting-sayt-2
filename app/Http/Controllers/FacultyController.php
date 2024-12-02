<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Http;
use App\Models\StudentsCountForDepart;
use Illuminate\Support\Facades\Config;
use App\Services\PointCalculationService;
use Illuminate\Pagination\LengthAwarePaginator;

class FacultyController extends Controller
{
    protected $pointCalculationService;

    public function __construct(PointCalculationService $pointCalculationService)
    {
        $this->pointCalculationService = $pointCalculationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculties = Faculty::with(['departments' => function ($query) {
            $query->where('status', 1);
        }])
            ->where('status', 1)
            ->get();

        $facultiesData = $faculties->map(function ($faculty) {
            $calculationResult = $this->pointCalculationService->calculateFacultyPoints($faculty);

            // Fakultet o'qituvchilari sonini hisoblash
            $facultyTotalTeachers = $faculty->departments
                ->where('status', 1)
                ->sum(function ($department) {
                    return DB::table('users')
                        ->where('department_id', $department->id)
                        ->where('status', 1)
                        ->count();
                });

            // Fakultet talabalar sonini hisoblash
            $facultyTotalStudents = StudentsCountForDepart::whereIn(
                'departament_id',
                $faculty->departments->where('status', 1)->pluck('id')
            )
                ->where('status', 1)
                ->sum('number');

            // Talabalar soni 0 bo'lmasligi uchun
            $facultyTotalStudents = $facultyTotalStudents ?: 1;

            // Talabalar soniga bo'linadigan jadvallar
            $studentDivisorTables = [
                'table_2_3_2',
                'table_2_4_1',
                'table_2_4_2_b',
                'table_3_4_1',
                'table_3_4_2',
                'table_4_1'
            ];

            // Konfiguratsiyadan yo'nalishlar ro'yxatini olish
            $departmentCodlari = Config::get('dep_emp_tables.department');
            $employeeCodlari = Config::get('dep_emp_tables.employee');
            $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

            // Umumiy ball yig'indisi
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
                    $divisor = $isDivisorStudent ? $facultyTotalStudents : $facultyTotalTeachers;
                    $divisor = $divisor ?: 1; // 0 ga bo'linishni oldini olish

                    $N = min($subtotal / $divisor, $maxPoint);
                    $totalN += $N;
                }
            }

            return (object)[
                'id' => $faculty->id,
                'name' => $faculty->name,
                'slug' => $faculty->slug,
                'status' => $faculty->status,
                'departments' => $faculty->departments,
                'total_points' => round($totalN, 2),
                'total_teachers' => $facultyTotalTeachers
            ];
        });

        $page = request('page', 1);
        $perPage = 15;

        $items = $facultiesData->forPage($page, $perPage);

        $faculties = new LengthAwarePaginator(
            $items,
            $facultiesData->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        return view('dashboard.faculty.index', compact('faculties'));
    }


    public function facultyShow($slug)
{
    $faculty = Faculty::where('slug', $slug)->firstOrFail();

    $pointUserInformations = PointUserDeportament::whereIn(
        'departament_id',
        $faculty->departments->where('status', 1)->pluck('id')
    )
        ->whereHas('employee', function ($q) {
            $q->where('status', 1);
        })
        ->orderBy('created_at', 'desc')
        ->paginate('15');

    // Department va Employee konfiguratsiyalarini olish
    $departmentCodlari = Config::get('dep_emp_tables.department');
    $employeeCodlari = Config::get('dep_emp_tables.employee');

    // Ikkala massivni birlashtirish
    $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

    // Talabalar soniga bo'linadigan jadvallar
    $studentDivisorTables = [
        'table_2_3_2',
        'table_2_4_1',
        'table_2_4_2_b',
        'table_3_4_1',
        'table_3_4_2',
        'table_4_1'
    ];

    // Har bir massiv elementiga "key" nomli yangi maydonni qo'shish
    $arrayKey = [];
    foreach ($jadvallarCodlari as $key => $value) {
        $arrayKey[$key . 'id'] = $key;
    }

    // Ma'lumotlar massivini tekshirish
    foreach ($pointUserInformations as $pointUserInformation) {
        foreach ($arrayKey as $column => $originalKey) {
            if (isset($pointUserInformation->$column)) {
                $pointUserInformation->murojaat_nomi = $jadvallarCodlari[$originalKey];
                $pointUserInformation->murojaat_codi = $originalKey;
                break;
            }
        }
    }

    // Fakultet o'qituvchilari sonini hisoblash
    $totalTeachers = $faculty->departments
        ->where('status', 1)
        ->sum(function ($department) {
            return DB::table('users')
                ->where('department_id', $department->id)
                ->where('status', 1)
                ->count();
        });

    // Fakultet umumiy ma'lumotlar soni
    $totalInfos = $faculty->departments
        ->where('status', 1)
        ->sum(function ($department) {
            return $department->point_user_deportaments()
                ->whereHas('employee', function ($q) {
                    $q->where('status', 1);
                })
                ->where('status', 1)
                ->count();
        });

    // Fakultetning barcha talabalar sonini hisoblash
    $totalStudents = StudentsCountForDepart::whereIn('departament_id', $faculty->departments->where('status', 1)->pluck('id'))
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

    // Fakultet reytingi
    $totalPoints = round($totalN, 2);
    $departmentsData = $this->pointCalculationService->calculateFacultyPoints($faculty)['departments_data'];

    // Eng so'nggi ma'lumot vaqti va egasini aniqlash
    $latestInfo = PointUserDeportament::whereIn(
        'departament_id',
        $faculty->departments->where('status', 1)->pluck('id')
    )
        ->whereHas('employee', function ($q) {
            $q->where('status', 1);
        })
        ->where('status', 1)
        ->with(['employee' => function ($q) {
            $q->where('status', 1);
        }])
        ->latest()
        ->first();

    if ($latestInfo && $latestInfo->employee) {
        $timeAgo = $latestInfo->created_at->diffForHumans();
        $fullName = $latestInfo->employee->full_name ?? "Ma'lumot topilmadi!";
        $departEmployee = $latestInfo->employee->department->name ?? "Ma'lumot topilmadi!";
    } else {
        $timeAgo = "Ma'lumot yo'q";
        $fullName = "Ma'lumot topilmadi!";
        $departEmployee = "Ma'lumot topilmadi!";
    }

    // Service ma'lumotlarini department ma'lumotlari bilan birlashtirish
    $departments = $faculty->departments
    ->where('status', 1)
    ->map(function ($department) use ($departmentsData) { // $calculationResult o'rniga $departmentsData
        // departments_data ni department_name bo'yicha qidirish
        $departmentData = collect($departmentsData)
            ->firstWhere('department_name', $department->name);

        return [
            'department' => $department,
            'points' => [
                'total_points' => $departmentData['total_n'] ?? 0,
                'teacher_count' => $departmentData['teacher_count'] ?? 0,
                'extra_points' => $departmentData['extra_points'] ?? 0,
                'approved_count' => $department->point_user_deportaments()
                    ->whereHas('employee', function ($q) {
                        $q->where('status', 1);
                    })
                    ->where('status', 1)
                    ->count()
            ]
        ];
    })
    ->values()
    ->all();

    // Bar chart uchun ma'lumotlarni tayyorlash
    $barChartData = collect($departments)->map(function ($item) {
        return [
            'x' => mb_substr($item['department']->name, 0, 15),
            'full_name' => $item['department']->name,
            'y' => round($item['points']['total_points'], 2)
        ];
    })->values()->toArray();

    // Radar chart uchun ma'lumotlarni yig'ish
    $radarChartData = [];
    foreach ($faculty->departments->where('status', 1) as $department) {
        foreach ($jadvallarCodlari as $key => $value) {
            $count = $department->point_user_deportaments()
                ->whereHas('employee', function ($q) {
                    $q->where('status', 1);
                })
                ->where($key . 'id', '!=', null)
                ->where('status', 1)
                ->count();

            if ($count > 0) {
                if (!isset($radarChartData[$key])) {
                    $radarChartData[$key] = 0;
                }
                $radarChartData[$key] += $count;
            }
        }
    }

    // Radar chart uchun ma'lumotlarni formatlash
    $radarData = [
        'categories' => array_keys($radarChartData),
        'series' => array_values($radarChartData)
    ];

    // HTML generatsiya
    $pointsCalculationExplanation = $this->generateFacultyCalculationHTML(
        $faculty,
        $departments,
        $totalTeachers,
        $totalStudents,
        $totalPoints,
        $totalInfos
    );

    return view('dashboard.faculty.show', compact(
        'faculty',
        'pointUserInformations',
        'totalPoints',
        'totalTeachers',
        'totalInfos',
        'timeAgo',
        'fullName',
        'departments',
        'barChartData',
        'radarData',
        'departEmployee',
        'pointsCalculationExplanation',
    ));
}


    public function getItemDetails($id)
    {
        $item = PointUserDeportament::findOrFail($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $relatedData = [];
        $relationships = $item->getRelationships();

        // Tablename ni olish va oxiriga _ qo'shish
        $tableName = '';
        foreach ($item->getRelationships() as $relationship) {
            $foreignKey = $relationship . '_id';
            if (isset($item->{$foreignKey}) && !is_null($item->{$foreignKey})) {
                $tableName = $relationship;
                if (!str_ends_with($tableName, '_')) {
                    $tableName .= '_';
                }
                break;
            }
        }

        if (is_array($relationships)) {
            foreach ($relationships as $relationship) {
                $foreignKey = $relationship . '_id';
                if (isset($item->{$foreignKey}) && !is_null($item->{$foreignKey})) {
                    $relatedModelClass = $this->getModelClassForRelation($relationship);
                    $relatedData[$relationship] = $relatedModelClass::find($item->{$foreignKey});
                } else {
                    $relatedData[$relationship] = null;
                }
            }
        } else {
            return response()->json(['error' => 'No relationships defined'], 500);
        }

        // Employee formalar uchun tablitsalar ro'yxati
        $employeeTables = [
            'table_1_1_',
            'table_1_2_',
            'table_1_3_1_a_',
            'table_1_3_1_b_',
            'table_1_3_2_a_',
            'table_1_3_2_b_',
            'table_1_4_',
            'table_1_5_1_',
            'table_1_5_1_a_',
            'table_1_6_1_',
            'table_1_6_1_a_',
            'table_1_6_2_',
            'table_1_9_1_',
            'table_1_9_2_',
            'table_1_9_3_',
            'table_2_2_1_',
            'table_2_2_2_',
            'table_2_4_2_'
        ];

        // Department formalar uchun tablitsalar ro'yxati
        $departmentTables = [
            'table_1_7_1_',
            'table_1_7_2_',
            'table_1_7_3_',
            'table_2_3_1_',
            'table_2_3_2_',
            'table_2_4_1_',
            'table_2_4_2_b_',
            'table_2_5_',
            'table_3_4_1_',
            'table_3_4_2_',
            'table_4_1_'
        ];

        $data = [
            'item' => $item,
            'relatedData' => $relatedData,
            'year' => $item->year,
            'arizaga_javob' => $item->arizaga_javob,
            'tekshirilgan_sana' => $item->updated_at,
            'yaratilgan_sana' => $item->created_at,
            'qoyilgan_ball' => is_numeric($item->point) ? round($item->point, 2) : 0,
            'status' => $item->status,
            'creator_id' => $item->user_id
        ];

        // Form turini aniqlash
        if (in_array($tableName, $employeeTables)) {
            $formType = 'employee';
        } elseif (in_array($tableName, $departmentTables)) {
            $formType = 'department';
        } else {
            // Agar aniqlanmasa, table_1_7 bilan boshlanganlarni department qilish
            $formType = strpos($tableName, 'table_') === 0 ? 'department' : 'employee';
        }
        try {
            $view = view('dashboard.faculty.item-details', $data)->render();

            return response()->json([
                'html' => $view,
                'status' => $item->status,
                'tableName' => $tableName,
                'itemId' => $id,
                'formType' => $formType,
                'creator_id' => $item->user_id, // Muhim: edit button uchun kerak
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'View rendering failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    private function getModelClassForRelation($relation)
    {
        // Tegishli model uchun to'liq class nomini tuzish
        return "\\App\\Models\\Tables\\" . ucfirst($relation) . "_";
    }

    // Hali ishlamaydigan funksiya keyinchalik ishlataman
    public function update()
    {
        $token = env('API_HEMIS_TOKEN');
        $apiUrl = env('API_HEMIS_URL') . '/rest/v1/data/department-list?limit=200&active=1&_structure_type=11&localityType.name=Mahalliy&structureType.name=Fakultet';

        $response = Http::withToken($token)->get($apiUrl);

        if ($response->failed()) {
            return response()->json(['error' => 'API ga so\'rov yuborishda xatolik yuz berdi.'], 500);
        }

        $hemisFaculties = $response->json()['data']['items'] ?? [];

        if (empty($hemisFaculties)) {
            return response()->json(['message' => 'API dan ma\'lumotlar olinmadi.'], 404);
        }

        $existingFaculties = Faculty::all()->keyBy('id');

        $newCount = 0;
        $updatedCount = 0;
        $unchangedCount = 0;

        foreach ($hemisFaculties as $hemisFaculty) {
            if (!$existingFaculties->has($hemisFaculty['id'])) {
                Faculty::create([
                    'id' => $hemisFaculty['id'],
                    'name' => $hemisFaculty['name'],
                    'slug' => Str::slug($hemisFaculty['name'] . "-fakulteti"),
                    'status' => true,
                ]);
                $newCount++;
            } else {
                $existingFaculty = $existingFaculties->get($hemisFaculty['id']);
                if ($existingFaculty->id !== $hemisFaculty['id']) {
                    $existingFaculty->update([

                        'name' => $hemisFaculty['name'],
                        'slug' => Str::slug($hemisFaculty['name'] . "-fakulteti"),
                        'status' => true,
                    ]);
                    $updatedCount++;
                } else {
                    $unchangedCount++;
                }
            }
        }

        $message = "Yangilash yakunlandi. ";
        if ($newCount > 0) {
            $message .= "{$newCount} ta yangi fakultet qo'shildi. ";
        }
        if ($updatedCount > 0) {
            $message .= "{$updatedCount} ta fakultet yangilandi. ";
        }
        if ($unchangedCount > 0) {
            $message .= "{$unchangedCount} ta fakultet o'zgarishsiz qoldi. ";
        }
        if ($newCount == 0 && $updatedCount == 0) {
            $message = "Fakultetlar ro'yxatida o'zgarish yo'q.";
        }

        return response()->json(['message' => $message]);
    }


    /**
     * Fakultet reytingini yo'nalishlar kesimida hisoblash va HTML generatsiya
     */
    protected function generateFacultyCalculationHTML($faculty, $departmentsData, $totalTeachers, $totalStudents, $totalPoints, $totalInfos)
    {
        // Debug uchun
        \Log::info('Calculation started', [
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers
        ]);

        // Konfiguratsiyadan yo'nalishlar ro'yxatini olish
        $departmentCodlari = Config::get('dep_emp_tables.department');
        $employeeCodlari = Config::get('dep_emp_tables.employee');
        $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

        // Talabalar soniga bo'linadigan jadvallar (oxiriga _ qo'shamiz)
        $studentDivisorTables = [
            'table_2_3_2',
            'table_2_4_1',
            'table_2_4_2_b',
            'table_3_4_1',
            'table_3_4_2',
            'table_4_1'
        ];

        // Debug uchun
        \Log::info('Student divisor tables:', $studentDivisorTables);

        // Yo'nalishlar bo'yicha ma'lumotlarni yig'ish
        $directionalData = [];
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
                // Debug qilish
                \Log::info("Processing direction: $code with count: $recordsCount", [
                    'isStudentDivisor' => in_array(rtrim($code, '_'), $studentDivisorTables)
                ]);

                $subtotal = $recordsCount * $maxPoint;
                // Talabalar soniga bo'linadigan jadvallarni tekshirish
                $isDivisorStudent = in_array(rtrim($code, '_'), $studentDivisorTables);
                $divisor = $isDivisorStudent ? $totalStudents : $totalTeachers;
                $divisor = $divisor ?: 1; // 0 ga bo'linishni oldini olish

                $N = min($subtotal / $divisor, $maxPoint);

                $directionalData[] = [
                    'direction' => $code,
                    'name' => $name,
                    'records_count' => $recordsCount,
                    'max_point' => $maxPoint,
                    'sub_total' => $subtotal,
                    'divisor' => $divisor,
                    'N' => round($N, 2),
                    'original_N' => round($subtotal / $divisor, 2),
                    'is_limited' => $subtotal / $divisor > $maxPoint,
                    'divisor_type' => $isDivisorStudent ? 'Talabalar' : "O'qituvchilar"
                ];
            }
        }

        // Debug: Tekshiramiz qancha yo'nalish qaysi turga tegishli
        $studentDivisorCount = count(array_filter($directionalData, function ($item) {
            return $item['divisor_type'] === 'Talabalar';
        }));

        $teacherDivisorCount = count(array_filter($directionalData, function ($item) {
            return $item['divisor_type'] === "O'qituvchilar";
        }));

        \Log::info('Direction counts:', [
            'studentDivisor' => $studentDivisorCount,
            'teacherDivisor' => $teacherDivisorCount
        ]);

        // HTML generatsiyasi
        $html = '
        <div class="w-full p-4 bg-gray-50 rounded-lg">
            <h2 class="text-xl font-bold mb-4 text-gray-800">FAKULTET REYTINGINI HISOBLASH TARTIBI</h2>
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
            </div>';

        // O'qituvchilar soniga bo'linadigan yo'nalishlar
        $html .= '<div class="mb-6">';
        $html .= '<h3 class="font-semibold mb-2 text-gray-700">O\'qituvchilar soniga bo\'linadigan yo\'nalishlar:</h3>';
        $html .= '<div class="flex flex-wrap gap-4">';

        foreach ($directionalData as $data) {
            if ($data['divisor_type'] === "O'qituvchilar") {
                $html .= $this->generateDirectionHTML($data);
            }
        }

        $html .= '</div></div>';

        // Talabalar soniga bo'linadigan yo'nalishlar
        $html .= '<div class="mb-6">';
        $html .= '<h3 class="font-semibold mb-2 text-gray-700">Talabalar soniga bo\'linadigan yo\'nalishlar:</h3>';
        $html .= '<div class="flex flex-wrap gap-4">';

        foreach ($directionalData as $data) {
            if ($data['divisor_type'] === 'Talabalar') {
                $html .= $this->generateDirectionHTML($data);
            }
        }

        $html .= '</div></div>';

        // Yakuniy natijalar
        $html .= $this->generateSummaryHTML($totalTeachers, $totalStudents, $totalInfos, $directionalData);

        $html .= '</div>';

        // Debug: Yakuniy HTML uzunligi
        \Log::info('Generated HTML length:', ['length' => strlen($html)]);

        return $html;
    }
    // Yo'nalish HTML ni generatsiya qilish uchun yordamchi metod
    private function generateDirectionHTML($data)
    {
        $limitExplanation = $data['is_limited']
            ? "<span class='text-red-600'>(${data['original_N']} > ${data['max_point']} = ${data['N']})</span>"
            : '';

        $divisorClass = $data['divisor_type'] === 'Talabalar' ? 'bg-pink-200' : 'bg-indigo-200';

        return "
           <div class='bg-white p-2 rounded shadow-sm flex items-center gap-2 text-sm'>
               <span class='text-gray-700'>{$data['direction']}:</span>
               <span class='bg-green-200 px-2 py-1 rounded'>{$data['records_count']}</span>
               <span>×</span>
               <span class='bg-blue-200 px-2 py-1 rounded'>{$data['max_point']}</span>
               <span>=</span>
               <span class='bg-yellow-200 px-2 py-1 rounded'>{$data['sub_total']}</span>
               <span>÷</span>
               <span class='{$divisorClass} px-2 py-1 rounded'>{$data['divisor']}</span>
               <span>=</span>
               <span class='bg-purple-200 px-2 py-1 rounded'>{$data['N']}</span>
               {$limitExplanation}
           </div>";
    }

    // Yakuniy natijalar HTML ni generatsiya qilish uchun yordamchi metod
    private function generateSummaryHTML($totalTeachers, $totalStudents, $totalInfos, $directionalData)
    {
        $totalN = array_sum(array_column($directionalData, 'N'));

        return "
           <div class='bg-white p-4 rounded shadow-sm'>
               <div class='flex flex-col gap-4'>
                   <div class='flex items-center justify-between'>
                       <span class='text-gray-600'>Fakultet o'qituvchilari soni:</span>
                       <span class='bg-indigo-200 px-3 py-1 rounded font-medium'>{$totalTeachers}</span>
                   </div>
                   <div class='flex items-center justify-between'>
                       <span class='text-gray-600'>Fakultet talabalari soni:</span>
                       <span class='bg-pink-200 px-3 py-1 rounded font-medium'>{$totalStudents}</span>
                   </div>
                   <div class='flex items-center justify-between'>
                       <span class='text-gray-600'>Yuborilgan ma'lumotlar soni:</span>
                       <span class='bg-green-200 px-3 py-1 rounded font-medium'>{$totalInfos}</span>
                   </div>
                   <div class='flex items-center justify-between'>
                       <span class='text-gray-600'>Barcha yo'nalishlar bo'yicha N yig'indisi:</span>
                       <span class='bg-yellow-200 px-3 py-1 rounded font-medium'>" . round($totalN, 2) . "</span>
                   </div>
               </div>

               <div class='mt-4 text-lg font-semibold text-center bg-gray-800 text-white py-2 rounded'>
                   FAKULTET REYTINGI: " . round($totalN, 2) . "
               </div>
           </div>";
    }
}
