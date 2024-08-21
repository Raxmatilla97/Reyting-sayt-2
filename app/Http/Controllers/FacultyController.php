<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\PointUserDeportament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculties = Faculty::with('departments.point_user_deportaments')->where('status', 1)->paginate(15);

        foreach ($faculties as $faculty) {
            $faculty->totalPoints = 0;
            foreach ($faculty->departments as $department) {
                $faculty->totalPoints += $department->point_user_deportaments()
                    ->where('status', 1)
                    ->sum('point');
            }
        }
        return view('dashboard.faculty.index', compact('faculties'));
    }

    public function facultyShow($slug)
    {
        $faculty = Faculty::where('slug', $slug)->firstOrFail();

        $pointUserInformations = PointUserDeportament::whereIn('departament_id', $faculty->departments->pluck('id'))->paginate('15');

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

        // Fakultetda nechta o'qituvchi borligi
        $totalEmployees = $faculty->departments->sum(function ($department) {
            return $department->employee->count();
        });

        // Fakultet umumiy ballari soni
        $totalPoints = $faculty->departments->sum(function ($department) {
            return round($department->point_user_deportaments()->where('status', 1)->sum('point'), 2);
        });

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
            'fullName'


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
                // Foreign key maydoni nomini tuzing
                $foreignKey = $relationship . '_id';
                // Foreign key maydoni mavjudligini va null emasligini tekshiring
                if (isset($item->{$foreignKey}) && !is_null($item->{$foreignKey})) {
                    // Tegishli model sinf nomini dinamik ravishda aniqlang
                    $relatedModelClass = $this->getModelClassForRelation($relationship);
                    // Foreign key ID asosida tegishli elementni oling
                    $relatedData[$relationship] = $relatedModelClass::find($item->{$foreignKey});
                } else {
                    $relatedData[$relationship] = null; // Tegishli maʼlumotlar topilmadi
                }
            }
        } else {
            return response()->json(['error' => 'No relationships defined'], 500);
        }

        // $item->year ni ko'rinishga uzatamiz
        $year = $item->year;

        $view = view('dashboard.faculty.item-details', compact('item', 'relatedData', 'year'))->render();

        return response()->json(['html' => $view]);
    }



    private function getModelClassForRelation($relation)
    {
        // Tegishli model uchun to'liq class nomini tuzish
        return "\\App\\Models\\Tables\\" . ucfirst($relation) . "_";
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Faculty $faculty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faculty $faculty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        //
    }
}
