<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache; // Cache ni import qilamiz
use App\Traits\ServerSentEventTrait;

class ConfigurationController extends Controller
{
    use ServerSentEventTrait;

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
            header('X-Accel-Buffering: no');

            $this->sendUpdate('Jarayon boshlandi', 0);

            $token = env('API_HEMIS_TOKEN');
            $baseUrl = env('API_HEMIS_URL');

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

            $fakultetlar = array_values(
                array_filter($fakultetlarResponse['data']['items'], function ($f) {
                    return $f['localityType']['name'] === 'Mahalliy';
                }),
            );

            $kafedralar = $kafedralarResponse['data']['items'];
            $totalFaculties = count($fakultetlar);

            $this->sendUpdate('Ma\'lumotlarni qayta ishlash boshlandi', 15);

            // O'qituvchilarning bandlik ma'lumotlarini saqlash uchun
            $teacherEmployments = [];

            foreach ($fakultetlar as $index => $fakultet) {
                $facultyId = $fakultet['id'];
                $facultyDepartments = [];

                $facultyProgress = 15 + (($index + 1) / $totalFaculties) * 80;
                $this->sendUpdate("Fakultet qayta ishlanmoqda: {$fakultet['name']}", round($facultyProgress));

                foreach ($kafedralar as $kafedra) {
                    if ($kafedra['parent'] == $facultyId) {
                        try {
                            $teacherCount = 0;
                            /*
                                $employmentForms dagi massiv raqamlari manolari
                                ----------------------------------------------
                                11 - Asosiy ish joy,
                                12 - O‘rindoshlik (ichki-qo'shimcha),
                                13 - O‘rindoshlik (tashqi),
                                14 - Soatbay,
                                15 - O‘rindoshlik (ichki-asosiy)
                                -----------------------------------------------
                            */
                            $employmentForms = [11, 12, 15]; // Faqat kerakli bandlik turlari

                            $this->sendUpdate("Kafedra qayta ishlanmoqda: {$kafedra['name']}", round($facultyProgress));

                            foreach ($employmentForms as $formCode) {
                                $employeesUrl = $baseUrl . "/rest/v1/data/employee-list?type=teacher&limit=100&_department={$kafedra['id']}&_employment_form={$formCode}";
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

                                if (isset($employeesResponse['data']['items'])) {
                                    foreach ($employeesResponse['data']['items'] as $employee) {
                                        $employeeId = $employee['employee_id_number'];

                                        // Asosiy ish joyi (1.0 stavka)
                                        if ($formCode == 11) {
                                            $teacherCount++;
                                            $teacherEmployments[$employeeId] = [
                                                'mainDepartment' => $kafedra['id'],
                                                'employmentForm' => $formCode
                                            ];
                                        }
                                        // Ichki asosiy o'rindoshlik (15)
                                        else if ($formCode == 15) {
                                            if (
                                                !isset($teacherEmployments[$employeeId]) ||
                                                ($teacherEmployments[$employeeId]['employmentForm'] != 15 &&
                                                    $teacherEmployments[$employeeId]['mainDepartment'] != $kafedra['id'])
                                            ) {
                                                $teacherCount++;
                                                $teacherEmployments[$employeeId] = [
                                                    'mainDepartment' => $kafedra['id'],
                                                    'employmentForm' => $formCode
                                                ];
                                            }
                                        }
                                        // Ichki qo'shimcha o'rindoshlik (12)
                                        else if ($formCode == 12) {
                                            // Faqat agar o'qituvchining asosiy ish joyi boshqa kafedrada bo'lsa
                                            if (
                                                !isset($teacherEmployments[$employeeId]) ||
                                                $teacherEmployments[$employeeId]['mainDepartment'] != $kafedra['id']
                                            ) {
                                                $teacherCount++;
                                            }
                                        }
                                    }
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




 // O'qituvchilarni kafedralarga joylashtirish
//  Bu boshqa kodlar!!!!
    public function getEmployeeDataFromHemis($employeeIdNumber)
    {
        try {
            $url = env('API_HEMIS_URL') . "/rest/v1/data/employee-list?type=teacher&search=$employeeIdNumber&limit=200";
            $response = Http::withToken(env('API_HEMIS_TOKEN'))->get($url)->json();

            if ($response['data']['pagination']['totalCount'] > 0) {
                // Ma'lumotlarni qayta ishlash
                $employeeData = $response['data']['items'][0];

                // departments massivini yaratamiz
                $departments = [];
                if (isset($employeeData['department']) && isset($employeeData['employmentForm'])) {
                    $departments[] = [
                        'department' => $employeeData['department'],
                        'employmentForm' => $employeeData['employmentForm']
                    ];
                }

                // departments massivini qo'shamiz
                $employeeData['departments'] = $departments;

                Log::info("Xodim ma'lumotlari olindi", [
                    'employee_id' => $employeeIdNumber,
                    'departments' => $departments
                ]);

                return $employeeData;
            }

            Log::warning("HEMIS da xodim topilmadi: $employeeIdNumber");
            throw new \Exception("HEMIS dan xodim ma'lumotlari olinmadi.");
        } catch (\Exception $e) {
            Log::error("HEMIS API xatolik", [
                'employee_id' => $employeeIdNumber,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getDepartmentId($departments)
    {
        try {
            $priorityOrder = [11, 15, 12];

            foreach ($priorityOrder as $priorityCode) {
                foreach ($departments as $department) {
                    if (
                        isset($department['employmentForm']['code']) &&
                        $department['employmentForm']['code'] == $priorityCode
                    ) {
                        $departmentId = $department['department']['id'];
                        if (Department::where('id', $departmentId)->exists()) {
                            return $departmentId;
                        }
                    }
                }
            }

            Log::info("Tegishli bandlik turi bo'yicha kafedra topilmadi", [
                'departments' => $departments,
                'priority_order' => $priorityOrder
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error("Kafedra ID ni aniqlashda xatolik", [
                'error' => $e->getMessage(),
                'departments' => $departments
            ]);
            throw $e;
        }
    }

    // O'qituvchilarni kafedralarga joylash
    public function updateTeacherDepartments()
    {
        try {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            // CORS headerlarini qo'shamiz
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Allow-Credentials: true');

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                exit(0);
            }

            Log::info('O\'qituvchilar kafedralarini yangilash jarayoni boshlandi');
            $this->sendUpdate('Jarayon boshlandi', 0);

            // Barcha foydalanuvchilarni olish
            $users = User::whereNotNull('employee_id_number')->get();
            $totalUsers = $users->count();

            Log::info("Jami {$totalUsers} ta foydalanuvchi topildi");
            $this->sendUpdate('Foydalanuvchilar ro\'yxati olindi', 5);

            $processed = 0;
            $successCount = 0;
            $errorCount = 0;
            $skippedCount = 0;

            foreach ($users as $user) {
                try {
                    $processed++;
                    $progress = 5 + ($processed / $totalUsers * 90);

                    Log::info("Foydalanuvchi qayta ishlanmoqda: {$user->name} (ID: {$user->id})", [
                        'employee_id' => $user->employee_id_number,
                        'current_department' => $user->department_id
                    ]);

                    $this->sendUpdate("Foydalanuvchi qayta ishlanmoqda: {$user->name}", round($progress));

                    try {
                        // HEMIS dan ma'lumot olish
                        $employeeData = $this->getEmployeeDataFromHemis($user->employee_id_number);

                        // API javobini tekshirish
                        if (empty($employeeData)) {
                            Log::warning("Foydalanuvchi {$user->name} uchun HEMIS dan ma'lumot olinmadi", [
                                'employee_id' => $user->employee_id_number
                            ]);
                            $skippedCount++;
                            continue;
                        }

                        // Departments massivini tekshirish
                        if (empty($employeeData['departments'])) {
                            Log::warning("Foydalanuvchi {$user->name} uchun departments ma'lumoti yo'q", [
                                'employee_id' => $user->employee_id_number,
                                'hemis_data' => $employeeData
                            ]);
                            $skippedCount++;
                            continue;
                        }

                        // Yangi department_id ni aniqlash
                        $newDepartmentId = $this->getDepartmentId($employeeData['departments']);

                        if (!$newDepartmentId) {
                            Log::warning("Foydalanuvchi {$user->name} uchun mos department topilmadi", [
                                'employee_id' => $user->employee_id_number,
                                'departments' => $employeeData['departments']
                            ]);
                            $skippedCount++;
                            continue;
                        }

                        // Department o'zgarganligini tekshirish
                        if ($newDepartmentId === $user->department_id) {
                            Log::info("Foydalanuvchi {$user->name} ning kafedrasi o'zgarmagan", [
                                'department_id' => $newDepartmentId
                            ]);
                            $skippedCount++;
                            continue;
                        }

                        // O'zgartirish kiritish
                        Log::info("Foydalanuvchi {$user->name} uchun yangi kafedra topildi", [
                            'old_department' => $user->department_id,
                            'new_department' => $newDepartmentId
                        ]);

                        $oldDepartmentId = $user->department_id;

                        try {
                            DB::transaction(function () use ($user, $oldDepartmentId, $newDepartmentId) {
                                // Foydalanuvchi department_id sini yangilash
                                $user->department_id = $newDepartmentId;
                                $user->save();

                                // PointUserDeportament jadvalidagi yozuvlarni yangilash
                                $updatedCount = PointUserDeportament::where('user_id', $user->id)
                                    ->where('departament_id', $oldDepartmentId)
                                    ->update(['departament_id' => $newDepartmentId]);

                                Log::info("PointUserDeportament jadvalida {$updatedCount} ta yozuv yangilandi", [
                                    'user_id' => $user->id,
                                    'old_department' => $oldDepartmentId,
                                    'new_department' => $newDepartmentId
                                ]);
                            });

                            $successCount++;
                            $this->sendUpdate("Foydalanuvchi {$user->name} kafedrasi yangilandi", round($progress));

                        } catch (\Exception $e) {
                            Log::error("Database yangilashda xatolik: {$user->name}", [
                                'error' => $e->getMessage(),
                                'user_id' => $user->id,
                                'old_department' => $oldDepartmentId,
                                'new_department' => $newDepartmentId
                            ]);
                            $errorCount++;
                            continue;
                        }

                    } catch (\Exception $e) {
                        Log::error("HEMIS so'rovi xatoligi: {$user->name}", [
                            'error' => $e->getMessage(),
                            'user_id' => $user->id,
                            'employee_id' => $user->employee_id_number
                        ]);
                        $errorCount++;
                        continue;
                    }

                } catch (\Exception $e) {
                    Log::error("Foydalanuvchini qayta ishlashda xatolik", [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $errorCount++;
                    continue;
                }
            }

            $summary = [
                'total' => $totalUsers,
                'success' => $successCount,
                'errors' => $errorCount,
                'skipped' => $skippedCount
            ];

            Log::info('Jarayon yakunlandi', $summary);

            $this->sendUpdate("Jarayon yakunlandi. Jami: {$totalUsers}, Yangilandi: {$successCount}, Xatoliklar: {$errorCount}, O'tkazib yuborildi: {$skippedCount}", 100);
            die();

        } catch (\Exception $e) {
            Log::error('Umumiy xatolik yuz berdi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->sendUpdate('Xatolik yuz berdi: ' . $e->getMessage(), 100);
            die();
        }
    }
}
