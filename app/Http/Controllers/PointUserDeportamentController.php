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
use Illuminate\Support\Facades\Log;


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

        // Table_11_1, table_11_2, table_11_3 uchun o'xshash ma'lumotlarni tekshirish
        $table11SimilarData = [];
        $hasTable11SimilarData = false;
        
        // Joriy ma'lumot qaysi table_11_* ga tegishligi aniqlaymiz
        $currentTable11Type = null;
        if (!is_null($information->table_11_1_id)) {
            $currentTable11Type = 'table_11_1';
        } elseif (!is_null($information->table_11_2_id)) {
            $currentTable11Type = 'table_11_2';
        } elseif (!is_null($information->table_11_3_id)) {
            $currentTable11Type = 'table_11_3';
        }

        if ($currentTable11Type) {
            // Joriy ma'lumotning asosiy maydonlarini olamiz
            $currentRelatedData = null;
            foreach ($relatedData as $table => $data) {
                if ($table === $currentTable11Type && $data) {
                    $currentRelatedData = $data;
                    break;
                }
            }

            if ($currentRelatedData) {
                // Foydalanuvchining barcha table_11_* ma'lumotlarini olamiz
                $table11UserData = PointUserDeportament::where('user_id', $information->user_id)
                    ->where('id', '!=', $id)
                    ->where('year', $information->year)
                    ->where(function ($query) {
                        $query->whereNotNull('table_11_1_id')
                              ->orWhereNotNull('table_11_2_id')
                              ->orWhereNotNull('table_11_3_id');
                    })
                    ->get();

                if ($table11UserData->isNotEmpty()) {
                    foreach ($table11UserData as $userData) {
                        $userTableType = null;
                        $userRelatedData = null;
                        
                        if (!is_null($userData->table_11_1_id)) {
                            $userTableType = 'table_11_1';
                            $userRelatedData = $this->getModelClassForRelation('table_11_1')::find($userData->table_11_1_id);
                        } elseif (!is_null($userData->table_11_2_id)) {
                            $userTableType = 'table_11_2';
                            $userRelatedData = $this->getModelClassForRelation('table_11_2')::find($userData->table_11_2_id);
                        } elseif (!is_null($userData->table_11_3_id)) {
                            $userTableType = 'table_11_3';
                            $userRelatedData = $this->getModelClassForRelation('table_11_3')::find($userData->table_11_3_id);
                        }

                        if ($userTableType && $userTableType !== $currentTable11Type && $userRelatedData) {
                            // Ma'lumotlarni taqqoslash
                            $isSimilar = $this->compareTable11Data($currentRelatedData, $userRelatedData);
                            
                            // Faqat tasdiqlangan (status = 1) ma'lumotlarni ko'rsatish
                            if ($isSimilar && $userData->status == 1) {
                                $similarity = $this->getTable11Similarity($currentRelatedData, $userRelatedData);
                                
                                $table11SimilarData[] = [
                                    'id' => $userData->id,
                                    'table_type' => $userTableType,
                                    'point' => $userData->point,
                                    'status' => $userData->status,
                                    'created_at' => $userData->created_at->format('d-m-Y H:i'),
                                    'jurnal_nomi' => $userRelatedData->jurnal_nomi ?? '',
                                    'maqola_nomi' => $userRelatedData->maqola_nomi ?? '',
                                    'nashr_yili' => $userRelatedData->nashr_yili ?? '',
                                    'similarity' => $similarity,
                                    'has_points' => $userData->point > 0 // Ball mavjudligini aniqlash
                                ];
                                $hasTable11SimilarData = true;
                            }
                        }
                    }
                }
            }
        }

        // Table_20_1, table_20_2, table_20_3 uchun o'xshash ma'lumotlarni tekshirish
        $table20SimilarData = [];
        $hasTable20SimilarData = false;
        
        // Joriy ma'lumot qaysi table_20_* ga tegishligi aniqlaymiz
        $currentTable20Type = null;
        if (!is_null($information->table_20_1_id)) {
            $currentTable20Type = 'table_20_1';
        } elseif (!is_null($information->table_20_2_id)) {
            $currentTable20Type = 'table_20_2';
        } elseif (!is_null($information->table_20_3_id)) {
            $currentTable20Type = 'table_20_3';
        }

        if ($currentTable20Type) {
            // Joriy ma'lumotning asosiy maydonlarini olamiz
            $currentTable20RelatedData = null;
            foreach ($relatedData as $table => $data) {
                if ($table === $currentTable20Type && $data) {
                    $currentTable20RelatedData = $data;
                    break;
                }
            }

            if ($currentTable20RelatedData) {
                // Foydalanuvchining barcha table_20_* ma'lumotlarini olamiz
                $table20UserData = PointUserDeportament::where('user_id', $information->user_id)
                    ->where('id', '!=', $id)
                    ->where('year', $information->year)
                    ->where(function ($query) {
                        $query->whereNotNull('table_20_1_id')
                              ->orWhereNotNull('table_20_2_id')
                              ->orWhereNotNull('table_20_3_id');
                    })
                    ->get();

                if ($table20UserData->isNotEmpty()) {
                    foreach ($table20UserData as $userData) {
                        $userTableType = null;
                        $userRelatedData = null;
                        
                        if (!is_null($userData->table_20_1_id)) {
                            $userTableType = 'table_20_1';
                            $userRelatedData = $this->getModelClassForRelation('table_20_1')::find($userData->table_20_1_id);
                        } elseif (!is_null($userData->table_20_2_id)) {
                            $userTableType = 'table_20_2';
                            $userRelatedData = $this->getModelClassForRelation('table_20_2')::find($userData->table_20_2_id);
                        } elseif (!is_null($userData->table_20_3_id)) {
                            $userTableType = 'table_20_3';
                            $userRelatedData = $this->getModelClassForRelation('table_20_3')::find($userData->table_20_3_id);
                        }

                        if ($userTableType && $userTableType !== $currentTable20Type && $userRelatedData) {
                            // Ma'lumotlarni taqqoslash
                            $isSimilar = $this->compareTable20Data($currentTable20RelatedData, $userRelatedData);
                            
                            // Faqat tasdiqlangan (status = 1) ma'lumotlarni ko'rsatish
                            if ($isSimilar && $userData->status == 1) {
                                $similarity = $this->getTable20Similarity($currentTable20RelatedData, $userRelatedData);
                                
                                $table20SimilarData[] = [
                                    'id' => $userData->id,
                                    'table_type' => $userTableType,
                                    'point' => $userData->point,
                                    'status' => $userData->status,
                                    'created_at' => $userData->created_at->format('d-m-Y H:i'),
                                    'jurnal_nomi' => $userRelatedData->jurnal_nomi ?? '',
                                    'mualliflar_soni' => $userRelatedData->mualliflar_soni ?? '',
                                    'similarity' => $similarity,
                                    'has_points' => $userData->point > 0 // Ball mavjudligini aniqlash
                                ];
                                $hasTable20SimilarData = true;
                            }
                        }
                    }
                }
            }
        }

        return view('dashboard.show_request', compact('information', 'default_image', 'totalPoints', 'relatedData', 'year', 'userPointInfo', 'hasSimilarData', 'similarDataId', 'hasTable11SimilarData', 'table11SimilarData', 'currentTable11Type', 'hasTable20SimilarData', 'table20SimilarData', 'currentTable20Type'));
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

                // Agar ma'lumotga point berilgan bo'lsa va oldin kafedra bali mavjud bo'lsa, uni o'chirish
                if ($inputPoint > 0) {
                    // Oldingi kafedra balini o'chirish (chunki endi o'qituvchi bali berilmoqda)
                    DepartPoints::where('point_user_deport_id', $model->id)->delete();
                    
                    Log::info('Kafedra bali o\'chirildi (o\'qituvchi bali berilganligi sababli)', [
                        'point_user_deport_id' => $model->id,
                        'user_id' => $model->user_id,
                        'teacher_point_given' => $inputPoint
                    ]);
                }

                // Table_11_* uchun maxsus logika: agar joriy ma'lumot table_11_* tipida bo'lsa
                $currentTable11Type = null;
                if (!is_null($model->table_11_1_id)) {
                    $currentTable11Type = 'table_11_1';
                } elseif (!is_null($model->table_11_2_id)) {
                    $currentTable11Type = 'table_11_2';
                } elseif (!is_null($model->table_11_3_id)) {
                    $currentTable11Type = 'table_11_3';
                }

                if ($currentTable11Type) {
                    // Current record ma'lumotlarini olish
                    $currentRelatedData = $this->getRelatedDataForController($model, $currentTable11Type);

                    if ($currentRelatedData) {
                        // Foydalanuvchining boshqa table_11_* ma'lumotlarini topish va faqat dublikatlarni 0 ga o'tkazish
                        $otherTable11Records = PointUserDeportament::where('user_id', $model->user_id)
                            ->where('id', '!=', $model->id)
                            ->where('year', $model->year)
                            ->where(function ($query) {
                                $query->whereNotNull('table_11_1_id')
                                      ->orWhereNotNull('table_11_2_id')
                                      ->orWhereNotNull('table_11_3_id');
                            })
                            ->get();

                        foreach ($otherTable11Records as $record) {
                            $otherTableType = null;
                            if (!is_null($record->table_11_1_id)) {
                                $otherTableType = 'table_11_1';
                            } elseif (!is_null($record->table_11_2_id)) {
                                $otherTableType = 'table_11_2';
                            } elseif (!is_null($record->table_11_3_id)) {
                                $otherTableType = 'table_11_3';
                            }

                            if ($currentTable11Type === $otherTableType) continue; // Bir xil tip bo'lsa o'tish

                            // Prioritet tartibini tekshirish
                            $priorityOrder = ['table_11_1' => 1, 'table_11_2' => 2, 'table_11_3' => 3];
                            $currentPriority = $priorityOrder[$currentTable11Type] ?? 999;
                            $otherPriority = $priorityOrder[$otherTableType] ?? 999;

                            // Faqat yuqori prioritetli tablelar past prioritetli tablelarga ta'sir qiladi
                            // (kichik raqam = yuqori prioritet, katta raqam = past prioritet)
                            if ($currentPriority > $otherPriority) continue;

                            $otherRelatedData = $this->getRelatedDataForController($record, $otherTableType);

                            if ($otherRelatedData) {
                                // O'xshashlik darajasini hisoblash
                                $similarityScore = $this->calculateTable11SimilarityController($currentRelatedData, $otherRelatedData);
                                
                                // Faqat 70% dan ko'p o'xshash dublikatlarni 0 ga o'tkazish
                                if ($similarityScore >= 0.7) {
                                    $record->point = 0.00;
                                    $record->save();
                                    
                                    // Dublikat uchun avtomatik kafedra bali (0.10) yaratish
                                    DepartPoints::updateOrCreate(
                                        ['point_user_deport_id' => $record->id],
                                        [
                                            'point' => 0.10,
                                            'status' => 1
                                        ]
                                    );
                                }
                            }
                        }
                    }
                }

                // Table_20_* uchun maxsus logika: agar joriy ma'lumot table_20_* tipida bo'lsa
                $currentTable20Type = null;
                if (!is_null($model->table_20_1_id)) {
                    $currentTable20Type = 'table_20_1';
                } elseif (!is_null($model->table_20_2_id)) {
                    $currentTable20Type = 'table_20_2';
                } elseif (!is_null($model->table_20_3_id)) {
                    $currentTable20Type = 'table_20_3';
                }

                if ($currentTable20Type) {
                    // Current record ma'lumotlarini olish
                    $currentRelatedData = $this->getRelatedDataForController($model, $currentTable20Type);

                    if ($currentRelatedData) {
                        // Foydalanuvchining boshqa table_20_* ma'lumotlarini topish va faqat dublikatlarni 0 ga o'tkazish
                        $otherTable20Records = PointUserDeportament::where('user_id', $model->user_id)
                            ->where('id', '!=', $model->id)
                            ->where('year', $model->year)
                            ->where(function ($query) {
                                $query->whereNotNull('table_20_1_id')
                                      ->orWhereNotNull('table_20_2_id')
                                      ->orWhereNotNull('table_20_3_id');
                            })
                            ->get();

                        foreach ($otherTable20Records as $record) {
                            $otherTableType = null;
                            if (!is_null($record->table_20_1_id)) {
                                $otherTableType = 'table_20_1';
                            } elseif (!is_null($record->table_20_2_id)) {
                                $otherTableType = 'table_20_2';
                            } elseif (!is_null($record->table_20_3_id)) {
                                $otherTableType = 'table_20_3';
                            }

                            if ($currentTable20Type === $otherTableType) continue; // Bir xil tip bo'lsa o'tish

                            // Prioritet tartibini tekshirish
                            $priorityOrder = ['table_20_1' => 1, 'table_20_2' => 2, 'table_20_3' => 3];
                            $currentPriority = $priorityOrder[$currentTable20Type] ?? 999;
                            $otherPriority = $priorityOrder[$otherTableType] ?? 999;

                            // Faqat yuqori prioritetli tablelar past prioritetli tablelarga ta'sir qiladi
                            // (kichik raqam = yuqori prioritet, katta raqam = past prioritet)
                            if ($currentPriority > $otherPriority) continue;

                            $otherRelatedData = $this->getRelatedDataForController($record, $otherTableType);

                            if ($otherRelatedData) {
                                // O'xshashlik darajasini hisoblash
                                $similarityScore = $this->calculateTable20SimilarityController($currentRelatedData, $otherRelatedData);
                                
                                // Faqat 70% dan ko'p o'xshash dublikatlarni 0 ga o'tkazish
                                if ($similarityScore >= 0.7) {
                                    $record->point = 0.00;
                                    $record->save();
                                    
                                    // Dublikat uchun avtomatik kafedra bali (0.10) yaratish
                                    DepartPoints::updateOrCreate(
                                        ['point_user_deport_id' => $record->id],
                                        [
                                            'point' => 0.10,
                                            'status' => 1
                                        ]
                                    );
                                }
                            }
                        }
                    }
                }

                // Kafedra balini tekshirish
                if ($request->has('kafedra_uchun')) {
                    // Kafedra uchun ball har doim 0.10 qilib qo'yiladi
                    if ($inputForDepart > 0) {
                        // Agar ball 0 dan katta bo'lsa yangilaymiz yoki yaratamiz
                        DepartPoints::updateOrCreate(
                            ['point_user_deport_id' => $model->id],
                            [
                                'point' => 0.10, // Har doim 0.10 ball
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

    /**
     * Table_11_* ma'lumotlarini taqqoslash
     */
    private function compareTable11Data($data1, $data2)
    {
        // Asosiy maydonlarni normallashtirish va taqqoslash
        $journal1 = $this->normalizeText($data1->jurnal_nomi ?? '');
        $journal2 = $this->normalizeText($data2->jurnal_nomi ?? '');
        
        $article1 = $this->normalizeText($data1->maqola_nomi ?? '');
        $article2 = $this->normalizeText($data2->maqola_nomi ?? '');
        
        $year1 = $this->normalizeYear($data1->nashr_yili ?? '');
        $year2 = $this->normalizeYear($data2->nashr_yili ?? '');

        // O'xshashlik foizini hisoblash
        $journalSimilarity = $this->calculateSimilarity($journal1, $journal2);
        $articleSimilarity = $this->calculateSimilarity($article1, $article2);
        $yearMatch = ($year1 === $year2 && !empty($year1)) ? 1.0 : 0.0;

        // Agar jurnal va maqola nomi 70% dan ko'p o'xshash bo'lsa va yil bir xil bo'lsa
        return ($journalSimilarity >= 0.7 && $articleSimilarity >= 0.7 && $yearMatch > 0);
    }

    /**
     * Matnni normallash
     */
    private function normalizeText($text)
    {
        // Kichik harfga o'tkazish
        $text = mb_strtolower($text, 'UTF-8');
        
        // Ortiqcha bo'sh joylarni olib tashlash
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Tinish belgilarini olib tashlash
        $text = preg_replace('/[.,;:!?"`\'"«»„"]/', '', $text);
        
        return $text;
    }

    /**
     * Yilni normallash
     */
    private function normalizeYear($yearText)
    {
        // 4 xonali raqamni qidirish
        preg_match('/\b(20\d{2}|19\d{2})\b/', $yearText, $matches);
        return $matches[0] ?? '';
    }

    /**
     * Matnlar o'rtasidagi o'xshashlik foizini hisoblash (Levenshtein distance)
     */
    private function calculateSimilarity($str1, $str2)
    {
        if (empty($str1) || empty($str2)) {
            return 0.0;
        }

        $len1 = mb_strlen($str1, 'UTF-8');
        $len2 = mb_strlen($str2, 'UTF-8');
        $maxLen = max($len1, $len2);
        
        if ($maxLen == 0) {
            return 1.0;
        }

        // Levenshtein masofasini hisoblash
        $distance = levenshtein($str1, $str2);
        
        // O'xshashlik foizini qaytarish
        return max(0, 1 - ($distance / $maxLen));
    }

    /**
     * Table_11_* ma'lumotlari o'xshashligi haqida batafsil ma'lumot
     */
    private function getTable11Similarity($data1, $data2)
    {
        $journal1 = $this->normalizeText($data1->jurnal_nomi ?? '');
        $journal2 = $this->normalizeText($data2->jurnal_nomi ?? '');
        
        $article1 = $this->normalizeText($data1->maqola_nomi ?? '');
        $article2 = $this->normalizeText($data2->maqola_nomi ?? '');
        
        $year1 = $this->normalizeYear($data1->nashr_yili ?? '');
        $year2 = $this->normalizeYear($data2->nashr_yili ?? '');

        return [
            'journal_similarity' => round($this->calculateSimilarity($journal1, $journal2) * 100, 1),
            'article_similarity' => round($this->calculateSimilarity($article1, $article2) * 100, 1),
            'year_match' => ($year1 === $year2 && !empty($year1)),
            'year1' => $year1,
            'year2' => $year2
        ];
    }

    /**
     * Table_20_* ma'lumotlarini taqqoslash
     */
    private function compareTable20Data($data1, $data2)
    {
        // Asosiy maydonlarni normallashtirish va taqqoslash
        $journal1 = $this->normalizeText($data1->jurnal_nomi ?? '');
        $journal2 = $this->normalizeText($data2->jurnal_nomi ?? '');
        
        $authors1 = $this->normalizeText($data1->mualliflar_soni ?? '');
        $authors2 = $this->normalizeText($data2->mualliflar_soni ?? '');

        // O'xshashlik foizini hisoblash
        $journalSimilarity = $this->calculateSimilarity($journal1, $journal2);
        $authorsSimilarity = $this->calculateSimilarity($authors1, $authors2);

        // Agar jurnal nomi va mualliflar soni 70% dan ko'p o'xshash bo'lsa
        return ($journalSimilarity >= 0.7 && $authorsSimilarity >= 0.7);
    }

    /**
     * Table_20_* ma'lumotlari o'xshashligi haqida batafsil ma'lumot
     */
    private function getTable20Similarity($data1, $data2)
    {
        $journal1 = $this->normalizeText($data1->jurnal_nomi ?? '');
        $journal2 = $this->normalizeText($data2->jurnal_nomi ?? '');
        
        $authors1 = $this->normalizeText($data1->mualliflar_soni ?? '');
        $authors2 = $this->normalizeText($data2->mualliflar_soni ?? '');

        return [
            'journal_similarity' => round($this->calculateSimilarity($journal1, $journal2) * 100, 1),
            'authors_similarity' => round($this->calculateSimilarity($authors1, $authors2) * 100, 1)
        ];
    }

    /**
     * Ma'lumotlarni olish (relation orqali)
     */
    private function getRelatedDataForController($record, $tableType)
    {
        try {
            switch ($tableType) {
                case 'table_11_1':
                    return $record->table_11_1;
                case 'table_11_2':
                    return $record->table_11_2;
                case 'table_11_3':
                    return $record->table_11_3;
                case 'table_20_1':
                    return $record->table_20_1;
                case 'table_20_2':
                    return $record->table_20_2;
                case 'table_20_3':
                    return $record->table_20_3;
                default:
                    return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Table 11 o'xshashlik hisoblash
     */
    private function calculateTable11SimilarityController($data1, $data2)
    {
        $journalSimilarity = $this->calculateSimilarity(
            $this->normalizeText($data1->jurnal_nomi ?? ''),
            $this->normalizeText($data2->jurnal_nomi ?? '')
        );
        
        $articleSimilarity = $this->calculateSimilarity(
            $this->normalizeText($data1->maqola_nomi ?? ''),
            $this->normalizeText($data2->maqola_nomi ?? '')
        );
        
        $year1 = $this->normalizeYear($data1->nashr_yili ?? '');
        $year2 = $this->normalizeYear($data2->nashr_yili ?? '');
        $yearSimilarity = ($year1 === $year2 && !empty($year1)) ? 1.0 : 0.0;

        // Weighted average: jurnal 33%, maqola 33%, yil 34%
        return ($journalSimilarity * 0.33) + ($articleSimilarity * 0.33) + ($yearSimilarity * 0.34);
    }

    /**
     * Table 20 o'xshashlik hisoblash
     */
    private function calculateTable20SimilarityController($data1, $data2)
    {
        $journalSimilarity = $this->calculateSimilarity(
            $this->normalizeText($data1->jurnal_nomi ?? ''),
            $this->normalizeText($data2->jurnal_nomi ?? '')
        );
        
        $authorsSimilarity = 0.0;
        if (isset($data1->mualliflar_soni) && isset($data2->mualliflar_soni)) {
            $authors1 = intval($data1->mualliflar_soni);
            $authors2 = intval($data2->mualliflar_soni);
            $authorsSimilarity = ($authors1 === $authors2) ? 1.0 : 0.0;
        }

        // Weighted average: jurnal 80%, mualliflar soni 20%
        return ($journalSimilarity * 0.8) + ($authorsSimilarity * 0.2);
    }
}
