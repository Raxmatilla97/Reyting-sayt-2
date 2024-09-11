<?php

namespace App\Http\Controllers;

use DateTime;

use App\Models\departPoints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\PointCalculationController;

class PointUserDeportamentController extends Controller
{

    protected $pointCalculationController;

    public function __construct(PointCalculationController $pointCalculationController)
    {
        $this->pointCalculationController = $pointCalculationController;
    }


    public function list(Request $request)
    {
        // Check if the 'category' field is filled and set the appropriate 'status'
        if ($request->filled('category')) {
            switch ($request->category) {
                case 'all':
                    $request->merge(['status' => 'all']);
                    break;
                case 'must_be_confirmed':
                    $request->merge(['status' => '3']);
                    break;
                case 'approved':
                    $request->merge(['status' => '1']);
                    break;
                case 'rejected':
                    $request->merge(['status' => '0']);
                    break;
            }
        }

        $form_info = [
            'category'   => $request->get('category'),
            'name'       => $request->get('name'),
            'sort'       => $request->get('sort'),
            'start_data' => $request->get('start_data'),
            'end_data'   => $request->get('end_data'),
        ];

        // Handle date formatting, ensuring correct input and avoiding potential null value issues
        $start_date = $request->filled('start_data')
            ? DateTime::createFromFormat('m/d/Y', $request->input('start_data'))
            : null;
        $end_date = $request->filled('end_data')
            ? DateTime::createFromFormat('m/d/Y', $request->input('end_data'))
            : null;

        // Apply filters only if necessary fields are filled
        $filter = PointUserDeportament::whereNotNull('status')->get();

        if ($request->filled('name') || $request->filled('category') || $request->filled('sort') || ($request->filled('start_data') && $request->filled('end_data'))) {
            $murojatlar = PointUserDeportament::when($request->filled('category') && $request->status !== 'all', function (Builder $query) use ($request) {
                $query->where('status', $request->status);
            })
                ->when($request->filled('name'), function (Builder $query) use ($request) {
                    $name = '%' . $request->name . '%'; // Prepare the search term for LIKE queries
                    $query->where(function (Builder $q) use ($name) {
                        $q->whereHas('employee', function (Builder $q) use ($name) {
                            $q->where('first_name', 'like', $name)
                                ->orWhere('second_name', 'like', $name)
                                ->orWhere('third_name', 'like', $name);
                        });
                    });
                })
                ->when($request->filled('sort'), function (Builder $query) use ($request) {
                    $query->orderBy('created_at', $request->sort);
                })
                ->when($start_date && $end_date, function (Builder $query) use ($start_date, $end_date) {
                    $query->whereBetween('created_at', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')]);
                })
                ->paginate(15);
        } else {
            // If no filters are applied, return all records ordered by creation date
            $murojatlar = PointUserDeportament::orderBy('created_at', 'desc')->paginate(15);
        }

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
        foreach ($murojatlar as $item) {
            foreach ($arrayKey as $column => $originalKey) {
                // column tekshiriladi
                if (isset($item->$column)) {
                    // $murojaat_nomi o'rnatiladi
                    $item->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $item->murojaat_codi = $originalKey;
                    break;
                }
            }
        }

        // Natijani ko'rsatish uchun ko'rinishni qaytarish
        return view('dashboard.incoming_requests', compact('murojatlar', 'filter', 'form_info'));
    }


    public function show($id)
    {
        // Yuborilgan faylni qidirish
        $information = PointUserDeportament::findOrFail($id);

        if (!$information) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        // Kafedra hisobiga o'tgan balni olish
        $departmentPoint = $this->pointCalculationController->getDepartmentPoint($id);

        // O'qituvchi bali va kafedra bali yig'indisi
        $totalPoint = $information->point + $departmentPoint;

        // Default surat
        $default_image = 'https://cspu.uz/storage/app/media/2023/avgust/i.webp';

        // Related ma'lumotlarni va user point info'ni olish
        [$relatedData, $userPointInfo, $foundRelation] = $this->pointCalculationController->getRelatedData($information);

        if (!$foundRelation) {
            return response()->json(['error' => 'No relationships defined'], 500);
        }

        // Foydalanuvchining barcha ballarini hisoblash
        $pointsData = $this->pointCalculationController->calculateTotalPoints($information->user_id);

        // Barcha ballarni qo'shganda + Departament ballariniham!
        $totalPoints = $pointsData['totalPoints'];

        // Departamentga o'tmagan sof ballari
        $totalPointsWithDeportament = $pointsData['totalPointsWithoutDepartment'];

        // Departamentga o'tgan ballar (Aynan shu yuborilgan ma'lumot bo'yicha!) Hamma ballar emas!
        $totalDepartmentPoints = $pointsData['totalDepartmentPoints'];

        // Umumiy kafedraga o'tgan ballarni hisoblash
        // $totalDepartmentPoints = '0';

        // Foydalanuvchining faqat shu table uchun ballarini hisoblash
        if ($foundRelation && $userPointInfo['table_name']) {
            $tablePointInfo = $this->pointCalculationController->calculateUserPointInfo($information->user_id, $userPointInfo['table_name']);
            $userPointInfo['total_points'] = $tablePointInfo['total_points'];
            // dd( $userPointInfo['total_points']);
        }

        // $item->year ni ko'rinishga uzatamiz
        $year = $information->year;

        return view('dashboard.show_request', compact(
            'information',
            'default_image',
            'totalPoints',
            'relatedData',
            'year',
            'userPointInfo',
            'departmentPoint',
            'totalPoint',
            'totalPointsWithDeportament',
            'totalDepartmentPoints'
        ));
    }



    public function murojatniTasdiqlash(Request $request)
    {
        $model = PointUserDeportament::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'murojaat_holati' => 'required|integer|between:0,3',
            'murojaat_bali' => 'nullable|numeric|min:0|max:9999.99',
            'murojaat_izohi' => 'nullable|string|max:1000',

            'max_point' => 'required|numeric|min:0|max:9999.99'
        ], [
            'murojaat_holati.required' => 'Ma\'lumot holatini kiritish majburiy.',
            'murojaat_holati.integer' => 'Ma\'lumot holati butun son bo\'lishi kerak.',
            'murojaat_holati.between' => 'Ma\'lumot holati 0 dan 3 gacha bo\'lishi kerak.',
            'murojaat_bali.numeric' => 'Ma\'lumot bali son bo\'lishi kerak.',
            'murojaat_bali.min' => 'Ma\'lumot bali 0 dan kichik bo\'lmasligi kerak.',
            'murojaat_bali.max' => 'Ma\'lumot bali 9999.99 dan oshmasligi kerak.',
            'murojaat_izohi.string' => 'Ma\'lumot izohi matn ko\'rinishida bo\'lishi kerak.',
            'murojaat_izohi.max' => 'Ma\'lumot izohi 1000 belgidan oshmasligi kerak.',
            'max_point.required' => 'Maksimal ball kiritish majburiy.',
            'max_point.numeric' => 'Maksimal ball son bo\'lishi kerak.',
            'max_point.min' => 'Maksimal ball 0 dan kichik bo\'lmasligi kerak.',
            'max_point.max' => 'Maksimal ball 9999.99 dan oshmasligi kerak.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $inputPoint = floatval($request->input('murojaat_bali', 0));
        $maxPoint = floatval($request->input('max_point', 0));

        // Foydalanuvchining barcha itemlari bo'yicha umumiy ballarni hisoblash
        $totalUserPoints = PointUserDeportament::where('user_id', $model->user_id)
            ->where('id', '!=', $model->id) // Joriy itemni hisobga olmaslik
            ->sum('point');

        $totalDepartmentPoints = DepartPoints::whereIn('point_user_deport_id', function($query) use ($model) {
            $query->select('id')
                ->from('point_user_deportaments')
                ->where('user_id', $model->user_id)
                ->where('id', '!=', $model->id); // Joriy itemni hisobga olmaslik
        })->sum('point');

        $totalPoints = $totalUserPoints + $totalDepartmentPoints;

        $model->status = $request->murojaat_holati;
        $model->arizaga_javob = $request->murojaat_izohi;

        if ($request->murojaat_holati == 1) { // Maqullandi
            $availablePoints = max(0, $maxPoint - $totalPoints);
            $teacherPoint = min($inputPoint, $availablePoints);
            $departmentPoint = max(0, $inputPoint - $teacherPoint);

            $model->point = $teacherPoint;

            if ($departmentPoint > 0) {
                DepartPoints::updateOrCreate(
                    ['point_user_deport_id' => $model->id],
                    [
                        'point' => $departmentPoint,
                        'status' => true
                    ]
                );
            } else {
                DepartPoints::where('point_user_deport_id', $model->id)->delete();
            }
        } elseif (in_array($request->murojaat_holati, [0, 3])) { // Rad etildi yoki Bekor qilindi
            $model->point = 0.00;
            DepartPoints::where('point_user_deport_id', $model->id)->delete();
        } else { // Boshqa holatlar
            $model->point = $inputPoint;
            DepartPoints::where('point_user_deport_id', $model->id)->delete();
        }

        $model->save();

        return redirect()->back()->with('success', 'Ma\'lumot muvaffaqiyatli saqlandi');
    }

    public function destroy($fileId)
    {
        $file = PointUserDeportament::where('id', $fileId)->firstOrFail();
        $file->delete();

        return redirect()->route('murojatlar.list')->with('toaster', ['success', "Ma'lumot o'chirildi!"]);
    }
}
