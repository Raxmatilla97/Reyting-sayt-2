<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\PointUserDeportament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $faculties = Faculty::with('departments.point_user_deportaments.departPoint')
        ->where('status', 1)
        ->paginate(15);

    $departmentCounts = config('departament_tichers_count');

    if ($departmentCounts === null) {
        $departmentCounts = include(config_path('departament_tichers_count.php'));
    }

    foreach ($faculties as $faculty) {
        $faculty->totalPoints = 0;
        $faculty->totalTeachers = 0;

        foreach ($faculty->departments as $department) {
            // Har bir departament uchun o'qituvchilar sonini hisoblash
            $teacherCount = $departmentCounts[$faculty->id][$department->id] ?? 0;
            $faculty->totalTeachers += $teacherCount;

            // Departament uchun umumiy ballarni hisoblash
            $departmentPoints = $department->point_user_deportaments
                ->where('status', 1)
                ->reduce(function ($carry, $pointEntry) {
                    return $carry + $pointEntry->point + ($pointEntry->departPoint ? $pointEntry->departPoint->point : 0);
                }, 0);

            // Departament ballarini fakultet umumiy balliga qo'shish
            $faculty->totalPoints += $departmentPoints;
        }

        // Fakultet o'rtacha ballini hisoblash
        $faculty->average_points = $faculty->totalTeachers > 0
            ? round($faculty->totalPoints / $faculty->totalTeachers, 2)
            : 'N/A';
    }

    return view('dashboard.faculty.index', compact('faculties'));
}

    public function facultyShow($slug)
    {
        $faculty = Faculty::where('slug', $slug)->firstOrFail();

        $pointUserInformations = PointUserDeportament::whereIn('departament_id', $faculty->departments->pluck('id'))->orderBy('created_at', 'desc')->paginate('15');

        // Department va Employee konfiguratsiyalarini olish
        $departmentCodlari = Config::get('dep_emp_tables.department');
        $employeeCodlari = Config::get('dep_emp_tables.employee');

        // Ikkala massivni birlashtirish
        $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

        // Har bir massiv elementiga "key" nomli yangi maydonni qo'shish
        $arrayKey = [];
        foreach ($jadvallarCodlari as $key => $value) {
            $arrayKey[$key . 'id'] = $key; // $key . 'id' qiymatini o'rnating
        }

        // Ma'lumotlar massivini tekshirish
        foreach ($pointUserInformations as $faculty_item) {
            foreach ($arrayKey as $column => $originalKey) {
                // column tekshiriladi
                if (isset($faculty_item->$column)) {
                    // $murojaat_nomi o'rnatiladi
                    $faculty_item->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $faculty_item->murojaat_codi = $originalKey;
                    break;
                }
            }
        }

        // Fakultetda nechta o'qituvchi ro'yxatdan o'tganligi
        $totalRegisteredEmployees = $faculty->departments->sum(function ($department) {
            return $department->employee->count();
        });

        // Kafedrada nechta o'qituvchi borligi haqidagi massiv
        $departmentCounts = config('departament_tichers_count');

        if ($departmentCounts === null) {
            $departmentCounts = include(config_path('departament_tichers_count.php'));
        }

        // Fakultet umumiy ballari va o'qituvchilar sonini hisoblash
        $totalPoints = 0;
        $totalTeachers = 0;

        // Fakultet ID siga mos keluvchi kafedralar ro'yxatini olish
        $facultyDepartments = $departmentCounts[$faculty->id] ?? [];

        foreach ($faculty->departments as $department) {
            $departmentPointTotal = 0;
            $teacherCount = $facultyDepartments[$department->id] ?? 0;
            $totalTeachers += $teacherCount;

            // point_user_deportaments jadvalidagi barcha tasdiqlangan (status = 1) balllarni qo'shish
            $departmentPointTotal += $department->point_user_deportaments()
                ->where('status', 1)
                ->sum('point');

            // departPoint jadvalidagi qo'shimcha balllarni qo'shish (agar mavjud bo'lsa)
            $departmentPointTotal += $department->point_user_deportaments()
                ->where('status', 1)
                ->whereHas('departPoint')
                ->with('departPoint')
                ->get()
                ->sum(function ($pointEntry) {
                    return $pointEntry->departPoint->point;
                });

            // Umumiy ballni hisoblash
            $totalPoints += $departmentPointTotal;
        }

        // Agar o'qituvchilar soni mavjud bo'lsa, o'rtacha ballni hisoblash
        if ($totalTeachers > 0) {
            $averagePoints = round($totalPoints / $totalTeachers, 2);
        } else {
            $averagePoints = 'N/A';
        }

        $totalEmployees = $totalTeachers;

        //------------------------------------------------------------------------------

        // Fakultet umumiy ma'lumotlar soni
        $totalInfos = $faculty->departments->sum(function ($department) {
            return $department->point_user_deportaments->count();
        });

        // Fakultet ma'lumoti eng ohirgi kelgan vaqti
        $mostRecentTimestamp = $faculty->departments->flatMap(function ($department) {
            return $department->point_user_deportaments;
        })->max('created_at');

        $mostRecentTime = Carbon::parse($mostRecentTimestamp);
        $now = Carbon::now();
        $diffInSeconds = $now->diffInSeconds($mostRecentTime);
        $diffInMinutes = $now->diffInMinutes($mostRecentTime);
        $diffInHours = $now->diffInHours($mostRecentTime);
        $diffInDays = $now->diffInDays($mostRecentTime);

        // Tarjima holati
        if ($diffInSeconds < 60) {
            $timeAgo = $diffInSeconds . ' soniya oldin';
        } elseif ($diffInMinutes < 60) {
            $timeAgo = $diffInMinutes . ' daqiqa oldin';
        } elseif ($diffInHours < 24) {
            $timeAgo = $diffInHours . ' soat oldin';
        } else {
            $timeAgo = $diffInDays . ' kun oldin';
        }

        // Eng ohirgi yuborgan malumotnign egasi nomi
        $mostRecentEntry = $faculty->departments->flatMap(function ($department) {
            return $department->point_user_deportaments;
        })->sortByDesc('created_at')->first();

        if ($mostRecentEntry) {
            $fullName = $mostRecentEntry->employee->full_name;
        } else {
            $fullName = "Ma'lumot topilmadi!";
        }

        return view('dashboard.faculty.show', compact(
            'faculty',
            'pointUserInformations',
            'totalEmployees',
            'totalPoints',
            'totalInfos',
            'timeAgo',
            'fullName',
            'averagePoints'


        ));
    }

    public function getItemDetails($id)
    {
        $item = PointUserDeportament::find($id);

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

        // $item->arizaga_javob ni $relatedData massiviga qo'shamiz
        $arizaga_javob = $item->arizaga_javob;
        $yaratilgan_sana = $item->created_at;
        $tekshirilgan_sana = $item->updated_at;

        // Qo'yilgan ballni hisoblash
        $qoyilgan_ball = 0;
        if ($item->point !== null && is_numeric($item->point)) {
            $qoyilgan_ball = round($item->point, 2);
        }

        // $item->year ni ko'rinishga uzatamiz
        $year = $item->year;

        $view = view('dashboard.faculty.item-details', compact('item', 'relatedData', 'year', 'arizaga_javob', 'tekshirilgan_sana', 'yaratilgan_sana', 'qoyilgan_ball'))->render();

        return response()->json(['html' => $view]);
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
