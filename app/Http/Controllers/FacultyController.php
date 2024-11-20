<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Http;
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

            // Fakultet reytingini hisoblash - umumiy ball / o'qituvchilar soni
            $facultyRating = $facultyTotalTeachers > 0
                ? round($calculationResult['total_points'] / $facultyTotalTeachers, 2)
                : 0;

            return (object)[
                'id' => $faculty->id,
                'name' => $faculty->name,
                'slug' => $faculty->slug,
                'status' => $faculty->status,
                'departments' => $faculty->departments,
                'total_points' => $facultyRating, // o'zgartirildi
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

        // Fakultet uchun PointCalculationService dan foydalanish
        $calculationResult = $this->pointCalculationService->calculateFacultyPoints($faculty);

        // Fakultet o'qituvchilari sonini hisoblash
        $totalTeachers = $faculty->departments
            ->where('status', 1)
            ->sum(function ($department) {
                return DB::table('users')
                    ->where('department_id', $department->id)
                    ->where('status', 1)
                    ->count();
            });

        // Fakultet reytingini hisoblash - umumiy ball / o'qituvchilar soni
        $totalPoints = $totalTeachers > 0
            ? round($calculationResult['total_points'] / $totalTeachers, 2)
            : 0;

        $departmentsData = $calculationResult['departments_data'];

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
            ->map(function ($department) use ($departmentsData) {
                $departmentInfo = collect($departmentsData)->firstWhere('department_name', $department->name);

                return [
                    'department' => $department,
                    'points' => [
                        'total_points' => $departmentInfo['total_n'] ?? 0,
                        'teacher_count' => $departmentInfo['teacher_count'] ?? 0,
                        'extra_points' => $departmentInfo['extra_points'] ?? 0,
                        'approved_count' => $department->point_user_deportaments()
                        ->whereHas('employee', function($q) {
                            $q->where('status', 1);
                        })
                        ->where('status', 1)
                        ->count()
                    ]
                ];
            });

        // Bar chart uchun ma'lumotlarni tayyorlash
        $barChartData = $departments->map(function ($item) {
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
            'departEmployee'
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
}
