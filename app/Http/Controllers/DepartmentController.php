<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\PointUserDeportament;
use App\Models\StudentsCountForDepart;
use Illuminate\Support\Facades\Config;
use App\Services\PointCalculationService;
use Illuminate\Database\Eloquent\Builder;

class DepartmentController extends Controller
{

    protected $pointCalculationService;

    public function __construct(PointCalculationService $pointCalculationService)
    {
        $this->pointCalculationService = $pointCalculationService;
    }


    /**
     * Display a listing of the resource.
     */


     public function index(Request $request)
     {
         // Departamentlar uchun so'rov yaratish
         $departments = Department::with(['point_user_deportaments' => function ($query) {
             $query->where('status', 1)->with('departPoint');
         }])->where('status', 1);

         // Agar ism berilgan bo'lsa, qidirish
         if ($request->filled('name')) {
             $name = '%' . $request->name . '%';
             $departments->where('name', 'like', $name);
         }

         // Paginatsiyani qo'llaymiz
         $departments = $departments->orderBy('created_at', 'desc')->paginate(21);

         // Har bir departament uchun reytingni hisoblash
         foreach ($departments as $department) {
             $calculationResult = $this->pointCalculationService->calculateDepartmentPoints($department);

             // Departament obyektiga yangi ma'lumotlarni qo'shish
             $department->totalPoints = $calculationResult['total_n']; // N yig'indisi (reyting balli)
             $department->teacherCount = $calculationResult['teacher_count'];
             $department->studentCount = $calculationResult['student_count'];
             $department->extraPoints = $calculationResult['extra_points'];
             $department->teacherTotalPoints = $calculationResult['teacher_total_points'];
             $department->totalWithExtra = $calculationResult['total_with_extra'];
         }

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

        $calculationResult = $this->pointCalculationService->calculateDepartmentPoints($department);

        $timeAgo = $this->getTimeAgo($department);
        $fullName = $this->getLastUserFullName($department);

        return view('dashboard.department.show', [
            'department' => $department,
            'pointUserInformations' => $pointUserInformations,
            'timeAgo' => $timeAgo,
            'fullName' => $fullName,
            'totalEmployees' => $calculationResult['total_employees'],  // shu yerdan olamiz
            'totalPoints' => $calculationResult['total_n'],
            'totalInfos' => $calculationResult['total_infos'],
            'unregisteredEmployees' => $calculationResult['teacher_count'],
            'pointsCalculationExplanation' => $calculationResult['html'],
            'departmentExtraPoints' => $calculationResult['extra_points'],
            'teacherTotalPoints' => $calculationResult['teacher_total_points'],
            'totalWithExtra' => $calculationResult['total_with_extra']
        ]);
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
}
