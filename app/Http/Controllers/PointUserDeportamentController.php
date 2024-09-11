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

        // Default surat buni o'zgartirsa bo'ladi
        $default_image = 'https://cspu.uz/storage/app/media/2023/avgust/i.webp';

        if (!$information) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $relatedData = [];
        $userPointInfo = [
            'table_name' => '',
            'max_point' => 0,
            'total_points' => 0
        ];
        $relationships = $information->getRelationships();

        // Config faylidan ma'lumotlarni olish
        $maxPointsConfig = config('max_points_dep_emp');

        $foundRelation = false;
        if (is_array($relationships)) {
            foreach ($relationships as $relationship) {
                $foreignKey = $relationship . '_id';
                if (isset($information->{$foreignKey}) && !is_null($information->{$foreignKey})) {
                    $relatedModelClass = $this->getModelClassForRelation($relationship);
                    $relatedData[$relationship] = $relatedModelClass::find($information->{$foreignKey});

                    $tableName = $relatedData[$relationship]->getTable();
                    $userPointInfo['table_name'] = $tableName;

                    foreach (['department', 'employee'] as $category) {
                        if (isset($maxPointsConfig[$category][$tableName])) {
                            $userPointInfo['max_point'] = $maxPointsConfig[$category][$tableName]['max'];
                            $foundRelation = true;
                            break 2;
                        }
                    }
                } else {
                    $relatedData[$relationship] = null;
                }
            }
        } else {
            return response()->json(['error' => 'No relationships defined'], 500);
        }

        // Foydalanuvchining barcha pointlarini hisoblash
        $totalPoints = PointUserDeportament::where('user_id', $information->user_id)
            ->where('status', 1)
            ->sum('point');

        // Foydalanuvchining faqat shu table uchun pointlarini hisoblash
        if ($foundRelation && $userPointInfo['table_name']) {
            $userPointInfo['total_points'] = PointUserDeportament::where('user_id', $information->user_id)
                ->where('status', 1)
                ->where(function ($query) use ($userPointInfo) {
                    $query->where(function ($q) use ($userPointInfo) {
                        $columns = Schema::getColumnListing('point_user_deportaments');
                        foreach ($columns as $column) {
                            if (strpos($column, $userPointInfo['table_name']) !== false) {
                                $q->orWhereNotNull($column);
                            }
                        }
                    });
                })
                ->sum('point');
        }

        // $item->year ni ko'rinishga uzatamiz
        $year = $information->year;

        return view('dashboard.show_request', compact('information', 'default_image', 'totalPoints', 'relatedData', 'year', 'userPointInfo'));
    }


    private function getModelClassForRelation($relation)
    {
        // Tegishli model uchun to'liq class nomini tuzish
        return "\\App\\Models\\Tables\\" . ucfirst($relation) . "_";
    }

    public function murojatniTasdiqlash(Request $request)
    {

        $model = PointUserDeportament::findOrFail($request->id); // Modelni topish

        // Validatsiya qoidalari
        $validator = Validator::make($request->all(), [
            'murojaat_holati' => 'required|numeric|max:3',
            'murojaat_bali' => 'nullable|numeric',
            'murojaat_izohi' => 'nullable|string'
        ], [
            'murojaat_holati.required' => 'Ma\'lumot holatini kiritish majburiy.',
            'murojaat_holati.string' => 'Ma\'lumot holati matn ko\'rinishida bo\'lishi kerak.',
            'murojaat_holati.max' => 'Ma\'lumot holati eng ko\'pi bilan 3 belgidan iborat bo\'lishi kerak.',
            'murojaat_bali.numeric' => 'Ma\'lumot bali raqam bo\'lishi kerak.',
            'murojaat_izohi.string' => 'Ma\'lumot izohi matn ko\'rinishida bo\'lishi kerak.'
        ]);

        // Agar murojaat_holati "maqullandi" ga teng bo'lsa, murojaat_bali majburiy bo'ladi
        $validator->sometimes('murojaat_bali', 'required|numeric', function ($input) {
            return $input->murojaat_holati == '1';
        }, [
            'murojaat_bali.required' => 'Ma\'lumot holati "maqullandi" bo\'lganida, Ma\'lumot bali kiritish majburiy.'
        ]);

        // // Formadan kelgan ma'lumotlarni olish
        $inputPoint = floatval($request->input('murojaat_bali'));
        $extraPoints = floatval($request->input('extra_point'));


        // Yangi soddalashtirilgan logika
        if ($request->murojaat_holati == '1') { // Faqat "maqullandi" holatida
            $teacherPoints = $inputPoint - $extraPoints;
            // O'qituvchi balini yangilash
            $model->point = $teacherPoints; // Manfiy qiymatni oldini olish

            // Kafedra balini yangilash
            if ($extraPoints > 0) {
                 $model->department_points = ($model->department_points ?? 0) + $extraPoints;

            }
        } else {
            // Eski logika
            if ($request->murojaat_holati == 0 || $request->murojaat_holati == 3) {
                $model->point = 0.00;

            } else {
                $model->point = $request->murojaat_bali;
            }
        }

        // Eski kod
        // Ma'lumotlarni yangilash
        $model->status = $request->murojaat_holati;
        if ($request->murojaat_holati == 0 || $request->murojaat_holati == 3) {
            $model->point = 0.00;
        } else {
            $model->point = $request->murojaat_bali;
        }

        $model->arizaga_javob = $request->murojaat_izohi;
        $model->save();

        // Bajarilganidan so'ng, foydalanuvchini kerakli sahifaga yo'naltiring
        return redirect()->back()->with('success', 'Ma\'lumot muvaffaqiyatli saqlandi');
    }


    public function destroy($fileId)
    {
        $file = PointUserDeportament::where('id', $fileId)->firstOrFail();
        $file->delete();

        return redirect()->route('murojatlar.list')->with('toaster', ['success', "Ma'lumot o'chirildi!"]);
    }
}
