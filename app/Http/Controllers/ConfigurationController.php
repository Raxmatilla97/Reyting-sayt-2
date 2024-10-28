<?php

namespace App\Http\Controllers;

use App\Models\PointUserDeportament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // Cache ni import qilamiz

class ConfigurationController extends Controller
{
    public function delete()
    {
        try {
            DB::beginTransaction();

            Log::info('Rad etilgan malumotlarni oʻchirish boshlandi');

            // Status = 0 bo'lgan barcha yozuvlarni olish
            $query = PointUserDeportament::where('status', 0)->with(['table_1_1', 'table_1_2', 'table_1_4', 'table_1_5_1', 'table_1_5_1_a', 'table_1_6_1', 'table_1_6_1_a', 'table_1_6_2', 'table_1_9_1', 'table_1_9_2', 'table_1_9_3', 'table_2_2_1', 'table_2_2_2', 'table_2_4_2', 'table_1_7_1', 'table_1_7_2', 'table_1_7_3', 'table_2_3_1', 'table_2_3_2', 'table_2_4_1', 'table_2_4_2_b', 'table_2_5', 'table_3_4_1', 'table_3_4_2', 'table_4_1']);

            $totalRecords = $query->count();

            Log::info('Topilgan rad etilgan yozuvlar soni: ' . $totalRecords);

            if ($totalRecords == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'O\'chiriladigan ma\'lumotlar topilmadi',
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
                        $relations = ['table_1_1', 'table_1_2', 'table_1_4', 'table_1_5_1', 'table_1_5_1_a', 'table_1_6_1', 'table_1_6_1_a', 'table_1_6_2', 'table_1_9_1', 'table_1_9_2', 'table_1_9_3', 'table_2_2_1', 'table_2_2_2', 'table_2_4_2', 'table_1_7_1', 'table_1_7_2', 'table_1_7_3', 'table_2_3_1', 'table_2_3_2', 'table_2_4_1', 'table_2_4_2_b', 'table_2_5', 'table_3_4_1', 'table_3_4_2', 'table_4_1'];

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
                    'progress' => 100,
                ]);
            } else {
                throw new \Exception('Ba\'zi yozuvlarni o\'chirishda xatolik: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rad etilgan malumotlarni oʻchirishda xatolik: ' . $e->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Xatolik yuz berdi: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function updateTeachersCount()
    {
        try {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no'); // NGINX uchun

            $this->sendUpdate('Jarayon boshlandi', 0);

            $token = env('API_HEMIS_TOKEN');
            $baseUrl = env('API_HEMIS_URL');

            // Fakultetlar ro'yxatini olish
            $this->sendUpdate('Fakultetlar ro\'yxati yuklanmoqda...', 5);

            $fakultetlarUrl = $baseUrl . '/rest/v1/data/department-list?limit=200&active=1&_structure_type=11&localityType.name=Mahalliy&structureType.name=Fakultet';
            $fakultetlarResponse = json_decode(
                file_get_contents(
                    $fakultetlarUrl,
                    false,
                    stream_context_create([
                        'http' => [
                            'method' => 'GET',
                            'header' => ['Authorization: Bearer ' . $token, 'Content-Type: application/json'],
                        ],
                    ]),
                ),
                true,
            );

            $this->sendUpdate('Kafedralar ro\'yxati yuklanmoqda...', 10);

            $kafedralarUrl = $baseUrl . '/rest/v1/data/department-list?limit=200&active=1&_structure_type=12&structureType.name=Kafedra';
            $kafedralarResponse = json_decode(
                file_get_contents(
                    $kafedralarUrl,
                    false,
                    stream_context_create([
                        'http' => [
                            'method' => 'GET',
                            'header' => ['Authorization: Bearer ' . $token, 'Content-Type: application/json'],
                        ],
                    ]),
                ),
                true,
            );

            $configPath = config_path('departament_tichers_count.php');
            $currentConfig = include $configPath;
            $newConfig = [];

            // Fakultetlarni filtrlash
            $fakultetlar = array_values(
                array_filter($fakultetlarResponse['data']['items'], function ($f) {
                    return $f['localityType']['name'] === 'Mahalliy';
                }),
            );

            $kafedralar = $kafedralarResponse['data']['items'];
            $totalFaculties = count($fakultetlar);

            $this->sendUpdate('Ma\'lumotlarni qayta ishlash boshlandi', 15);

            foreach ($fakultetlar as $index => $fakultet) {
                $facultyId = $fakultet['id'];
                $facultyDepartments = [];

                // Fakultet progress
                $facultyProgress = 15 + (($index + 1) / $totalFaculties) * 80;
                $this->sendUpdate("Fakultet qayta ishlanmoqda: {$fakultet['name']}", round($facultyProgress));

                foreach ($kafedralar as $kafedra) {
                    if ($kafedra['parent'] == $facultyId) {
                        try {
                            $teacherCount = 0;
                            $employmentForms = [11, 12, 15];

                            $this->sendUpdate("Kafedra qayta ishlanmoqda: {$kafedra['name']}", round($facultyProgress));

                            foreach ($employmentForms as $form) {
                                $employeesUrl = $baseUrl . "/rest/v1/data/employee-list?type=teacher&limit=100&_department={$kafedra['id']}&_employment_form={$form}";
                                $employeesResponse = json_decode(
                                    file_get_contents(
                                        $employeesUrl,
                                        false,
                                        stream_context_create([
                                            'http' => [
                                                'method' => 'GET',
                                                'header' => ['Authorization: Bearer ' . $token, 'Content-Type: application/json'],
                                            ],
                                        ]),
                                    ),
                                    true,
                                );

                                if (isset($employeesResponse['data']['pagination']['totalCount'])) {
                                    $teacherCount += $employeesResponse['data']['pagination']['totalCount'];
                                }
                            }

                            $facultyDepartments[$kafedra['id']] = $teacherCount . ', //* ' . $kafedra['name'];
                        } catch (\Exception $e) {
                            \Log::error("Kafedra {$kafedra['id']} uchun xatolik: " . $e->getMessage());
                            continue;
                        }
                    }
                }

                if (isset($currentConfig[$facultyId])) {
                    foreach ($currentConfig[$facultyId] as $deptId => $value) {
                        if (!array_key_exists($deptId, $facultyDepartments) && is_string($value) && strpos($value, '//') === 0) {
                            $facultyDepartments[$deptId] = $value;
                        }
                    }
                }

                $newConfig[$facultyId] = $facultyDepartments;
            }

            $this->sendUpdate('Konfiguratsiya fayli yangilanmoqda...', 95);

            // Config faylini yangilash
            $fileContent = "<?php\nreturn [\n";
            foreach ($newConfig as $facultyId => $departments) {
                $fakultetNomi = '';
                foreach ($fakultetlar as $f) {
                    if ($f['id'] == $facultyId) {
                        $fakultetNomi = $f['name'];
                        break;
                    }
                }
                $fileContent .= "    '$facultyId' => [ // " . $fakultetNomi . "\n";
                foreach ($departments as $deptId => $info) {
                    $fileContent .= "        '$deptId' => $info,\n";
                }
                $fileContent .= "    ],\n";
            }
            $fileContent .= "];\n";

            file_put_contents($configPath, $fileContent);

            $this->sendUpdate('Jarayon muvaffaqiyatli yakunlandi', 100);
            die();
        } catch (\Exception $e) {
            \Log::error('O\'qituvchilar sonini yangilashda xatolik: ' . $e->getMessage());
            $this->sendUpdate('Xatolik yuz berdi: ' . $e->getMessage(), 100);
            die();
        }
    }

    private function sendUpdate($message, $progress)
    {
        \Log::info("Sending update: $message, Progress: $progress");
        echo 'data: ' . json_encode(['message' => $message, 'progress' => $progress]) . "\n\n";
        ob_flush();
        flush();
    }

    public function stopTeachersUpdate()
    {
        Cache::put('update_process_status', 'stopped', 600);
        Log::info('Jarayon foydalanuvchi tomonidan to\'xtatildi');
        return response()->json([
            'success' => true,
            'message' => 'Jarayon to\'xtatildi',
        ]);
    }
}
