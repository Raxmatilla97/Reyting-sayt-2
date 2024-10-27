<?php

namespace App\Http\Controllers;

use App\Models\PointUserDeportament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConfigurationController extends Controller
{
    public function delete()
    {
        try {
            DB::beginTransaction();

            Log::info('Rad etilgan malumotlarni oʻchirish boshlandi');

            // Status = 0 bo'lgan barcha yozuvlarni olish
            $query = PointUserDeportament::where('status', 0)->with([
                'table_1_1', 'table_1_2', 'table_1_4', 'table_1_5_1', 'table_1_5_1_a',
                'table_1_6_1', 'table_1_6_1_a', 'table_1_6_2', 'table_1_9_1', 'table_1_9_2',
                'table_1_9_3', 'table_2_2_1', 'table_2_2_2', 'table_2_4_2',
                'table_1_7_1', 'table_1_7_2', 'table_1_7_3', 'table_2_3_1', 'table_2_3_2',
                'table_2_4_1', 'table_2_4_2_b', 'table_2_5', 'table_3_4_1', 'table_3_4_2',
                'table_4_1'
            ]);

            $totalRecords = $query->count();

            Log::info('Topilgan rad etilgan yozuvlar soni: ' . $totalRecords);

            if ($totalRecords == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'O\'chiriladigan ma\'lumotlar topilmadi'
                ]);
            }

            $deletedCount = 0;
            $errors = [];

            // Har bir yozuvni alohida o'chiramiz
            $query->orderBy('id')->chunk(5000, function ($items) use (&$deletedCount, &$errors) {
                foreach ($items as $item) {
                    try {
                        Log::info('Murojaat ID: ' . $item->id . ' o\'chirilmoqda');

                        // Bog'langan relationlarni o'chirish
                        $relations = [
                            'table_1_1', 'table_1_2', 'table_1_4', 'table_1_5_1', 'table_1_5_1_a',
                            'table_1_6_1', 'table_1_6_1_a', 'table_1_6_2', 'table_1_9_1', 'table_1_9_2',
                            'table_1_9_3', 'table_2_2_1', 'table_2_2_2', 'table_2_4_2',
                            'table_1_7_1', 'table_1_7_2', 'table_1_7_3', 'table_2_3_1', 'table_2_3_2',
                            'table_2_4_1', 'table_2_4_2_b', 'table_2_5', 'table_3_4_1', 'table_3_4_2',
                            'table_4_1'
                        ];

                        foreach ($relations as $relation) {
                            if ($item->$relation) {
                                $item->$relation->delete();
                            }
                        }

                        // Asosiy yozuvni o'chirish
                        $item->delete();
                        $deletedCount++;

                    } catch (\Exception $e) {
                        Log::error('ID ' . $item->id . ' uchun xatolik: ' . $e->getMessage());
                        $errors[] = 'ID ' . $item->id . ': ' . $e->getMessage();
                    }
                }
            });

            if (empty($errors)) {
                DB::commit();
                Log::info('Muvaffaqiyatli oʻchirildi. Jami: ' . $deletedCount);

                return response()->json([
                    'success' => true,
                    'message' => "Barcha rad etilgan ma'lumotlar muvaffaqiyatli o'chirildi. ($deletedCount ta yozuv)",
                    'progress' => 100
                ]);
            } else {
                throw new \Exception('Ba\'zi yozuvlarni o\'chirishda xatolik: ' . implode(', ', $errors));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rad etilgan malumotlarni oʻchirishda xatolik: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }
}
