<?php

namespace App\Http\Controllers;

use DateTime;

use App\Models\DepartPoints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;


class PointUserDeportamentController extends Controller
{




    public function list(Request $request)
    {
        // "Kategoriya" maydoni to'ldirilganligini tekshiring va tegishli "holat" ni o'rnatish
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
            'yonalish'   => $request->get('yonalish'),
            'name'       => $request->get('name'),
            'sort'       => $request->get('sort'),
            'start_data' => $request->get('start_data'),
            'end_data'   => $request->get('end_data'),
        ];

        // Sana formatlash bilan shug'ullaning, to'g'ri kiritilishini ta'minlash va mumkin bo'lgan null qiymat muammolaridan qochish
        $start_date = $request->filled('start_data')
            ? DateTime::createFromFormat('m/d/Y', $request->input('start_data'))
            : null;
        $end_date = $request->filled('end_data')
            ? DateTime::createFromFormat('m/d/Y', $request->input('end_data'))
            : null;

        // Filtrlarni faqat kerakli maydonlar to'ldirilgan bo'lsa qo'llang
        $filter = PointUserDeportament::whereNotNull('status')->get();

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

        // Select uchun variantlarni tayyorlash
        $selectOptions = [];
        foreach ($jadvallarCodlari as $key => $value) {
            $value = str_replace("Chirchiq davlat pedagogika universitetida", "CHDPUda", $value);
            $truncatedValue = mb_strlen($value) > 70 ? mb_substr($value, 0, 67) . '...' : $value;
            $selectOptions[$key] = "$key - $truncatedValue";
        }

        // Key bo'yicha asc tartibda saralash
        ksort($selectOptions);

        // Yo'nalish bo'yicha filtrlash
        $query = PointUserDeportament::query();

        // Yo'nalish bo'yicha filtrlash
        if ($request->filled('yonalish') && $request->yonalish !== 'all') {
            $selectedYonalish = $request->yonalish;
            $query->where(function ($q) use ($selectedYonalish, $arrayKey) {
                foreach ($arrayKey as $column => $key) {
                    if ($key === $selectedYonalish) {
                        $q->orWhereNotNull($column);
                    }
                }
            });
        }

        // Boshqa filtrlar
        $query->when($request->filled('category') && $request->category !== 'all', function ($q) use ($request) {
            $q->where('status', $request->status);
        })
            ->when($request->filled('name'), function ($q) use ($request) {
                $searchTerms = explode(' ', $request->name);
                $q->where(function ($subQ) use ($searchTerms) {
                    $subQ->whereHas('employee', function ($employeeQ) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $employeeQ->where(function ($termQ) use ($term) {
                                $termQ->where('first_name', 'like', '%' . $term . '%')
                                    ->orWhere('second_name', 'like', '%' . $term . '%')
                                    ->orWhere('third_name', 'like', '%' . $term . '%');
                            });
                        }
                    });
                });
            })
            ->when($request->filled('sort'), function ($q) use ($request) {
                $q->orderBy('created_at', $request->sort);
            })
            ->when($start_date && $end_date, function ($q) use ($start_date, $end_date) {
                $q->whereBetween('created_at', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')]);
            });

        $murojatlar = $query->orderBy('created_at', 'desc')->paginate(15)->appends($form_info);

        // Ma'lumotlar massivini tekshirish
        foreach ($murojatlar as $item) {
            foreach ($arrayKey as $column => $originalKey) {
                if (isset($item->$column)) {
                    $item->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $item->murojaat_codi = $originalKey;
                    break;
                }
            }
        }



        // Natijani ko'rsatish uchun ko'rinishni qaytarish
        return view('dashboard.incoming_requests', compact('murojatlar', 'filter', 'form_info', 'selectOptions'));
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
            'total_points' => 0,
            'user_point_this_item' => 0,
            'user_points_this_depart_relation' => 0,
            'user_points_all_departs_items' => 0
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
                            if (strpos($column, $userPointInfo['table_name'] . 'id') !== false) {
                                $q->orWhereNotNull($column);
                            }
                        }
                    });
                })
                ->sum('point');
        }

        // Foydalanuvchining aynan shu item uchun kafedraga o'tgan balini hisoblash
        $userPointInfo['user_point_this_item'] = DepartPoints::where('point_user_deport_id', $id)->sum('point');

        // Foydalanuvchining aynan shu relationdagi kafedraga o'tgan ballarini hisoblash
        $userPointInfo['user_points_this_depart_relation'] = DepartPoints::whereHas('pointUserDeportament', function ($query) use ($information, $userPointInfo) {
            $query->where('user_id', $information->user_id)
                ->where($userPointInfo['table_name'] . 'id', $information->{$userPointInfo['table_name'] . 'id'});
        })->sum('point');

        // Foydalanuvchining barcha yo'nalishlar bo'yicha departamentga o'tgan pointlarini hisoblash
        $userPointInfo['user_points_all_departs_items'] = DepartPoints::whereHas('pointUserDeportament', function ($query) use ($information) {
            $query->where('user_id', $information->user_id);
        })->sum('point');

        // $item->year ni ko'rinishga uzatamiz
        $year = $information->year;

        // O'xshash ma'lumotlarni tekshirish
        $query = PointUserDeportament::where('user_id', $information->user_id)
            ->where('id', '!=', $id);

        $userData = $query->get();

        // O'xshash ma'lumotlarni aniqlash
        $similarData = $userData->filter(function ($item) use ($information) {
            // Yil bir xil va table_1_1_id mavjud bo'lsa
            return $item->year == $information->year && !is_null($item->table_1_1_id);
        });

        $hasSimilarData = $similarData->isNotEmpty();
        $similarDataId = $hasSimilarData ? $similarData->first()->id : null;

        return view('dashboard.show_request', compact('information', 'default_image', 'totalPoints', 'relatedData', 'year', 'userPointInfo', 'hasSimilarData', 'similarDataId'));
    }


    private function getModelClassForRelation($relation)
    {
        // Tegishli model uchun to'liq class nomini tuzish
        return "\\App\\Models\\Tables\\" . ucfirst($relation) . "_";
    }

    public function murojatniTasdiqlash(Request $request)
    {
        // Modelni topish, agar topilmasa 404 xatosi qaytariladi
        $model = PointUserDeportament::findOrFail($request->id);

        // Validatsiya qoidalari
        $validator = Validator::make($request->all(), [
            'murojaat_holati' => 'required|numeric|max:3',
            'murojaat_bali' => 'nullable|numeric|min:0|max:9999.99',
            'kafedra_uchun' => 'nullable|numeric|min:0|max:9999.99',
            'murojaat_izohi' => 'nullable|string'
        ], [
            'murojaat_holati.required' => 'Ma\'lumot holatini kiritish majburiy.',
            'murojaat_holati.numeric' => 'Ma\'lumot holati raqam ko\'rinishida bo\'lishi kerak.',
            'murojaat_holati.max' => 'Ma\'lumot holati eng ko\'pi bilan 3 bo\'lishi kerak.',
            'murojaat_bali.numeric' => 'Ma\'lumot bali raqam bo\'lishi kerak.',
            'kafedra_uchun.numeric' => 'Kafedra uchun o\'tayotgan ball raqam bo\'lishi kerak.',
            'murojaat_izohi.string' => 'Ma\'lumot izohi matn ko\'rinishida bo\'lishi kerak.'
        ]);

        // Agar murojaat_holati "maqullandi" (1) ga teng bo'lsa, murojaat_bali majburiy bo'ladi
        $validator->sometimes('murojaat_bali', 'required|numeric', function ($input) {
            return $input->murojaat_holati == '1';
        }, [
            'murojaat_bali.required' => 'Ma\'lumot holati "maqullandi" bo\'lganida, Ma\'lumot bali kiritish majburiy.'
        ]);

        // Validatsiya xatolarini tekshirish
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Formadan kelgan ma'lumotlarni olish va float tipiga o'tkazish
        $inputPoint = floatval($request->input('murojaat_bali'));
        $inputForDepart = floatval($request->input('kafedra_uchun'));

        try {
            // Tranzaksiya boshlash
            DB::beginTransaction();

            // Ma'lumotlarni yangilash
            $model->status = $request->murojaat_holati;
            $model->arizaga_javob = $request->murojaat_izohi;

            // Murojaat holati va balini yangilash
            if ($request->murojaat_holati == '1') { // "Maqullandi" holati
                $model->point = $inputPoint;

                // Kafedra balini tekshirish
                if ($request->has('kafedra_uchun')) {
                    if ($inputForDepart > 0) {
                        // Agar ball 0 dan katta bo'lsa yangilaymiz yoki yaratamiz
                        DepartPoints::updateOrCreate(
                            ['point_user_deport_id' => $model->id],
                            [
                                'point' => $inputForDepart,
                                'status' => 1
                            ]
                        );
                    } else {
                        // Agar ball 0 yoki undan kichik bo'lsa o'chiramiz
                        DepartPoints::where('point_user_deport_id', $model->id)->delete();
                    }
                }
            } else { // Boshqa holatlar uchun
                $model->point = 0.00;
                // Agar murojaat rad etilgan yoki qayta ko'rib chiqilishi kerak bo'lsa, kafedra balini o'chirish
                if ($request->murojaat_holati == '0' || $request->murojaat_holati == '3') {
                    DepartPoints::where('point_user_deport_id', $model->id)->delete();
                }
            }

            // O'zgarishlarni saqlash
            $model->save();

            // Tranzaksiyani tasdiqlash
            DB::commit();

            // Muvaffaqiyatli bajarilganligi haqida xabar
            return redirect()->back()->with('success', 'Ma\'lumot muvaffaqiyatli saqlandi');
        } catch (\Exception $e) {
            // Xatolik yuz berganda tranzaksiyani bekor qilish
            DB::rollback();

            // Xatolik haqida xabar
            return redirect()->back()->with('error', 'Ma\'lumotni saqlashda xatolik yuz berdi: ' . $e->getMessage());
        }
    }


    public function destroy($fileId)
    {
        $file = PointUserDeportament::where('id', $fileId)->firstOrFail();
        $file->delete();

        return redirect()->route('murojatlar.list')->with('toaster', ['success', "Ma'lumot o'chirildi!"]);
    }
}
