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
                // Foydalanuvchining barcha table_11_* ma'lumotlarini olamiz (faqat tasdiqlangan, ham aktiv ham tuzatilgan)
                $table11UserData = PointUserDeportament::where('user_id', $information->user_id)
                    ->where('id', '!=', $id)
                    ->where('year', $information->year)
                    ->where('status', 1) // Faqat tasdiqlangan
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
                            // 70% similarity algorithm bilgan taqqoslash
                            $similarityScore = $this->calculateTable11SimilarityController($currentRelatedData, $userRelatedData);
                            
                            // Faqat 70% dan yuqori o'xshash ma'lumotlarni ko'rsatish
                            if ($similarityScore >= 0.7) {
                                $similarity = $this->getTable11SimilarityDetails($currentRelatedData, $userRelatedData);
                                
                                // Prioritet tartibini tekshirish
                                $priorityOrder = ['table_11_1' => 1, 'table_11_2' => 2, 'table_11_3' => 3];
                                $currentPriority = $priorityOrder[$currentTable11Type] ?? 999;
                                $userPriority = $priorityOrder[$userTableType] ?? 999;
                                
                                // Tuzatilgan dublikat ekanligini aniqlash (point = 0 va kafedra bali mavjud)
                                $hasKafedraPoint = \App\Models\DepartPoints::where('point_user_deport_id', $userData->id)->exists();
                                $isFixed = ($userData->point == 0 && $hasKafedraPoint);
                                
                                // Agar joriy ma'lumot past prioritetli bo'lsa va boshqa ma'lumot yuqori prioritetli bo'lsa
                                // bu holda "tuzatilgan" emas, balki "tuzata olmaydi" deb ko'rsatish kerak
                                $cannotFix = ($currentPriority > $userPriority);
                                
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
                                    'similarity_score' => $similarityScore,
                                    'has_points' => $userData->point > 0, // Ball mavjudligini aniqlash
                                    'is_fixed' => $isFixed && !$cannotFix, // Tuzatilgan dublikat (faqat tuzata oladigan bo'lsa)
                                    'cannot_fix' => $cannotFix, // Tuzata olmaydigan holat
                                    'kafedra_point' => $hasKafedraPoint ? 0.10 : 0,
                                    'current_priority' => $currentPriority,
                                    'other_priority' => $userPriority
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
                // Foydalanuvchining barcha table_20_* ma'lumotlarini olamiz (faqat tasdiqlangan, ham aktiv ham tuzatilgan)
                $table20UserData = PointUserDeportament::where('user_id', $information->user_id)
                    ->where('id', '!=', $id)
                    ->where('year', $information->year)
                    ->where('status', 1) // Faqat tasdiqlangan
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
                            // 70% similarity algorithm bilgan taqqoslash
                            $similarityScore = $this->calculateTable20SimilarityController($currentTable20RelatedData, $userRelatedData);
                            
                            // Faqat 70% dan yuqori o'xshash ma'lumotlarni ko'rsatish
                            if ($similarityScore >= 0.7) {
                                $similarity = $this->getTable20SimilarityDetails($currentTable20RelatedData, $userRelatedData);
                                
                                // Prioritet tartibini tekshirish
                                $priorityOrder = ['table_20_1' => 1, 'table_20_2' => 2, 'table_20_3' => 3];
                                $currentPriority = $priorityOrder[$currentTable20Type] ?? 999;
                                $userPriority = $priorityOrder[$userTableType] ?? 999;
                                
                                // Tuzatilgan dublikat ekanligini aniqlash (point = 0 va kafedra bali mavjud)
                                $hasKafedraPoint = \App\Models\DepartPoints::where('point_user_deport_id', $userData->id)->exists();
                                $isFixed = ($userData->point == 0 && $hasKafedraPoint);
                                
                                // Agar joriy ma'lumot past prioritetli bo'lsa va boshqa ma'lumot yuqori prioritetli bo'lsa
                                // bu holda "tuzatilgan" emas, balki "tuzata olmaydi" deb ko'rsatish kerak
                                $cannotFix = ($currentPriority > $userPriority);
                                
                                $table20SimilarData[] = [
                                    'id' => $userData->id,
                                    'table_type' => $userTableType,
                                    'point' => $userData->point,
                                    'status' => $userData->status,
                                    'created_at' => $userData->created_at->format('d-m-Y H:i'),
                                    'jurnal_nomi' => $userRelatedData->jurnal_nomi ?? '',
                                    'mualliflar_soni' => $userRelatedData->mualliflar_soni ?? '',
                                    'similarity' => $similarity,
                                    'similarity_score' => $similarityScore,
                                    'has_points' => $userData->point > 0, // Ball mavjudligini aniqlash
                                    'is_fixed' => $isFixed && !$cannotFix, // Tuzatilgan dublikat (faqat tuzata oladigan bo'lsa)
                                    'cannot_fix' => $cannotFix, // Tuzata olmaydigan holat
                                    'kafedra_point' => $hasKafedraPoint ? 0.10 : 0,
                                    'current_priority' => $currentPriority,
                                    'other_priority' => $userPriority
                                ];
                                $hasTable20SimilarData = true;
                            }
                        }
                    }
                }
            }
        }

        // Table_14_1, table_14_2 uchun o'xshash ma'lumotlarni tekshirish
        $table14SimilarData = [];
        $hasTable14SimilarData = false;
        
        // Joriy ma'lumot qaysi table_14_* ga tegishligi aniqlaymiz
        $currentTable14Type = null;
        if (!is_null($information->table_14_1_id)) {
            $currentTable14Type = 'table_14_1';
        } elseif (!is_null($information->table_14_2_id)) {
            $currentTable14Type = 'table_14_2';
        }

        if ($currentTable14Type) {
            // Joriy ma'lumotning asosiy maydonlarini olamiz
            $currentTable14RelatedData = null;
            foreach ($relatedData as $table => $data) {
                if ($table === $currentTable14Type && $data) {
                    $currentTable14RelatedData = $data;
                    break;
                }
            }

            if ($currentTable14RelatedData) {
                // Foydalanuvchining barcha table_14_* ma'lumotlarini olamiz (faqat tasdiqlangan, ham aktiv ham tuzatilgan)
                $table14UserData = PointUserDeportament::where('user_id', $information->user_id)
                    ->where('id', '!=', $id)
                    ->where('year', $information->year)
                    ->where('status', 1) // Faqat tasdiqlangan
                    ->where(function ($query) {
                        $query->whereNotNull('table_14_1_id')
                              ->orWhereNotNull('table_14_2_id');
                    })
                    ->get();

                if ($table14UserData->isNotEmpty()) {
                    foreach ($table14UserData as $userData) {
                        $userTableType = null;
                        $userRelatedData = null;
                        
                        if (!is_null($userData->table_14_1_id)) {
                            $userTableType = 'table_14_1';
                            $userRelatedData = $this->getModelClassForRelation('table_14_1')::find($userData->table_14_1_id);
                        } elseif (!is_null($userData->table_14_2_id)) {
                            $userTableType = 'table_14_2';
                            $userRelatedData = $this->getModelClassForRelation('table_14_2')::find($userData->table_14_2_id);
                        }

                        if ($userTableType && $userTableType !== $currentTable14Type && $userRelatedData) {
                            // 70% similarity algorithm bilgan taqqoslash
                            $similarityScore = $this->calculateTable14SimilarityController($currentTable14RelatedData, $userRelatedData);
                            
                            // Faqat 70% dan yuqori o'xshash ma'lumotlarni ko'rsatish
                            if ($similarityScore >= 0.7) {
                                $similarity = $this->getTable14SimilarityDetails($currentTable14RelatedData, $userRelatedData);
                                
                                // Prioritet tartibini tekshirish
                                $priorityOrder = ['table_14_1' => 1, 'table_14_2' => 2];
                                $currentPriority = $priorityOrder[$currentTable14Type] ?? 999;
                                $userPriority = $priorityOrder[$userTableType] ?? 999;
                                
                                // Tuzatilgan dublikat ekanligini aniqlash (point = 0 va kafedra bali mavjud)
                                $hasKafedraPoint = \App\Models\DepartPoints::where('point_user_deport_id', $userData->id)->exists();
                                $isFixed = ($userData->point == 0 && $hasKafedraPoint);
                                
                                // Agar joriy ma'lumot past prioritetli bo'lsa va boshqa ma'lumot yuqori prioritetli bo'lsa
                                // bu holda "tuzatilgan" emas, balki "tuzata olmaydi" deb ko'rsatish kerak
                                $cannotFix = ($currentPriority > $userPriority);
                                
                                $table14SimilarData[] = [
                                    'id' => $userData->id,
                                    'table_type' => $userTableType,
                                    'point' => $userData->point,
                                    'status' => $userData->status,
                                    'created_at' => $userData->created_at->format('d-m-Y H:i'),
                                    'davlat_otm_nomi' => $userRelatedData->davlat_otm_nomi ?? '',
                                    'tezis_nomi' => $userRelatedData->tezis_nomi ?? '',
                                    'konf_seminar_nomi' => $userRelatedData->konf_seminar_nomi ?? '',
                                    'similarity' => $similarity,
                                    'similarity_score' => $similarityScore,
                                    'has_points' => $userData->point > 0, // Ball mavjudligini aniqlash
                                    'is_fixed' => $isFixed && !$cannotFix, // Tuzatilgan dublikat (faqat tuzata oladigan bo'lsa)
                                    'cannot_fix' => $cannotFix, // Tuzata olmaydigan holat
                                    'kafedra_point' => $hasKafedraPoint ? 0.10 : 0,
                                    'current_priority' => $currentPriority,
                                    'other_priority' => $userPriority
                                ];
                                $hasTable14SimilarData = true;
                            }
                        }
                    }
                }
            }
        }

        // Table_10_1, table_10_2, table_10_3 uchun o'xshash ma'lumotlarni tekshirish
        $table10SimilarData = [];
        $hasTable10SimilarData = false;
        
        // Joriy ma'lumot qaysi table_10_* ga tegishligi aniqlaymiz
        $currentTable10Type = null;
        if (!is_null($information->table_10_1_id)) {
            $currentTable10Type = 'table_10_1';
        } elseif (!is_null($information->table_10_2_id)) {
            $currentTable10Type = 'table_10_2';
        } elseif (!is_null($information->table_10_3_id)) {
            $currentTable10Type = 'table_10_3';
        }

        if ($currentTable10Type) {
            // Joriy ma'lumotning asosiy maydonlarini olamiz
            $currentTable10RelatedData = null;
            foreach ($relatedData as $table => $data) {
                if ($table === $currentTable10Type && $data) {
                    $currentTable10RelatedData = $data;
                    break;
                }
            }

            if ($currentTable10RelatedData) {
                // Foydalanuvchining barcha table_10_* ma'lumotlarini olamiz (faqat tasdiqlangan, ham aktiv ham tuzatilgan)
                $table10UserData = PointUserDeportament::where('user_id', $information->user_id)
                    ->where('id', '!=', $id)
                    ->where('year', $information->year)
                    ->where('status', 1) // Faqat tasdiqlangan
                    ->where(function ($query) {
                        $query->whereNotNull('table_10_1_id')
                              ->orWhereNotNull('table_10_2_id')
                              ->orWhereNotNull('table_10_3_id');
                    })
                    ->get();

                if ($table10UserData->isNotEmpty()) {
                    foreach ($table10UserData as $userData) {
                        $userTableType = null;
                        $userRelatedData = null;
                        
                        if (!is_null($userData->table_10_1_id)) {
                            $userTableType = 'table_10_1';
                            $userRelatedData = $this->getModelClassForRelation('table_10_1')::find($userData->table_10_1_id);
                        } elseif (!is_null($userData->table_10_2_id)) {
                            $userTableType = 'table_10_2';
                            $userRelatedData = $this->getModelClassForRelation('table_10_2')::find($userData->table_10_2_id);
                        } elseif (!is_null($userData->table_10_3_id)) {
                            $userTableType = 'table_10_3';
                            $userRelatedData = $this->getModelClassForRelation('table_10_3')::find($userData->table_10_3_id);
                        }

                        if ($userTableType && $userTableType !== $currentTable10Type && $userRelatedData) {
                            // 70% similarity algorithm bilgan taqqoslash
                            $similarityScore = $this->calculateTable10SimilarityController($currentTable10RelatedData, $userRelatedData);
                            
                            // Faqat 70% dan yuqori o'xshash ma'lumotlarni ko'rsatish
                            if ($similarityScore >= 0.7) {
                                $similarity = $this->getTable10SimilarityDetails($currentTable10RelatedData, $userRelatedData);
                                
                                // Prioritet tartibini tekshirish
                                $priorityOrder = ['table_10_1' => 1, 'table_10_2' => 2, 'table_10_3' => 3];
                                $currentPriority = $priorityOrder[$currentTable10Type] ?? 999;
                                $userPriority = $priorityOrder[$userTableType] ?? 999;
                                
                                // Tuzatilgan dublikat ekanligini aniqlash (point = 0 va kafedra bali mavjud)
                                $hasKafedraPoint = \App\Models\DepartPoints::where('point_user_deport_id', $userData->id)->exists();
                                $isFixed = ($userData->point == 0 && $hasKafedraPoint);
                                
                                // Agar joriy ma'lumot past prioritetli bo'lsa va boshqa ma'lumot yuqori prioritetli bo'lsa
                                // bu holda "tuzatilgan" emas, balki "tuzata olmaydi" deb ko'rsatish kerak
                                $cannotFix = ($currentPriority > $userPriority);
                                
                                // Table 10 uchun maydonlarni aniqlash
                                $journalName = '';
                                $articleName = '';
                                $publishYear = '';
                                
                                if ($userTableType === 'table_10_1' || $userTableType === 'table_10_2') {
                                    $journalName = $userRelatedData->ilmiy_jurnal_nomi ?? '';
                                    $articleName = $userRelatedData->ilmiy_maqola_nomi ?? '';
                                    $publishYear = $userRelatedData->nashr_yili_betlari ?? '';
                                } else { // table_10_3
                                    $journalName = $userRelatedData->konfrrensiya_nomi ?? '';
                                    $articleName = $userRelatedData->maqola_nomi ?? '';
                                    $publishYear = $userRelatedData->Nashr_yili_betlari ?? '';
                                }
                                
                                $table10SimilarData[] = [
                                    'id' => $userData->id,
                                    'table_type' => $userTableType,
                                    'point' => $userData->point,
                                    'status' => $userData->status,
                                    'created_at' => $userData->created_at->format('d-m-Y H:i'),
                                    'journal_name' => $journalName,
                                    'article_name' => $articleName,
                                    'publish_year' => $publishYear,
                                    'similarity' => $similarity,
                                    'similarity_score' => $similarityScore,
                                    'has_points' => $userData->point > 0, // Ball mavjudligini aniqlash
                                    'is_fixed' => $isFixed && !$cannotFix, // Tuzatilgan dublikat (faqat tuzata oladigan bo'lsa)
                                    'cannot_fix' => $cannotFix, // Tuzata olmaydigan holat
                                    'kafedra_point' => $hasKafedraPoint ? 0.10 : 0,
                                    'current_priority' => $currentPriority,
                                    'other_priority' => $userPriority
                                ];
                                $hasTable10SimilarData = true;
                            }
                        }
                    }
                }
            }
        }

        return view('dashboard.show_request', compact('information', 'default_image', 'totalPoints', 'relatedData', 'year', 'userPointInfo', 'hasSimilarData', 'similarDataId', 'hasTable11SimilarData', 'table11SimilarData', 'currentTable11Type', 'hasTable20SimilarData', 'table20SimilarData', 'currentTable20Type', 'hasTable14SimilarData', 'table14SimilarData', 'currentTable14Type', 'hasTable10SimilarData', 'table10SimilarData', 'currentTable10Type'));
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

                // Table_14_* uchun maxsus logika: agar joriy ma'lumot table_14_* tipida bo'lsa
                $currentTable14Type = null;
                if (!is_null($model->table_14_1_id)) {
                    $currentTable14Type = 'table_14_1';
                } elseif (!is_null($model->table_14_2_id)) {
                    $currentTable14Type = 'table_14_2';
                }

                if ($currentTable14Type) {
                    // Current record ma'lumotlarini olish
                    $currentRelatedData = $this->getRelatedDataForController($model, $currentTable14Type);

                    if ($currentRelatedData) {
                        // Foydalanuvchining boshqa table_14_* ma'lumotlarini topish va faqat dublikatlarni 0 ga o'tkazish
                        $otherTable14Records = PointUserDeportament::where('user_id', $model->user_id)
                            ->where('id', '!=', $model->id)
                            ->where('year', $model->year)
                            ->where(function ($query) {
                                $query->whereNotNull('table_14_1_id')
                                      ->orWhereNotNull('table_14_2_id');
                            })
                            ->get();

                        foreach ($otherTable14Records as $record) {
                            $otherTableType = null;
                            if (!is_null($record->table_14_1_id)) {
                                $otherTableType = 'table_14_1';
                            } elseif (!is_null($record->table_14_2_id)) {
                                $otherTableType = 'table_14_2';
                            }

                            if ($currentTable14Type === $otherTableType) continue; // Bir xil tip bo'lsa o'tish

                            // Prioritet tartibini tekshirish
                            $priorityOrder = ['table_14_1' => 1, 'table_14_2' => 2];
                            $currentPriority = $priorityOrder[$currentTable14Type] ?? 999;
                            $otherPriority = $priorityOrder[$otherTableType] ?? 999;

                            // Faqat yuqori prioritetli tablelar past prioritetli tablelarga ta'sir qiladi
                            // (kichik raqam = yuqori prioritet, katta raqam = past prioritet)
                            if ($currentPriority > $otherPriority) continue;

                            $otherRelatedData = $this->getRelatedDataForController($record, $otherTableType);

                            if ($otherRelatedData) {
                                // O'xshashlik darajasini hisoblash
                                $similarityScore = $this->calculateTable14SimilarityController($currentRelatedData, $otherRelatedData);
                                
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

                // Table_10_* uchun maxsus logika: agar joriy ma'lumot table_10_* tipida bo'lsa
                $currentTable10Type = null;
                if (!is_null($model->table_10_1_id)) {
                    $currentTable10Type = 'table_10_1';
                } elseif (!is_null($model->table_10_2_id)) {
                    $currentTable10Type = 'table_10_2';
                } elseif (!is_null($model->table_10_3_id)) {
                    $currentTable10Type = 'table_10_3';
                }

                if ($currentTable10Type) {
                    // Current record ma'lumotlarini olish
                    $currentRelatedData = $this->getRelatedDataForController($model, $currentTable10Type);

                    if ($currentRelatedData) {
                        // Foydalanuvchining boshqa table_10_* ma'lumotlarini topish va faqat dublikatlarni 0 ga o'tkazish
                        $otherTable10Records = PointUserDeportament::where('user_id', $model->user_id)
                            ->where('id', '!=', $model->id)
                            ->where('year', $model->year)
                            ->where(function ($query) {
                                $query->whereNotNull('table_10_1_id')
                                      ->orWhereNotNull('table_10_2_id')
                                      ->orWhereNotNull('table_10_3_id');
                            })
                            ->get();

                        foreach ($otherTable10Records as $record) {
                            $otherTableType = null;
                            if (!is_null($record->table_10_1_id)) {
                                $otherTableType = 'table_10_1';
                            } elseif (!is_null($record->table_10_2_id)) {
                                $otherTableType = 'table_10_2';
                            } elseif (!is_null($record->table_10_3_id)) {
                                $otherTableType = 'table_10_3';
                            }

                            if ($currentTable10Type === $otherTableType) continue; // Bir xil tip bo'lsa o'tish

                            // Prioritet tartibini tekshirish
                            $priorityOrder = ['table_10_1' => 1, 'table_10_2' => 2, 'table_10_3' => 3];
                            $currentPriority = $priorityOrder[$currentTable10Type] ?? 999;
                            $otherPriority = $priorityOrder[$otherTableType] ?? 999;

                            // Faqat yuqori prioritetli tablelar past prioritetli tablelarga ta'sir qiladi
                            // (kichik raqam = yuqori prioritet, katta raqam = past prioritet)
                            if ($currentPriority > $otherPriority) continue;

                            $otherRelatedData = $this->getRelatedDataForController($record, $otherTableType);

                            if ($otherRelatedData) {
                                // O'xshashlik darajasini hisoblash
                                $similarityScore = $this->calculateTable10SimilarityController($currentRelatedData, $otherRelatedData);
                                
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
        // Matnlarni normallashtirish
        $str1 = $this->normalizeText($str1);
        $str2 = $this->normalizeText($str2);
        
        if (empty($str1) || empty($str2)) {
            return 0.0;
        }

        $len1 = mb_strlen($str1, 'UTF-8');
        $len2 = mb_strlen($str2, 'UTF-8');
        $maxLen = max($len1, $len2);
        
        if ($maxLen == 0) {
            return 1.0;
        }

        // Agar matnlar aynan bir xil bo'lsa
        if ($str1 === $str2) {
            return 1.0;
        }

        // Levenshtein masofasini hisoblash
        $distance = levenshtein($str1, $str2);
        
        // Agar distance xato qaytarsa (masalan, juda uzun matnlar uchun)
        if ($distance === -1) {
            // Oddiy taqqoslash
            return ($str1 === $str2) ? 1.0 : 0.0;
        }
        
        // O'xshashlik foizini qaytarish
        return max(0, 1 - ($distance / $maxLen));
    }

    /**
     * Table_11_* ma'lumotlari o'xshashligi haqida batafsil ma'lumot
     */
    private function getTable11SimilarityDetails($data1, $data2)
    {
        $journalSimilarity = $this->calculateSimilarity($data1->jurnal_nomi ?? '', $data2->jurnal_nomi ?? '');
        $articleSimilarity = $this->calculateSimilarity($data1->maqola_nomi ?? '', $data2->maqola_nomi ?? '');
        $yearSimilarity = $this->calculateSimilarity($this->normalizeYear($data1->nashr_yili ?? ''), $this->normalizeYear($data2->nashr_yili ?? ''));

        return [
            'journal_similarity' => round($journalSimilarity * 100, 1),
            'article_similarity' => round($articleSimilarity * 100, 1),
            'year_similarity' => round($yearSimilarity * 100, 1),
            'year_match' => $yearSimilarity > 0.9, // 90% dan yuqori bo'lsa match deb hisoblaymiz
            'year1' => $this->normalizeYear($data1->nashr_yili ?? ''),
            'year2' => $this->normalizeYear($data2->nashr_yili ?? '')
        ];
    }



    /**
     * Table_20_* ma'lumotlari o'xshashligi haqida batafsil ma'lumot
     */
    private function getTable20SimilarityDetails($data1, $data2)
    {
        $journalSimilarity = $this->calculateSimilarity($data1->jurnal_nomi ?? '', $data2->jurnal_nomi ?? '');
        $authorsSimilarity = 0.0;
        
        // Mualliflar sonini taqqoslash
        if (isset($data1->mualliflar_soni) && isset($data2->mualliflar_soni)) {
            $authors1 = intval($data1->mualliflar_soni);
            $authors2 = intval($data2->mualliflar_soni);
            $authorsSimilarity = ($authors1 === $authors2) ? 1.0 : 0.0;
        }

        return [
            'journal_similarity' => round($journalSimilarity * 100, 1),
            'authors_similarity' => round($authorsSimilarity * 100, 1)
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
                case 'table_14_1':
                    return $record->table_14_1;
                case 'table_14_2':
                    return $record->table_14_2;
                case 'table_10_1':
                    return $record->table_10_1;
                case 'table_10_2':
                    return $record->table_10_2;
                case 'table_10_3':
                    return $record->table_10_3;
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
        // Asosiy maydonlarni taqqoslash
        $journalSimilarity = $this->calculateSimilarity($data1->jurnal_nomi ?? '', $data2->jurnal_nomi ?? '');
        $articleSimilarity = $this->calculateSimilarity($data1->maqola_nomi ?? '', $data2->maqola_nomi ?? '');
        $yearSimilarity = $this->calculateSimilarity($this->normalizeYear($data1->nashr_yili ?? ''), $this->normalizeYear($data2->nashr_yili ?? ''));

        // O'xshashlik foizini hisoblash (bir xil algrithm DuplicateManagementController bilan)
        $similarityScore = ($journalSimilarity + $articleSimilarity + $yearSimilarity) / 3.0;
        
        return $similarityScore;
    }

    /**
     * Table 20 o'xshashlik hisoblash
     */
    private function calculateTable20SimilarityController($data1, $data2)
    {
        // Asosiy maydonlarni taqqoslash
        $journalSimilarity = $this->calculateSimilarity($data1->jurnal_nomi ?? '', $data2->jurnal_nomi ?? '');
        $authorsSimilarity = $this->calculateSimilarity($data1->mualliflar_soni ?? '', $data2->mualliflar_soni ?? '');

        // O'xshashlik foizini hisoblash (bir xil algrithm DuplicateManagementController bilan)
        $similarityScore = ($journalSimilarity + $authorsSimilarity) / 2.0;
        
        return $similarityScore;
    }

    /**
     * Table 14 o'xshashlik hisoblash
     */
    private function calculateTable14SimilarityController($data1, $data2)
    {
        // Tezis nomlarini taqqoslash
        $tezisSimilarity = $this->calculateSimilarity($data1->tezis_nomi ?? '', $data2->tezis_nomi ?? '');

        // Konferensiya/seminar nomlarini taqqoslash
        $konfSimilarity = $this->calculateSimilarity($data1->konf_seminar_nomi ?? '', $data2->konf_seminar_nomi ?? '');

        // O'xshashlik foizini hisoblash (bir xil algrithm DuplicateManagementController bilan)
        $similarityScore = ($tezisSimilarity + $konfSimilarity) / 2.0;
        
        return $similarityScore;
    }

    /**
     * Table_14_* ma'lumotlari o'xshashligi haqida batafsil ma'lumot
     */
    private function getTable14SimilarityDetails($data1, $data2)
    {
        $tezisSimilarity = $this->calculateSimilarity($data1->tezis_nomi ?? '', $data2->tezis_nomi ?? '');
        $konfSimilarity = $this->calculateSimilarity($data1->konf_seminar_nomi ?? '', $data2->konf_seminar_nomi ?? '');

        return [
            'tezis_similarity' => round($tezisSimilarity * 100, 1),
            'conference_similarity' => round($konfSimilarity * 100, 1)
        ];
    }

    /**
     * Table 10 o'xshashlik hisoblash
     */
    private function calculateTable10SimilarityController($data1, $data2)
    {
        // Table 10 uchun maydon nomlarini aniqlash
        $journal1 = '';
        $article1 = '';
        $year1 = '';
        
        // data1 ning maydonlarini aniqlash (table_10_1/10_2 vs table_10_3)
        if (isset($data1->ilmiy_jurnal_nomi)) {
            // table_10_1 yoki table_10_2
            $journal1 = $data1->ilmiy_jurnal_nomi ?? '';
            $article1 = $data1->ilmiy_maqola_nomi ?? '';
            $year1 = $data1->nashr_yili_betlari ?? '';
        } else {
            // table_10_3
            $journal1 = $data1->konfrrensiya_nomi ?? '';
            $article1 = $data1->maqola_nomi ?? '';
            $year1 = $data1->Nashr_yili_betlari ?? '';
        }
        
        $journal2 = '';
        $article2 = '';
        $year2 = '';
        
        // data2 ning maydonlarini aniqlash
        if (isset($data2->ilmiy_jurnal_nomi)) {
            // table_10_1 yoki table_10_2
            $journal2 = $data2->ilmiy_jurnal_nomi ?? '';
            $article2 = $data2->ilmiy_maqola_nomi ?? '';
            $year2 = $data2->nashr_yili_betlari ?? '';
        } else {
            // table_10_3
            $journal2 = $data2->konfrrensiya_nomi ?? '';
            $article2 = $data2->maqola_nomi ?? '';
            $year2 = $data2->Nashr_yili_betlari ?? '';
        }

        // O'xshashlik foizini hisoblash
        $journalSimilarity = $this->calculateSimilarity($journal1, $journal2);
        $articleSimilarity = $this->calculateSimilarity($article1, $article2);
        $yearSimilarity = $this->calculateSimilarity($this->normalizeYear($year1), $this->normalizeYear($year2));

        // 3 ta maydon bo'yicha o'rtacha hisoblash
        return ($journalSimilarity + $articleSimilarity + $yearSimilarity) / 3.0;
    }

    /**
     * Table_10_* ma'lumotlari o'xshashligi haqida batafsil ma'lumot
     */
    private function getTable10SimilarityDetails($data1, $data2)
    {
        // Table 10 uchun maydon nomlarini aniqlash
        $journal1 = '';
        $article1 = '';
        $year1 = '';
        
        // data1 ning maydonlarini aniqlash
        if (isset($data1->ilmiy_jurnal_nomi)) {
            $journal1 = $data1->ilmiy_jurnal_nomi ?? '';
            $article1 = $data1->ilmiy_maqola_nomi ?? '';
            $year1 = $data1->nashr_yili_betlari ?? '';
        } else {
            $journal1 = $data1->konfrrensiya_nomi ?? '';
            $article1 = $data1->maqola_nomi ?? '';
            $year1 = $data1->Nashr_yili_betlari ?? '';
        }
        
        $journal2 = '';
        $article2 = '';
        $year2 = '';
        
        // data2 ning maydonlarini aniqlash
        if (isset($data2->ilmiy_jurnal_nomi)) {
            $journal2 = $data2->ilmiy_jurnal_nomi ?? '';
            $article2 = $data2->ilmiy_maqola_nomi ?? '';
            $year2 = $data2->nashr_yili_betlari ?? '';
        } else {
            $journal2 = $data2->konfrrensiya_nomi ?? '';
            $article2 = $data2->maqola_nomi ?? '';
            $year2 = $data2->Nashr_yili_betlari ?? '';
        }

        $journalSimilarity = $this->calculateSimilarity($journal1, $journal2);
        $articleSimilarity = $this->calculateSimilarity($article1, $article2);
        $yearSimilarity = $this->calculateSimilarity($this->normalizeYear($year1), $this->normalizeYear($year2));

        return [
            'journal_similarity' => round($journalSimilarity * 100, 1),
            'article_similarity' => round($articleSimilarity * 100, 1),
            'year_similarity' => round($yearSimilarity * 100, 1),
            'year_match' => $yearSimilarity > 0.9,
            'year1' => $this->normalizeYear($year1),
            'year2' => $this->normalizeYear($year2)
        ];
    }
}
