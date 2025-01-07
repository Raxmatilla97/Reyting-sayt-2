<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PointUserDeportament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\DepartPoints;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Employee modelidan queryni boshlash
        $query = Employee::query();

        // Agar 'name' parametri mavjud bo'lsa
        if ($request->filled('name')) {
            $searchTerms = explode(' ', trim($request->name));

            $query->where(function ($mainQuery) use ($searchTerms) {
                // Har bir so'z uchun
                foreach ($searchTerms as $term) {
                    $term = '%' . $term . '%';

                    $mainQuery->where(function ($q) use ($term) {
                        $q->where('first_name', 'like', $term)
                            ->orWhere('second_name', 'like', $term)
                            ->orWhere('third_name', 'like', $term);
                    });
                }
            });

            // To'liq ism bilan qidirish
            $fullName = '%' . $request->name . '%';
            $query->orWhere(DB::raw("CONCAT(first_name, ' ', second_name, ' ', third_name)"), 'like', $fullName)
                ->orWhere(DB::raw("CONCAT(second_name, ' ', first_name, ' ', third_name)"), 'like', $fullName);
        }

        // Tartiblash va paginatsiya qilish
        $employee = $query->orderBy('created_at', 'desc')->paginate(30);

        return view('dashboard.employee.index', compact('employee'));
    }

    public function employeeFormChose()
    {


        $jadvallar_codlari = Config::get('dep_emp_tables.employee');;

        return view('dashboard.employee_category_choose', compact('jadvallar_codlari'));
    }

    public function export(Request $request)
    {
        // Get all employees with their departments
        $employees = Employee::with('department')->get();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', '№');
        $sheet->setCellValue('B1', 'F.I.O');
        $sheet->setCellValue('C1', 'Kafedra');
        $sheet->setCellValue('D1', 'Holat');
        $sheet->setCellValue('E1', 'Ball');

        // Add data
        $row = 2;
        foreach ($employees as $index => $employee) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, ucwords(strtolower($employee->FullName ?? 'Kuzatuvchi')));
            $sheet->setCellValue('C' . $row, $employee->department ? $employee->department->name : 'N/A');
            $sheet->setCellValue('D' . $row, $employee->status ? 'Aktiv' : 'Aktiv emas');
            $sheet->setCellValue('E' . $row, round($employee->department ?
                $employee->department->point_user_deportaments()
                ->where('status', 1)
                ->where('user_id', $employee->id)
                ->sum('point') : 0, 2));
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create writer and prepare response
        $writer = new Xlsx($spreadsheet);
        $fileName = 'oqituvchilar_royxati_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
        exit;
    }


    public function employeeShow($employee_id)
    {
        try {
            // Xodimni topish
            $employee = Employee::where('employee_id_number', $employee_id)->firstOrFail();

            // Xodimga tegishli point ma'lumotlarini olish
            $pointUserInformations = PointUserDeportament::where('user_id', $employee->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            // Department va Employee config konfiguratsiyalarini olish
            $departmentCodlari = Config::get('dep_emp_tables.department');
            $employeeCodlari = Config::get('dep_emp_tables.employee');
            $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

            // Label nomlarini config fayllaridan olish
            $employeeLabels = Config::get('employee_form_fields');
            $departmentLabels = Config::get('department_forms_fields');
            $allLabels = array_merge($employeeLabels, $departmentLabels);

            // Key nomli maydonlarni yaratish
            $arrayKey = [];
            foreach ($jadvallarCodlari as $key => $value) {
                $arrayKey[$key . 'id'] = $key;
            }

            // Murojaat nomlarini qo'shish
            foreach ($pointUserInformations as $item) {
                foreach ($arrayKey as $column => $originalKey) {
                    if (isset($item->$column)) {
                        $labelKey = $originalKey . '_';
                        $label = isset($allLabels[$labelKey]) ? $allLabels[$labelKey]['label'] : $jadvallarCodlari[$originalKey];
                        $item->murojaat_nomi = $label;
                        $item->murojaat_codi = $originalKey;
                        break;
                    }
                }
            }

            // Umumiy ballarni hisoblash
            $totalPoints = PointUserDeportament::where('user_id', $employee->id)
                ->where('status', 1)
                ->sum('point');

            $totalInfos = PointUserDeportament::where('user_id', $employee->id)
                ->where('status', 1)
                ->count();

            // Departamentga o'tgan ballar yig'indisi
            $departamentPointTotal = DepartPoints::whereIn('point_user_deport_id', function ($query) use ($employee) {
                $query->select('id')
                    ->from('point_user_deportaments')
                    ->where('user_id', $employee->id)
                    ->where('status', 1);
            })->sum('point');

            // Statistika uchun ma'lumotlarni olish
            $endDate = now();
            $startDate = now()->subDays(80);

            // Barcha ma'lumotlarni olish
            $statistics = PointUserDeportament::where('user_id', $employee->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();

            // Ma'lumotlarni kunlar bo'yicha guruhlash
            $dailyStats = $statistics->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })
                ->map(function ($group) {
                    return [
                        'total' => $group->count(),
                        'accepted' => $group->where('status', 1)->count(),
                        'rejected' => $group->where('status', 0)->count()
                    ];
                });

            // Kunlarni tartiblab olish
            $dailyStats = $dailyStats->sortKeys();
            return view('dashboard.employee.show', compact(
                'employee',
                'pointUserInformations',
                'totalPoints',
                'departamentPointTotal',
                'totalInfos',
                'dailyStats'
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', "ID: $employee_id bo'lgan xodim topilmadi.");
        } catch (\Exception $e) {
            \Log::error('Xodim ma\'lumotlarini olishda xatolik: ' . $e->getMessage());
            return back()->with('error', 'Ma\'lumotlarni olishda xatolik yuz berdi.');
        }
    }

    public function mySubmittedInformation()
    {
        $user = auth()->user();
        $pointUserInformations = PointUserDeportament::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

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

        // Umumiy ballar yig'indisini hisoblash (pagination dan tashqari)
        $totalPointsSuccess = PointUserDeportament::where('user_id', $user->id)
            ->where('status', 1);

        $totalPoints = $totalPointsSuccess->sum('point') ? $totalPointsSuccess->sum('point') : 0;
        $totalInfos = $totalPointsSuccess->count() ? $totalPointsSuccess->count() : 0;

        // DepartPoints ballarini hisoblash (pagination dan tashqari)
        $departamentPointTotal = DepartPoints::whereIn('point_user_deport_id', function ($query) use ($user) {
            $query->select('id')
                ->from('point_user_deportaments')
                ->where('user_id', $user->id);
        })->sum('point');

        // Kunlik statistika uchun so'nggi 80 kunlik ma'lumotlarni olish
        $dailyStats = DB::table('point_user_deportaments')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as accepted'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as rejected')
            )
            ->where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(365))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => [
                    'total' => (int)$item->total,
                    'accepted' => (int)$item->accepted,
                    'rejected' => (int)$item->rejected
                ]];
            });

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

        return view('dashboard.my_submited_info', compact('pointUserInformations', 'totalPoints', 'departamentPointTotal', 'totalInfos', 'dailyStats'));
    }

    public function list(Request $request)
    {
        // Employee modelidan queryni boshlash
        $query = Employee::query();

        // Agar 'name' parametri mavjud bo'lsa, nomga mos keladigan xodimlarni qidirish
        if ($request->filled('name')) {
            $name = '%' . $request->name . '%'; // LIKE qidiruvlari uchun qidiruv terminini tayyorlash
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', $name)
                    ->orWhere('second_name', 'like', $name)
                    ->orWhere('third_name', 'like', $name);
            });
        }

        // Tartiblash va paginatsiya qilish
        $employees = $query->orderBy('created_at', 'desc')->paginate(30);

        // Natijani ko'rsatish uchun ko'rinishni qaytarish
        return view('dashboard.employee.index', compact('employees'));
    }
}
