<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Faculty;
use Illuminate\Support\Str;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
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

            // Object yaratish
            return (object)[
                'id' => $faculty->id,
                'name' => $faculty->name,
                'slug' => $faculty->slug,
                'status' => $faculty->status,
                'departments' => $faculty->departments,
                'total_points' => $calculationResult['total_points']
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

        $pointUserInformations = PointUserDeportament::whereIn('departament_id', $faculty->departments->pluck('id'))
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
                // column tekshiriladi
                if (isset($pointUserInformation->$column)) {
                    // $murojaat_nomi o'rnatiladi
                    $pointUserInformation->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $pointUserInformation->murojaat_codi = $originalKey;
                    break;
                }
            }
        }

        // Fakultet uchun PointCalculationService dan foydalanish
        $calculationResult = $this->pointCalculationService->calculateFacultyPoints($faculty);

        // Service dan qaytgan qiymatlarni ajratib olish
        $totalPoints = $calculationResult['total_points'];
        $totalTeachers = $calculationResult['total_teachers'];
        $departmentsData = $calculationResult['departments_data'];

        // Fakultet umumiy ma'lumotlar soni
        $totalInfos = $faculty->departments->sum(function ($department) {
            return $department->point_user_deportaments->count();
        });

        // Eng so'nggi ma'lumot vaqti va egasini aniqlash
        $latestInfo = PointUserDeportament::whereIn('departament_id', $faculty->departments->pluck('id'))
            ->with('employee')
            ->latest()
            ->first();

        if ($latestInfo) {
            $timeAgo = $latestInfo->created_at->diffForHumans();
            $fullName = $latestInfo->employee->full_name ?? "Ma'lumot topilmadi!";
            $departEmployee = $latestInfo->employee->department->name ?? "Ma'lumot topilmadi!";
        } else {
            $timeAgo = "Ma'lumot yo'q";
            $fullName = "Ma'lumot topilmadi!";
            $departEmployee = "Ma'lumot topilmadi!";
        }

        // Service ma'lumotlarini department ma'lumotlari bilan birlashtirish
        $departments = $faculty->departments->map(function ($department) use ($departmentsData) {
            $departmentInfo = collect($departmentsData)->firstWhere('department_name', $department->name);

            return [
                'department' => $department,
                'points' => [
                    'total_points' => $departmentInfo['total_n'] ?? 0,
                    'teacher_count' => $departmentInfo['teacher_count'] ?? 0,
                    'extra_points' => $departmentInfo['extra_points'] ?? 0,
                ]
            ];
        });

        // Bar chart uchun ma'lumotlarni tayyorlash
        $barChartData = $departments->map(function ($item) {
            return [
                'x' => mb_substr($item['department']->name, 0, 15),  // Qisqa nom
                'full_name' => $item['department']->name,  // To'liq nom
                'y' => round($item['points']['total_points'], 2)
            ];
        })->values()->toArray();
        // Radar chart uchun ma'lumotlarni yig'ish
        $radarChartData = [];
        foreach ($faculty->departments as $department) {
            foreach ($jadvallarCodlari as $key => $value) {
                $count = $department->point_user_deportaments()
                    ->where($key . 'id', '!=', null)
                    ->where('status', 1)
                    ->count();

                // Faqat ma'lumot mavjud bo'lgan yo'nalishlarni qo'shamiz
                if ($count > 0) {
                    if (!isset($radarChartData[$key])) {
                        $radarChartData[$key] = 0;
                    }
                    $radarChartData[$key] += $count;
                }
            }
        }

        // Radar chart uchun ma'lumotlarni formatlash (faqat ma'lumoti bor yo'nalishlar)
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

        // Ma'lumotlarni yig'ish
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

        try {
            $view = view('dashboard.faculty.item-details', $data)->render();

            return response()->json([
                'html' => $view,
                'status' => $item->status,
                'creator_id' => $item->user_id
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
