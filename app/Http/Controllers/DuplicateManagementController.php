<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointUserDeportament;
use App\Models\DepartPoints;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DuplicateManagementController extends Controller
{
    /**
     * Dубликатларни бошқарув саҳифаси
     */
    public function index()
    {
        $table11Duplicates = $this->findTable11Duplicates();
        $table20Duplicates = $this->findTable20Duplicates();
        
        // Тузатилган дубликатлар
        $table11FixedDuplicates = $this->findTable11FixedDuplicates();
        $table20FixedDuplicates = $this->findTable20FixedDuplicates();
        
        $recentLogs = $this->getRecentLogs();

        return view('dashboard.duplicate-management', compact(
            'table11Duplicates',
            'table20Duplicates',
            'table11FixedDuplicates',
            'table20FixedDuplicates',
            'recentLogs'
        ));
    }

    /**
     * Table 11 dublikatlarini topish (faqat aktiv) - 70% o'xshashlik
     */
    private function findTable11Duplicates()
    {
        $duplicates = [];
        
        // Barcha Table 11 turlaridagi ma'lumotlarni olish (faqat point > 0)
        $table11Records = PointUserDeportament::where(function ($query) {
            $query->whereNotNull('table_11_1_id')
                  ->orWhereNotNull('table_11_2_id')
                  ->orWhereNotNull('table_11_3_id');
        })
        ->where('status', 1) // Фақат тасдиқланган маълумотлар
        ->where('point', '>', 0) // Фақат актив баллар
        ->with(['employee'])
        ->get();

        foreach ($table11Records as $record) {
            $currentTableType = $this->getRecordTableType($record);
            $currentRelatedData = $this->getRelatedData($record, $currentTableType);

            if (!$currentRelatedData) continue;

            // Шу фойдаланувчининг бошқа Table 11 маълумотлари (фақат point > 0)
            $otherRecords = $table11Records->where('user_id', $record->user_id)
                                         ->where('id', '!=', $record->id)
                                         ->where('year', $record->year)
                                         ->where('point', '>', 0);

            foreach ($otherRecords as $otherRecord) {
                $otherTableType = $this->getRecordTableType($otherRecord);
                $otherRelatedData = $this->getRelatedData($otherRecord, $otherTableType);

                if (!$otherRelatedData || $currentTableType === $otherTableType) continue;

                // Ўхшашлик баллини ҳисоблаш
                $similarityScore = $this->calculateTable11Similarity($currentRelatedData, $otherRelatedData);
                
                // Агар ўхшашлик 70% дан кўп бўлса
                if ($similarityScore >= 0.7) {
                    $duplicateKey = $record->user_id . '_' . $record->year;
                    
                    if (!isset($duplicates[$duplicateKey])) {
                        $duplicates[$duplicateKey] = [
                            'user_id' => $record->user_id,
                            'employee_name' => $record->employee->FullName ?? 'Номаълум',
                            'year' => $record->year,
                            'records' => [],
                            'total_points' => 0,
                            'similarity_pairs' => []
                        ];
                    }

                    // Агар бу ёзув аллақачон қўшилмаган бўлса
                    $recordExists = collect($duplicates[$duplicateKey]['records'])->contains('id', $record->id);
                    if (!$recordExists) {
                        $duplicates[$duplicateKey]['records'][] = [
                            'id' => $record->id,
                            'table_type' => $currentTableType,
                            'point' => $record->point,
                            'jurnal_nomi' => $currentRelatedData->jurnal_nomi ?? '',
                            'maqola_nomi' => $currentRelatedData->maqola_nomi ?? '',
                            'nashr_yili' => $currentRelatedData->nashr_yili ?? '',
                            'created_at' => $record->created_at
                        ];
                        $duplicates[$duplicateKey]['total_points'] += $record->point;
                    }

                    // Бошқа ёзувни ҳам қўшиш
                    $otherRecordExists = collect($duplicates[$duplicateKey]['records'])->contains('id', $otherRecord->id);
                    if (!$otherRecordExists) {
                        $duplicates[$duplicateKey]['records'][] = [
                            'id' => $otherRecord->id,
                            'table_type' => $otherTableType,
                            'point' => $otherRecord->point,
                            'jurnal_nomi' => $otherRelatedData->jurnal_nomi ?? '',
                            'maqola_nomi' => $otherRelatedData->maqola_nomi ?? '',
                            'nashr_yili' => $otherRelatedData->nashr_yili ?? '',
                            'created_at' => $otherRecord->created_at
                        ];
                        $duplicates[$duplicateKey]['total_points'] += $otherRecord->point;
                    }
                    
                    // Ўхшашлик жуфтини сақлаш
                    $pairKey = min($record->id, $otherRecord->id) . '_' . max($record->id, $otherRecord->id);
                    if (!isset($duplicates[$duplicateKey]['similarity_pairs'][$pairKey])) {
                        $duplicates[$duplicateKey]['similarity_pairs'][$pairKey] = [
                            'record1_id' => $record->id,
                            'record2_id' => $otherRecord->id,
                            'similarity_score' => $similarityScore,
                            'details' => $this->getTable11SimilarityDetails($currentRelatedData, $otherRelatedData)
                        ];
                    }
                }
            }
        }

        // Ёзувларни санаси бўйича тартиблаш (янгиси биринчи)
        foreach ($duplicates as &$duplicate) {
            usort($duplicate['records'], function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }

        return array_values($duplicates);
    }

    /**
     * Table 20 дубликатларини топиш (фақат актив) - 70% ўхшашлик
     */
    private function findTable20Duplicates()
    {
        $duplicates = [];
        
        // Барча Table 20 турларидаги маълумотларни олиш (фақат point > 0)
        $table20Records = PointUserDeportament::where(function ($query) {
            $query->whereNotNull('table_20_1_id')
                  ->orWhereNotNull('table_20_2_id')
                  ->orWhereNotNull('table_20_3_id');
        })
        ->where('status', 1) // Фақат тасдиқланган маълумотлар
        ->where('point', '>', 0) // Фақат актив баллар
        ->with(['employee'])
        ->get();

        foreach ($table20Records as $record) {
            $currentTableType = $this->getRecordTableType($record);
            $currentRelatedData = $this->getRelatedData($record, $currentTableType);

            if (!$currentRelatedData) continue;

            // Шу фойдаланувчининг бошқа Table 20 маълумотлари (фақат point > 0)
            $otherRecords = $table20Records->where('user_id', $record->user_id)
                                         ->where('id', '!=', $record->id)
                                         ->where('year', $record->year)
                                         ->where('point', '>', 0);

            foreach ($otherRecords as $otherRecord) {
                $otherTableType = $this->getRecordTableType($otherRecord);
                $otherRelatedData = $this->getRelatedData($otherRecord, $otherTableType);

                if (!$otherRelatedData || $currentTableType === $otherTableType) continue;

                // Ўхшашлик баллини ҳисоблаш
                $similarityScore = $this->calculateTable20Similarity($currentRelatedData, $otherRelatedData);
                
                // Агар ўхшашлик 70% дан кўп бўлса
                if ($similarityScore >= 0.7) {
                    $duplicateKey = $record->user_id . '_' . $record->year;
                    
                    if (!isset($duplicates[$duplicateKey])) {
                        $duplicates[$duplicateKey] = [
                            'user_id' => $record->user_id,
                            'employee_name' => $record->employee->FullName ?? 'Номаълум',
                            'year' => $record->year,
                            'records' => [],
                            'total_points' => 0,
                            'similarity_pairs' => []
                        ];
                    }

                    // Агар бу ёзув аллақачон қўшилмаган бўлса
                    $recordExists = collect($duplicates[$duplicateKey]['records'])->contains('id', $record->id);
                    if (!$recordExists) {
                        $duplicates[$duplicateKey]['records'][] = [
                            'id' => $record->id,
                            'table_type' => $currentTableType,
                            'point' => $record->point,
                            'jurnal_nomi' => $currentRelatedData->jurnal_nomi ?? '',
                            'mualliflar_soni' => $currentRelatedData->mualliflar_soni ?? '',
                            'created_at' => $record->created_at
                        ];
                        $duplicates[$duplicateKey]['total_points'] += $record->point;
                    }

                    // Бошқа ёзувни ҳам қўшиш
                    $otherRecordExists = collect($duplicates[$duplicateKey]['records'])->contains('id', $otherRecord->id);
                    if (!$otherRecordExists) {
                        $duplicates[$duplicateKey]['records'][] = [
                            'id' => $otherRecord->id,
                            'table_type' => $otherTableType,
                            'point' => $otherRecord->point,
                            'jurnal_nomi' => $otherRelatedData->jurnal_nomi ?? '',
                            'mualliflar_soni' => $otherRelatedData->mualliflar_soni ?? '',
                            'created_at' => $otherRecord->created_at
                        ];
                        $duplicates[$duplicateKey]['total_points'] += $otherRecord->point;
                    }
                    
                    // Ўхшашлик жуфтини сақлаш
                    $pairKey = min($record->id, $otherRecord->id) . '_' . max($record->id, $otherRecord->id);
                    if (!isset($duplicates[$duplicateKey]['similarity_pairs'][$pairKey])) {
                        $duplicates[$duplicateKey]['similarity_pairs'][$pairKey] = [
                            'record1_id' => $record->id,
                            'record2_id' => $otherRecord->id,
                            'similarity_score' => $similarityScore,
                            'details' => $this->getTable20SimilarityDetails($currentRelatedData, $otherRelatedData)
                        ];
                    }
                }
            }
        }

        // Ёзувларни санаси бўйича тартиблаш (янгиси биринчи)
        foreach ($duplicates as &$duplicate) {
            usort($duplicate['records'], function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }

        return array_values($duplicates);
    }

    /**
     * Table 11 dublikatlarini avtomatik tuzatish - eng katta ballni saqlab qolish
     */
    public function fixTable11Duplicates(Request $request)
    {
        try {
            $duplicates = $this->findTable11Duplicates();
            $fixedCount = 0;
            $fixedRecords = [];

            DB::beginTransaction();

            foreach ($duplicates as $duplicate) {
                if (count($duplicate['records']) >= 2) {
                    // Eng katta ballga ega yozuvni topish
                    $sortedRecords = collect($duplicate['records'])->sortByDesc('point');
                    $maxPoint = $sortedRecords->first()['point'];
                    
                    // Eng katta ball bilan barcha yozuvlarni saqlab qolish
                    $recordsToKeep = $sortedRecords->where('point', $maxPoint);
                    $recordsToFix = $sortedRecords->where('point', '<', $maxPoint);

                    // Agar bir nechta eng katta ball bo'lsa, eng yuqori prioritetli turni saqlab qolish
                    if ($recordsToKeep->count() > 1) {
                        $priorityOrder = ['Table_11_1' => 1, 'Table_11_2' => 2, 'Table_11_3' => 3];
                        
                        $recordsToKeepSorted = $recordsToKeep->sort(function($a, $b) use ($priorityOrder) {
                            $priorityA = $priorityOrder[$a['table_type']] ?? 999;
                            $priorityB = $priorityOrder[$b['table_type']] ?? 999;
                            
                            if ($priorityA != $priorityB) {
                                return $priorityA - $priorityB;
                            }
                            
                            return strtotime($b['created_at']) - strtotime($a['created_at']);
                        });
                        
                        // Birinchisidan boshqa barcha yozuvlarni tuzatish ro'yxatiga qo'shish
                        $recordsToFix = $recordsToFix->merge($recordsToKeepSorted->skip(1));
                    }

                    // Tuzatish jarayoni
                    foreach ($recordsToFix as $record) {
                        $pointRecord = PointUserDeportament::find($record['id']);
                        if ($pointRecord && $pointRecord->point > 0) {
                            $pointRecord->update(['point' => 0]);
                            
                            // Kafedra balini yaratish (0.10)
                            DepartPoints::updateOrCreate(
                                ['point_user_deport_id' => $record['id']],
                                [
                                    'point' => 0.10,
                                    'status' => 1
                                ]
                            );
                            
                            $fixedCount++;
                            
                            $fixedRecords[] = [
                                'id' => $record['id'],
                                'table_type' => $record['table_type'],
                                'old_point' => $record['point'],
                                'new_point' => 0,
                                'department_point_added' => 0.10,
                                'user_id' => $duplicate['user_id'],
                                'year' => $duplicate['year']
                            ];
                        }
                    }
                }
            }

            // Log yozish
            $this->writeLog('Table 11 avtomatik tuzatish (eng katta ball saqlanadi)', [
                'fixed_count' => $fixedCount,
                'fixed_records' => $fixedRecords,
                'timestamp' => now()->toDateTimeString()
            ]);

            DB::commit();

            return redirect()->back()->with('success', 
                "Table 11 dublikatlari muvaffaqiyatli tuzatildi! {$fixedCount} ta yozuv 0 ballga o'tkazildi va har biriga 0.10 kafedra bali berildi."
            );

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 
                'Xatolik yuz berdi: ' . $e->getMessage()
            );
        }
    }

    /**
     * Table 20 dublikatlarini avtomatik tuzatish - eng katta ballni saqlab qolish
     */
    public function fixTable20Duplicates(Request $request)
    {
        try {
            $duplicates = $this->findTable20Duplicates();
            $fixedCount = 0;
            $fixedRecords = [];

            DB::beginTransaction();

            foreach ($duplicates as $duplicate) {
                if (count($duplicate['records']) >= 2) {
                    // Eng katta ballga ega yozuvni topish
                    $sortedRecords = collect($duplicate['records'])->sortByDesc('point');
                    $maxPoint = $sortedRecords->first()['point'];
                    
                    // Eng katta ball bilan barcha yozuvlarni saqlab qolish
                    $recordsToKeep = $sortedRecords->where('point', $maxPoint);
                    $recordsToFix = $sortedRecords->where('point', '<', $maxPoint);

                    // Agar bir nechta eng katta ball bo'lsa, eng yuqori prioritetli turni saqlab qolish
                    if ($recordsToKeep->count() > 1) {
                        $priorityOrder = ['Table_20_1' => 1, 'Table_20_2' => 2, 'Table_20_3' => 3];
                        
                        $recordsToKeepSorted = $recordsToKeep->sort(function($a, $b) use ($priorityOrder) {
                            $priorityA = $priorityOrder[$a['table_type']] ?? 999;
                            $priorityB = $priorityOrder[$b['table_type']] ?? 999;
                            
                            if ($priorityA != $priorityB) {
                                return $priorityA - $priorityB;
                            }
                            
                            return strtotime($b['created_at']) - strtotime($a['created_at']);
                        });
                        
                        // Birinchisidan boshqa barcha yozuvlarni tuzatish ro'yxatiga qo'shish
                        $recordsToFix = $recordsToFix->merge($recordsToKeepSorted->skip(1));
                    }

                    // Tuzatish jarayoni
                    foreach ($recordsToFix as $record) {
                        $pointRecord = PointUserDeportament::find($record['id']);
                        if ($pointRecord && $pointRecord->point > 0) {
                            $pointRecord->update(['point' => 0]);
                            
                            // Kafedra balini yaratish (0.10)
                            DepartPoints::updateOrCreate(
                                ['point_user_deport_id' => $record['id']],
                                [
                                    'point' => 0.10,
                                    'status' => 1
                                ]
                            );
                            
                            $fixedCount++;
                            
                            $fixedRecords[] = [
                                'id' => $record['id'],
                                'table_type' => $record['table_type'],
                                'old_point' => $record['point'],
                                'new_point' => 0,
                                'department_point_added' => 0.10,
                                'user_id' => $duplicate['user_id'],
                                'year' => $duplicate['year']
                            ];
                        }
                    }
                }
            }

            // Log yozish
            $this->writeLog('Table 20 avtomatik tuzatish (eng katta ball saqlanadi)', [
                'fixed_count' => $fixedCount,
                'fixed_records' => $fixedRecords,
                'timestamp' => now()->toDateTimeString()
            ]);

            DB::commit();

            return redirect()->back()->with('success', 
                "Table 20 dublikatlari muvaffaqiyatli tuzatildi! {$fixedCount} ta yozuv 0 ballga o'tkazildi va har biriga 0.10 kafedra bali berildi."
            );

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 
                'Xatolik yuz berdi: ' . $e->getMessage()
            );
        }
    }

    /**
     * Бир хил ёзувни тузатиш
     */
    public function fixSingleRecord(Request $request)
    {
        try {
            DB::beginTransaction();

            // Parametrlarni olish
            $userId = $request->user_id;
            $year = $request->year;
            $recordIds = $request->record_ids;

            if (!$userId || !$year || !$recordIds) {
                throw new \Exception('Kerakli parametrlar kiritilmagan');
            }

            // Record ID larni massivga aylantirish
            $recordIdArray = explode(',', $recordIds);
            
            if (empty($recordIdArray)) {
                throw new \Exception('Hech qanday yozuv ID si topilmadi');
            }

            $fixedCount = 0;
            $maxPoint = 0;
            $maxPointRecord = null;

            // Barcha yozuvlarni olish va eng katta ballini topish
            $records = PointUserDeportament::whereIn('id', $recordIdArray)->get();
            
            if ($records->isEmpty()) {
                throw new \Exception('Belgilangan yozuvlar topilmadi');
            }

            foreach ($records as $record) {
                if ($record->point > $maxPoint) {
                    $maxPoint = $record->point;
                    $maxPointRecord = $record;
                }
            }

            // Eng katta ballli yozuvdan boshqa barchasini 0 ga o'tkazish
            foreach ($records as $record) {
                if ($record->id !== $maxPointRecord->id) {
                    $record->point = 0.00;
                    $record->save();
                    
                    // Kafedra bali yaratish
                    DepartPoints::updateOrCreate(
                        ['point_user_deport_id' => $record->id],
                        [
                            'point' => 0.10,
                            'status' => 1
                        ]
                    );
                    
                    $fixedCount++;
                }
            }

            $this->writeLog('fix_single_record', [
                'user_id' => $userId,
                'year' => $year,
                'record_ids' => $recordIds,
                'fixed_count' => $fixedCount,
                'max_point_record_id' => $maxPointRecord->id,
                'max_point' => $maxPoint
            ]);

            DB::commit();

            $message = "Muvaffaqiyatli tuzatildi! {$fixedCount} ta yozuv 0 ballga o'tkazildi, eng katta ballli yozuv ({$maxPoint} ball) saqlandi.";
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Хатолик юз берди: ' . $e->getMessage());
        }
    }

    /**
     * Ёзувнинг жадвал турини аниқлаш
     */
    private function getRecordTableType($record)
    {
        if (!is_null($record->table_11_1_id)) return 'table_11_1';
        if (!is_null($record->table_11_2_id)) return 'table_11_2';
        if (!is_null($record->table_11_3_id)) return 'table_11_3';
        if (!is_null($record->table_20_1_id)) return 'table_20_1';
        if (!is_null($record->table_20_2_id)) return 'table_20_2';
        if (!is_null($record->table_20_3_id)) return 'table_20_3';
        
        return null;
    }

    /**
     * Лог ёзиш
     */
    private function writeLog($action, $data)
    {
        $logEntry = [
            'timestamp' => Carbon::now()->toISOString(),
            'action' => $action,
            'data' => $data,
            'admin_id' => auth()->id()
        ];

        $logFileName = 'logs/duplicate_management_' . Carbon::now()->format('Y-m-d') . '.log';
        Storage::disk('local')->append($logFileName, json_encode($logEntry));
    }

    /**
     * Сўнгги логларни олиш (сўнги ўзгаришлар биринчи)
     */
    private function getRecentLogs($limit = 10)
    {
        $logs = [];
        
        // Бугунги ва кечаги логларни текшириш (сўнгисидан бошлаб)
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $logFileName = 'logs/duplicate_management_' . $date . '.log';
            
            if (Storage::disk('local')->exists($logFileName)) {
                $logContent = Storage::disk('local')->get($logFileName);
                $logLines = array_filter(explode("\n", $logContent));
                
                // Логларни тескари тартибда (сўнгиси биринчи) олиш
                foreach (array_reverse($logLines) as $line) {
                    if (count($logs) >= $limit) break 2;
                    
                    $logEntry = json_decode($line, true);
                    if ($logEntry) {
                        // Timestamp бўйича сорт қилиш учун таймстампни қўшамиз
                        $logEntry['sort_timestamp'] = $logEntry['timestamp'] ?? '';
                        $logs[] = $logEntry;
                    }
                }
            }
        }
        
        // Логларни timestamp бўйича тескари тартибда сорт қиламиз (сўнгиси биринчи)
        usort($logs, function($a, $b) {
            return strcmp($b['sort_timestamp'], $a['sort_timestamp']);
        });
        
        return array_slice($logs, 0, $limit);
    }

    /**
     * Боғланган маълумотларни олиш
     */
    private function getRelatedData($record, $tableType)
    {
        if (!$tableType) return null;

        $modelClass = $this->getModelClassForRelation($tableType);
        if (!$modelClass) return null;

        $foreignKey = $tableType . '_id';
        return $modelClass::find($record->{$foreignKey});
    }

    /**
     * Модел класс номини аниқлаш
     */
    private function getModelClassForRelation($relation)
    {
        return "\\App\\Models\\Tables\\" . ucfirst($relation) . "_";
    }

    /**
     * Table_11_* маълумотларини таққослаш
     */
    private function compareTable11Data($data1, $data2)
    {
        // Асосий майдонларни нормаллаштириш ва таққослаш
        $journal1 = $this->normalizeText($data1->jurnal_nomi ?? '');
        $journal2 = $this->normalizeText($data2->jurnal_nomi ?? '');
        
        $article1 = $this->normalizeText($data1->maqola_nomi ?? '');
        $article2 = $this->normalizeText($data2->maqola_nomi ?? '');
        
        $year1 = $this->normalizeYear($data1->nashr_yili ?? '');
        $year2 = $this->normalizeYear($data2->nashr_yili ?? '');

        // Ўхшашлик фоизини ҳисоблаш
        $journalSimilarity = $this->calculateSimilarity($journal1, $journal2);
        $articleSimilarity = $this->calculateSimilarity($article1, $article2);
        $yearMatch = ($year1 === $year2 && !empty($year1)) ? 1.0 : 0.0;

        // Агар журнал ва мақола номи 70% дан кўп ўхшаш бўлса ва йил бир хил бўлса
        return ($journalSimilarity >= 0.7 && $articleSimilarity >= 0.7 && $yearMatch > 0);
    }

    /**
     * Table_20_* маълумотларини таққослаш
     */
    private function compareTable20Data($data1, $data2)
    {
        // Асосий майдонларни нормаллаштириш ва таққослаш
        $journal1 = $this->normalizeText($data1->jurnal_nomi ?? '');
        $journal2 = $this->normalizeText($data2->jurnal_nomi ?? '');
        
        $authors1 = $this->normalizeText($data1->mualliflar_soni ?? '');
        $authors2 = $this->normalizeText($data2->mualliflar_soni ?? '');

        // Ўхшашлик фоизини ҳисоблаш
        $journalSimilarity = $this->calculateSimilarity($journal1, $journal2);
        $authorsSimilarity = $this->calculateSimilarity($authors1, $authors2);

        // Агар журнал номи ва муаллифлар сони 70% дан кўп ўхшаш бўлса
        return ($journalSimilarity >= 0.7 && $authorsSimilarity >= 0.7);
    }

    /**
     * Матнни нормаллаш
     */
    private function normalizeText($text)
    {
        // Кичик ҳарфга ўтказиш
        $text = mb_strtolower($text, 'UTF-8');
        
        // Ортиқча бўш жойларни олиб ташлаш
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Тиниш белгиларини олиб ташлаш
        $text = preg_replace('/[.,;:!?"`\'"«»„"]/', '', $text);
        
        return $text;
    }

    /**
     * Йилни нормаллаш
     */
    private function normalizeYear($yearText)
    {
        // 4 хонали рақамни қидириш
        preg_match('/\b(20\d{2}|19\d{2})\b/', $yearText, $matches);
        return $matches[0] ?? '';
    }

    /**
     * Матнлар ўртасидаги ўхшашлик фоизини ҳисоблаш (Levenshtein distance)
     */
    private function calculateSimilarity($str1, $str2)
    {
        // Матнларни нормаллаштириш
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

        // Агар матнлар айнан бир хил бўлса
        if ($str1 === $str2) {
            return 1.0;
        }

        // Levenshtein масофасини ҳисоблаш
        $distance = levenshtein($str1, $str2);
        
        // Агар distance хато қайтарса (масалан, жуда узун матнлар учун)
        if ($distance === -1) {
            // Oddiy таққослаш
            return ($str1 === $str2) ? 1.0 : 0.0;
        }
        
        // Ўхшашлик фоизини қайтариш
        return max(0, 1 - ($distance / $maxLen));
    }

    /**
     * Ўхшашлик баллини ҳисоблаш
     */
    private function calculateTable11Similarity($data1, $data2)
    {
        // Асосий майдонларни таққослаш
        $journalSimilarity = $this->calculateSimilarity($data1->jurnal_nomi ?? '', $data2->jurnal_nomi ?? '');
        $articleSimilarity = $this->calculateSimilarity($data1->maqola_nomi ?? '', $data2->maqola_nomi ?? '');
        $yearSimilarity = $this->calculateSimilarity($this->normalizeYear($data1->nashr_yili ?? ''), $this->normalizeYear($data2->nashr_yili ?? ''));

        // Ўхшашлик фоизини ҳисоблаш
        $similarityScore = ($journalSimilarity + $articleSimilarity + $yearSimilarity) / 3.0;
        
        return $similarityScore;
    }

    /**
     * Ўхшашлик баллини ҳисоблаш
     */
    private function calculateTable20Similarity($data1, $data2)
    {
        // Асосий майдонларни таққослаш
        $journalSimilarity = $this->calculateSimilarity($data1->jurnal_nomi ?? '', $data2->jurnal_nomi ?? '');
        $authorsSimilarity = $this->calculateSimilarity($data1->mualliflar_soni ?? '', $data2->mualliflar_soni ?? '');

        // Ўхшашлик фоизини ҳисоблаш
        $similarityScore = ($journalSimilarity + $authorsSimilarity) / 2.0;
        
        return $similarityScore;
    }

    /**
     * Ўхшашлик жуфтини сақлаш
     */
    private function getTable11SimilarityDetails($data1, $data2)
    {
        return [
            'journal_similarity' => $this->calculateSimilarity($data1->jurnal_nomi ?? '', $data2->jurnal_nomi ?? ''),
            'article_similarity' => $this->calculateSimilarity($data1->maqola_nomi ?? '', $data2->maqola_nomi ?? ''),
            'year_similarity' => $this->calculateSimilarity($this->normalizeYear($data1->nashr_yili ?? ''), $this->normalizeYear($data2->nashr_yili ?? '')),
        ];
    }

    /**
     * Ўхшашлик жуфтини сақлаш
     */
    private function getTable20SimilarityDetails($data1, $data2)
    {
        return [
            'journal_similarity' => $this->calculateSimilarity($data1->jurnal_nomi ?? '', $data2->jurnal_nomi ?? ''),
            'authors_similarity' => $this->calculateSimilarity($data1->mualliflar_soni ?? '', $data2->mualliflar_soni ?? ''),
        ];
    }

    /**
     * Table 11 тузатилган дубликатларини топиш - Аниқ номлар бўйича
     */
    private function findTable11FixedDuplicates()
    {
        $fixedDuplicates = [];
        
        // Барча Table 11 турларидаги маълумотларни олиш (ҳам point > 0, ҳам point = 0)
        $allTable11Records = PointUserDeportament::where(function ($query) {
            $query->whereNotNull('table_11_1_id')
                  ->orWhereNotNull('table_11_2_id')
                  ->orWhereNotNull('table_11_3_id');
        })
        ->where('status', 1)
        ->with(['employee'])
        ->get();

        // Фойдаланувчи ва йил бўйича гуруҳлаш
        $groupedByUserYear = $allTable11Records->groupBy(function($record) {
            return $record->user_id . '_' . $record->year;
        });

        foreach ($groupedByUserYear as $key => $records) {
            $activeRecords = $records->where('point', '>', 0);
            $fixedRecords = $records->where('point', '=', 0);

            // Агар гуруҳда point = 0 бўлган ёзувлар бор ва улар ўхшаш бўлса
            if ($fixedRecords->count() > 0 && $activeRecords->count() >= 1) {
                
                // 70% ўхшашлик алгоритми билан мос келишни текшириш
                $similarityMatches = [];
                
                foreach ($activeRecords as $activeRecord) {
                    $activeTableType = $this->getRecordTableType($activeRecord);
                    $activeData = $this->getRelatedData($activeRecord, $activeTableType);
                    
                    if (!$activeData) continue;
                    
                    foreach ($fixedRecords as $fixedRecord) {
                        $fixedTableType = $this->getRecordTableType($fixedRecord);
                        $fixedData = $this->getRelatedData($fixedRecord, $fixedTableType);
                        
                        if (!$fixedData) continue;
                        if ($activeTableType === $fixedTableType) continue;
                        
                        // 70% ўхшашлик баллини ҳисоблаш
                        $similarityScore = $this->calculateTable11Similarity($activeData, $fixedData);
                        
                        // Агар 70% дан кўп ўхшаш бўлса
                        if ($similarityScore >= 0.7) {
                            $matchKey = $activeData->maqola_nomi ?? 'Unknown';
                            
                            if (!isset($similarityMatches[$matchKey])) {
                                $similarityMatches[$matchKey] = [
                                    'active' => [],
                                    'fixed' => [],
                                    'similarity_score' => $similarityScore
                                ];
                            }
                            
                            $similarityMatches[$matchKey]['active'][] = $activeRecord;
                            $similarityMatches[$matchKey]['fixed'][] = $fixedRecord;
                            $similarityMatches[$matchKey]['similarity_score'] = max($similarityMatches[$matchKey]['similarity_score'], $similarityScore);
                        }
                    }
                }

                // Агар ўхшашлик топилган бўлса
                if (!empty($similarityMatches)) {
                    $userYearParts = explode('_', $key);
                    $userId = $userYearParts[0];
                    $year = $userYearParts[1];
                    
                    $firstRecord = $records->first();
                    
                    foreach ($similarityMatches as $title => $matches) {
                        $fixedDuplicates[] = [
                            'user_id' => $userId,
                            'employee_name' => $firstRecord->employee->FullName ?? 'Номаълум',
                            'year' => $year,
                            'article_title' => $title,
                            'active_records' => collect($matches['active'])->unique('id')->map(function($record) {
                                $tableType = $this->getRecordTableType($record);
                                $relatedData = $this->getRelatedData($record, $tableType);
                                return [
                                    'id' => $record->id,
                                    'table_type' => $tableType,
                                    'point' => $record->point,
                                    'jurnal_nomi' => $relatedData->jurnal_nomi ?? '',
                                    'maqola_nomi' => $relatedData->maqola_nomi ?? '',
                                    'nashr_yili' => $relatedData->nashr_yili ?? '',
                                    'created_at' => $record->created_at
                                ];
                            })->values()->toArray(),
                            'fixed_records' => collect($matches['fixed'])->unique('id')->map(function($record) {
                                $tableType = $this->getRecordTableType($record);
                                $relatedData = $this->getRelatedData($record, $tableType);
                                return [
                                    'id' => $record->id,
                                    'table_type' => $tableType,
                                    'point' => $record->point,
                                    'jurnal_nomi' => $relatedData->jurnal_nomi ?? '',
                                    'maqola_nomi' => $relatedData->maqola_nomi ?? '',
                                    'nashr_yili' => $relatedData->nashr_yili ?? '',
                                    'created_at' => $record->created_at
                                ];
                            })->values()->toArray(),
                            'total_active_points' => collect($matches['active'])->unique('id')->sum('point'),
                            'fixed_at' => collect($matches['fixed'])->max('updated_at')
                        ];
                    }
                }
            }
        }

        return $fixedDuplicates;
    }

    /**
     * Table 20 тузатилган дубликатларини топиш - Аниқ номлар бўйича
     */
    private function findTable20FixedDuplicates()
    {
        $fixedDuplicates = [];
        
        // Барча Table 20 турларидаги маълумотларни олиш (ҳам point > 0, ҳам point = 0)
        $allTable20Records = PointUserDeportament::where(function ($query) {
            $query->whereNotNull('table_20_1_id')
                  ->orWhereNotNull('table_20_2_id')
                  ->orWhereNotNull('table_20_3_id');
        })
        ->where('status', 1)
        ->with(['employee'])
        ->get();

        // Фойдаланувчи ва йил бўйича гуруҳлаш
        $groupedByUserYear = $allTable20Records->groupBy(function($record) {
            return $record->user_id . '_' . $record->year;
        });

        foreach ($groupedByUserYear as $key => $records) {
            $activeRecords = $records->where('point', '>', 0);
            $fixedRecords = $records->where('point', '=', 0);

            // Агар гуруҳда point = 0 бўлган ёзувлар бор ва улар ўхшаш бўлса
            if ($fixedRecords->count() > 0 && $activeRecords->count() >= 1) {
                
                // 70% ўхшашлик алгоритми билан мос келишни текшириш
                $similarityMatches = [];
                
                foreach ($activeRecords as $activeRecord) {
                    $activeTableType = $this->getRecordTableType($activeRecord);
                    $activeData = $this->getRelatedData($activeRecord, $activeTableType);
                    
                    if (!$activeData) continue;
                    
                    foreach ($fixedRecords as $fixedRecord) {
                        $fixedTableType = $this->getRecordTableType($fixedRecord);
                        $fixedData = $this->getRelatedData($fixedRecord, $fixedTableType);
                        
                        if (!$fixedData) continue;
                        if ($activeTableType === $fixedTableType) continue;
                        
                        // 70% ўхшашлик баллини ҳисоблаш
                        $similarityScore = $this->calculateTable20Similarity($activeData, $fixedData);
                        
                        // Агар 70% дан кўп ўхшаш бўлса
                        if ($similarityScore >= 0.7) {
                            $matchKey = $activeData->jurnal_nomi ?? 'Unknown';
                            
                            if (!isset($similarityMatches[$matchKey])) {
                                $similarityMatches[$matchKey] = [
                                    'active' => [],
                                    'fixed' => [],
                                    'similarity_score' => $similarityScore
                                ];
                            }
                            
                            $similarityMatches[$matchKey]['active'][] = $activeRecord;
                            $similarityMatches[$matchKey]['fixed'][] = $fixedRecord;
                            $similarityMatches[$matchKey]['similarity_score'] = max($similarityMatches[$matchKey]['similarity_score'], $similarityScore);
                        }
                    }
                }

                // Агар ўхшашлик топилган бўлса
                if (!empty($similarityMatches)) {
                    $userYearParts = explode('_', $key);
                    $userId = $userYearParts[0];
                    $year = $userYearParts[1];
                    
                    $firstRecord = $records->first();
                    
                    foreach ($similarityMatches as $journal => $matches) {
                        $fixedDuplicates[] = [
                            'user_id' => $userId,
                            'employee_name' => $firstRecord->employee->FullName ?? 'Номаълум',
                            'year' => $year,
                            'journal_name' => $journal,
                            'active_records' => collect($matches['active'])->unique('id')->map(function($record) {
                                $tableType = $this->getRecordTableType($record);
                                $relatedData = $this->getRelatedData($record, $tableType);
                                return [
                                    'id' => $record->id,
                                    'table_type' => $tableType,
                                    'point' => $record->point,
                                    'jurnal_nomi' => $relatedData->jurnal_nomi ?? '',
                                    'mualliflar_soni' => $relatedData->mualliflar_soni ?? '',
                                    'created_at' => $record->created_at
                                ];
                            })->values()->toArray(),
                            'fixed_records' => collect($matches['fixed'])->unique('id')->map(function($record) {
                                $tableType = $this->getRecordTableType($record);
                                $relatedData = $this->getRelatedData($record, $tableType);
                                return [
                                    'id' => $record->id,
                                    'table_type' => $tableType,
                                    'point' => $record->point,
                                    'jurnal_nomi' => $relatedData->jurnal_nomi ?? '',
                                    'mualliflar_soni' => $relatedData->mualliflar_soni ?? '',
                                    'created_at' => $record->created_at
                                ];
                            })->values()->toArray(),
                            'total_active_points' => collect($matches['active'])->unique('id')->sum('point'),
                            'fixed_at' => collect($matches['fixed'])->max('updated_at')
                        ];
                    }
                }
            }
        }

        return $fixedDuplicates;
    }
} 