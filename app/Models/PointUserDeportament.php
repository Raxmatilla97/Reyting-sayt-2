<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class PointUserDeportament extends Model
{
    use HasFactory;

    // Ruxsat etilgan columnlar ro'yxati - migratsiyaga moslashtirilgan
    protected $fillable = [
        'user_id',
        'table_2_id',
        'table_3_id',
        'table_4_id',
        'table_5_id',
        'table_6_id',
        'table_7_id',
        'table_8_1_id',
        'table_8_2_id',
        'table_9_1_id',
        'table_9_2_id',
        'table_10_1_id',
        'table_10_2_id',
        'table_10_3_id',
        'table_11_1_id',
        'table_11_2_id',
        'table_11_3_id',
        'table_12_id',
        'table_13_id',
        'table_14_1_id',
        'table_14_2_id',
        'table_14_3_id',
        'table_15_1_id',
        'table_15_2_id',
        'table_16_id',
        'table_17_1_id',
        'table_17_2_id',
        'table_18_1_id',
        'table_18_2_id',
        'table_18_3_id',
        'table_18_3_a_id',
        'table_19_id',
        'table_20_1_id',
        'table_20_2_id',
        'table_20_3_id',
        'table_21_1_id',
        'table_21_2_id',
        'table_22_id',
        'table_23_id',
        'table_24_id',
        'departament_info',
        'status',
        'departament_id',
        'year',
        'arizaga_javob',
        'is_admin',
        'point',
        'is_active'
    ];

    // Relationlarni nomli ro'yxati - migratsiyaga moslashtirilgan
    protected $relationships = [
        'table_2',
        'table_3',
        'table_4',
        'table_5',
        'table_6',
        'table_7',
        'table_8_1',
        'table_8_2',
        'table_9_1',
        'table_9_2',
        'table_10_1',
        'table_10_2',
        'table_10_3',
        'table_11_1',
        'table_11_2',
        'table_11_3',
        'table_12',
        'table_13',
        'table_14_1',
        'table_14_2',
        'table_14_3',
        'table_15_1',
        'table_15_2',
        'table_16',
        'table_17_1',
        'table_17_2',
        'table_18_1',
        'table_18_2',
        'table_18_3',
        'table_18_3_a',
        'table_19',
        'table_20_1',
        'table_20_2',
        'table_20_3',
        'table_21_1',
        'table_21_2',
        'table_22',
        'table_23',
        'table_24'
    ];

    // Bu modeldan biron item o'chirilganda unga tegishli bosha tabledagi item ham o'chadi!
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pointUserDeportament) {
            foreach ($pointUserDeportament->getRelationships() as $relationship) {
                $relatedModels = $pointUserDeportament->$relationship;
                if ($relatedModels instanceof Model) {
                    $relatedModels->delete();
                } elseif ($relatedModels instanceof \Illuminate\Database\Eloquent\Collection) {
                    foreach ($relatedModels as $model) {
                        $model->delete();
                    }
                }
            }
        });

        // Table 11 dublikatlar tizimi: agar bir Table 11 turiga ball berilsa, qolganlarini 0 ga o'tkazish
        static::updated(function ($pointUserDeportament) {
            // Faqat ball o'zgarganda ishlaydi
            if ($pointUserDeportament->isDirty('point') && $pointUserDeportament->point > 0) {
                
                // Agar ma'lumotga point berilgan bo'lsa va oldin kafedra bali mavjud bo'lsa, uni o'chirish
                \App\Models\DepartPoints::where('point_user_deport_id', $pointUserDeportament->id)->delete();
                
                Log::info('Kafedra bali o\'chirildi (o\'qituvchi bali berilganligi sababli - model event)', [
                    'point_user_deport_id' => $pointUserDeportament->id,
                    'user_id' => $pointUserDeportament->user_id,
                    'teacher_point_given' => $pointUserDeportament->point
                ]);

                // Agar Table 11 turlaridan biri bo'lsa
                $isTable11 = $pointUserDeportament->table_11_1_id || 
                           $pointUserDeportament->table_11_2_id || 
                           $pointUserDeportament->table_11_3_id;
                
                if ($isTable11) {
                    // Current record ma'lumotlarini olish
                    $currentTableType = null;
                    if ($pointUserDeportament->table_11_1_id) $currentTableType = 'table_11_1';
                    elseif ($pointUserDeportament->table_11_2_id) $currentTableType = 'table_11_2';
                    elseif ($pointUserDeportament->table_11_3_id) $currentTableType = 'table_11_3';

                    $currentRelatedData = self::getRelatedDataForModel($pointUserDeportament, $currentTableType);

                    if ($currentRelatedData) {
                        // Shu foydalanuvchining shu yildagi boshqa Table 11 yozuvlarini topish
                        $otherTable11Records = self::where('user_id', $pointUserDeportament->user_id)
                            ->where('year', $pointUserDeportament->year)
                            ->where('id', '!=', $pointUserDeportament->id)
                            ->where('point', '>', 0)
                            ->where(function ($query) {
                                $query->whereNotNull('table_11_1_id')
                                      ->orWhereNotNull('table_11_2_id')
                                      ->orWhereNotNull('table_11_3_id');
                            })
                            ->get();

                        foreach ($otherTable11Records as $otherRecord) {
                            $otherTableType = null;
                            if ($otherRecord->table_11_1_id) $otherTableType = 'table_11_1';
                            elseif ($otherRecord->table_11_2_id) $otherTableType = 'table_11_2';
                            elseif ($otherRecord->table_11_3_id) $otherTableType = 'table_11_3';

                            if ($currentTableType === $otherTableType) continue; // Bir xil tip bo'lsa o'tish

                            // Prioritet tartibini tekshirish
                            $priorityOrder = ['table_11_1' => 1, 'table_11_2' => 2, 'table_11_3' => 3];
                            $currentPriority = $priorityOrder[$currentTableType] ?? 999;
                            $otherPriority = $priorityOrder[$otherTableType] ?? 999;

                            // Faqat past prioritetli tablelarni ta'sir qilish (yuqori prioritetli tablela ta'sir qilmaydi)
                            if ($currentPriority >= $otherPriority) continue;

                            $otherRelatedData = self::getRelatedDataForModel($otherRecord, $otherTableType);

                            if ($otherRelatedData) {
                                // O'xshashlik darajasini hisoblash
                                $similarityScore = self::calculateTable11SimilarityForModel($currentRelatedData, $otherRelatedData);
                                
                                // Agar 70% dan ko'p o'xshash bo'lsa, dublikat sifatida qayta ishlash
                                if ($similarityScore >= 0.7) {
                                    // O'qituvchi balini 0 ga o'tkazish
                                    $otherRecord->update(['point' => 0]);
                                    
                                    // Dublikat uchun avtomatik kafedra bali (0.10) yaratish
                                    \App\Models\DepartPoints::updateOrCreate(
                                        ['point_user_deport_id' => $otherRecord->id],
                                        [
                                            'point' => 0.10,
                                            'status' => 1
                                        ]
                                    );
                                    
                                    Log::info('Table 11 dublikat avtomatik tuzatildi (kafedra bali qo\'shildi)', [
                                        'zeroed_record_id' => $otherRecord->id,
                                        'active_record_id' => $pointUserDeportament->id,
                                        'user_id' => $pointUserDeportament->user_id,
                                        'year' => $pointUserDeportament->year,
                                        'similarity_score' => $similarityScore,
                                        'current_table_type' => $currentTableType,
                                        'zeroed_table_type' => $otherTableType,
                                        'current_priority' => $currentPriority,
                                        'other_priority' => $otherPriority,
                                        'department_points_added' => 0.10
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        });

        // Table 20 dublikatlar tizimi: agar bir Table 20 turiga ball berilsa, qolganlarini 0 ga o'tkazish
        static::updated(function ($pointUserDeportament) {
            // Faqat ball o'zgarganda ishlaydi
            if ($pointUserDeportament->isDirty('point') && $pointUserDeportament->point > 0) {
                // Agar Table 20 turlaridan biri bo'lsa
                $isTable20 = $pointUserDeportament->table_20_1_id || 
                           $pointUserDeportament->table_20_2_id || 
                           $pointUserDeportament->table_20_3_id;
                
                if ($isTable20) {
                    // Current record ma'lumotlarini olish
                    $currentTableType = null;
                    if ($pointUserDeportament->table_20_1_id) $currentTableType = 'table_20_1';
                    elseif ($pointUserDeportament->table_20_2_id) $currentTableType = 'table_20_2';
                    elseif ($pointUserDeportament->table_20_3_id) $currentTableType = 'table_20_3';

                    $currentRelatedData = self::getRelatedDataForModel($pointUserDeportament, $currentTableType);

                    if ($currentRelatedData) {
                        // Shu foydalanuvchining shu yildagi boshqa Table 20 yozuvlarini topish
                        $otherTable20Records = self::where('user_id', $pointUserDeportament->user_id)
                            ->where('year', $pointUserDeportament->year)
                            ->where('id', '!=', $pointUserDeportament->id)
                            ->where('point', '>', 0)
                            ->where(function ($query) {
                                $query->whereNotNull('table_20_1_id')
                                      ->orWhereNotNull('table_20_2_id')
                                      ->orWhereNotNull('table_20_3_id');
                            })
                            ->get();

                        foreach ($otherTable20Records as $otherRecord) {
                            $otherTableType = null;
                            if ($otherRecord->table_20_1_id) $otherTableType = 'table_20_1';
                            elseif ($otherRecord->table_20_2_id) $otherTableType = 'table_20_2';
                            elseif ($otherRecord->table_20_3_id) $otherTableType = 'table_20_3';

                            if ($currentTableType === $otherTableType) continue; // Bir xil tip bo'lsa o'tish

                            // Prioritet tartibini tekshirish
                            $priorityOrder = ['table_20_1' => 1, 'table_20_2' => 2, 'table_20_3' => 3];
                            $currentPriority = $priorityOrder[$currentTableType] ?? 999;
                            $otherPriority = $priorityOrder[$otherTableType] ?? 999;

                            // Faqat past prioritetli tablelarni ta'sir qilish (yuqori prioritetli tablela ta'sir qilmaydi)
                            if ($currentPriority >= $otherPriority) continue;

                            $otherRelatedData = self::getRelatedDataForModel($otherRecord, $otherTableType);

                            if ($otherRelatedData) {
                                // O'xshashlik darajasini hisoblash
                                $similarityScore = self::calculateTable20SimilarityForModel($currentRelatedData, $otherRelatedData);
                                
                                // Agar 70% dan ko'p o'xshash bo'lsa, dublikat sifatida qayta ishlash
                                if ($similarityScore >= 0.7) {
                                    // O'qituvchi balini 0 ga o'tkazish
                                    $otherRecord->update(['point' => 0]);
                                    
                                    // Dublikat uchun avtomatik kafedra bali (0.10) yaratish
                                    \App\Models\DepartPoints::updateOrCreate(
                                        ['point_user_deport_id' => $otherRecord->id],
                                        [
                                            'point' => 0.10,
                                            'status' => 1
                                        ]
                                    );
                                    
                                    Log::info('Table 20 dublikat avtomatik tuzatildi (kafedra bali qo\'shildi)', [
                                        'zeroed_record_id' => $otherRecord->id,
                                        'active_record_id' => $pointUserDeportament->id,
                                        'user_id' => $pointUserDeportament->user_id,
                                        'year' => $pointUserDeportament->year,
                                        'similarity_score' => $similarityScore,
                                        'current_table_type' => $currentTableType,
                                        'zeroed_table_type' => $otherTableType,
                                        'current_priority' => $currentPriority,
                                        'other_priority' => $otherPriority,
                                        'department_points_added' => 0.10
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        });
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departament_id');
    }

    public function getRelationships()
    {
        return $this->relationships;
    }

    public function departPoint()
    {
        return $this->hasOne(\App\Models\DepartPoints::class, 'point_user_deport_id');
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'user_id');
    }

    public function user_ponts()
    {
        return $this->belongsTo(\App\Models\Department::class);
    }

    public function table_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_2_::class, 'id', 'table_2_id');
    }

    public function table_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_3_::class, 'id', 'table_3_id');
    }

    public function table_4()
    {
        return $this->hasOne(\App\Models\Tables\Table_4_::class, 'id', 'table_4_id');
    }

    public function table_5()
    {
        return $this->hasOne(\App\Models\Tables\Table_5_::class, 'id', 'table_5_id');
    }

    public function table_6()
    {
        return $this->hasOne(\App\Models\Tables\Table_6_::class, 'id', 'table_6_id');
    }

    public function table_7()
    {
        return $this->hasOne(\App\Models\Tables\Table_7_::class, 'id', 'table_7_id');
    }

    public function table_8_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_8_1_::class, 'id', 'table_8_1_id');
    }

    public function table_8_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_8_2_::class, 'id', 'table_8_2_id');
    }

    public function table_9_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_9_1_::class, 'id', 'table_9_1_id');
    }

    public function table_9_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_9_2_::class, 'id', 'table_9_2_id');
    }

    public function table_10_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_10_1_::class, 'id', 'table_10_1_id');
    }

    public function table_10_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_10_2_::class, 'id', 'table_10_2_id');
    }

    public function table_10_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_10_3_::class, 'id', 'table_10_3_id');
    }

    public function table_11_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_11_1_::class, 'id', 'table_11_1_id');
    }

    public function table_11_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_11_2_::class, 'id', 'table_11_2_id');
    }

    public function table_11_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_11_3_::class, 'id', 'table_11_3_id');
    }

    public function table_12()
    {
        return $this->hasOne(\App\Models\Tables\Table_12_::class, 'id', 'table_12_id');
    }

    public function table_13()
    {
        return $this->hasOne(\App\Models\Tables\Table_13_::class, 'id', 'table_13_id');
    }

    public function table_14_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_14_1_::class, 'id', 'table_14_1_id');
    }

    public function table_14_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_14_2_::class, 'id', 'table_14_2_id');
    }

    public function table_14_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_14_3_::class, 'id', 'table_14_3_id');
    }

    public function table_15_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_15_1_::class, 'id', 'table_15_1_id');
    }

    public function table_15_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_15_2_::class, 'id', 'table_15_2_id');
    }

    public function table_16()
    {
        return $this->hasOne(\App\Models\Tables\Table_16_::class, 'id', 'table_16_id');
    }

    public function table_17_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_17_1_::class, 'id', 'table_17_1_id');
    }

    public function table_17_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_17_2_::class, 'id', 'table_17_2_id');
    }

    public function table_18_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_18_1_::class, 'id', 'table_18_1_id');
    }

    public function table_18_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_18_2_::class, 'id', 'table_18_2_id');
    }

    public function table_18_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_18_3_::class, 'id', 'table_18_3_id');
    }

    public function table_18_3_a()
    {
        return $this->hasOne(\App\Models\Tables\Table_18_3_a_::class, 'id', 'table_18_3_a_id');
    }

    public function table_19()
    {
        return $this->hasOne(\App\Models\Tables\Table_19_::class, 'id', 'table_19_id');
    }

    public function table_20_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_20_1_::class, 'id', 'table_20_1_id');
    }

    public function table_20_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_20_2_::class, 'id', 'table_20_2_id');
    }

    public function table_20_3()
    {
        return $this->hasOne(\App\Models\Tables\Table_20_3_::class, 'id', 'table_20_3_id');
    }

    public function table_21_1()
    {
        return $this->hasOne(\App\Models\Tables\Table_21_1_::class, 'id', 'table_21_1_id');
    }

    public function table_21_2()
    {
        return $this->hasOne(\App\Models\Tables\Table_21_2_::class, 'id', 'table_21_2_id');
    }

    public function table_22()
    {
        return $this->hasOne(\App\Models\Tables\Table_22_::class, 'id', 'table_22_id');
    }

    public function table_23()
    {
        return $this->hasOne(\App\Models\Tables\Table_23_::class, 'id', 'table_23_id');
    }

    public function table_24()
    {
        return $this->hasOne(\App\Models\Tables\Table_24_::class, 'id', 'table_24_id');
    }

    /**
     * Static method: Ma'lumotlarni olish (relation orqali)
     */
    public static function getRelatedDataForModel($record, $tableType)
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
            Log::warning('Error getting related data', [
                'record_id' => $record->id,
                'table_type' => $tableType,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Static method: Table 11 o'xshashlik hisoblash
     */
    public static function calculateTable11SimilarityForModel($data1, $data2)
    {
        $journalSimilarity = self::calculateSimilarityForModel(
            self::normalizeTextForModel($data1->jurnal_nomi ?? ''),
            self::normalizeTextForModel($data2->jurnal_nomi ?? '')
        );
        
        $articleSimilarity = self::calculateSimilarityForModel(
            self::normalizeTextForModel($data1->maqola_nomi ?? ''),
            self::normalizeTextForModel($data2->maqola_nomi ?? '')
        );
        
        $year1 = self::normalizeYearForModel($data1->nashr_yili ?? '');
        $year2 = self::normalizeYearForModel($data2->nashr_yili ?? '');
        $yearSimilarity = ($year1 === $year2 && !empty($year1)) ? 1.0 : 0.0;

        // Weighted average: jurnal 33%, maqola 33%, yil 34%
        return ($journalSimilarity * 0.33) + ($articleSimilarity * 0.33) + ($yearSimilarity * 0.34);
    }

    /**
     * Static method: Table 20 o'xshashlik hisoblash
     */
    public static function calculateTable20SimilarityForModel($data1, $data2)
    {
        $journalSimilarity = self::calculateSimilarityForModel(
            self::normalizeTextForModel($data1->jurnal_nomi ?? ''),
            self::normalizeTextForModel($data2->jurnal_nomi ?? '')
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

    /**
     * Static method: Matnni normallash
     */
    public static function normalizeTextForModel($text)
    {
        if (empty($text)) return '';
        
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        $text = preg_replace('/[.,;:!?"`\'"«»„"]/', '', $text);
        
        return $text;
    }

    /**
     * Static method: Yilni normallash
     */
    public static function normalizeYearForModel($yearText)
    {
        preg_match('/\b(20\d{2}|19\d{2})\b/', $yearText, $matches);
        return $matches[0] ?? '';
    }

    /**
     * Static method: O'xshashlik hisoblash (Levenshtein)
     */
    public static function calculateSimilarityForModel($str1, $str2)
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

        $distance = levenshtein($str1, $str2);
        return max(0, 1 - ($distance / $maxLen));
    }
}