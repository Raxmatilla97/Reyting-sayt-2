<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PointUserDeportament;
use App\Traits\ServerSentEventTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class ConfigurationController extends Controller
{
    use ServerSentEventTrait;

    /**
     * Rad etilgan murojaat ma'lumotlarini tizimdan o'chirish
     *
     * Bu funksiya quyidagi amallarni bajaradi:
     * 1. Rad etilgan (status=0) barcha murojaat ma'lumotlarini o'chirish:
     *    - Asosiy murojaatlar
     *    - Bog'liq bo'lgan jadvallar

     * Xavfsizlik choralari:
     * - Database tranzaksiyalardan foydalaniladi
     * - O'chirishdan oldin ma'lumotlar tekshiriladi
     * - Xatoliklar nazorat qilinadi va logga yoziladi
     * - Batch processing orqali katta hajmdagi ma'lumotlar bilan ishlash
     *
     * Optimizatsiya:
     * - Ma'lumotlar 5000 talik guruhlar bilan o'chiriladi (chunk)
     * - Har bir qadam logga yoziladi
     * - Xotira samaradorligi ta'minlanadi
     *
     * @return \Illuminate\Http\JsonResponse Quyidagi formatda:
     * {
     *     "success": boolean,
     *     "message": string,
     *     "progress"?: number // [optional] progress foizlarda
     * }
     *
     * @throws \Exception Quyidagi hollarda:
     * - Database ga bog'lanishda xatolik
     * - Ma'lumotlarni o'chirishda xatolik
     * - Tranzaksiya xatoligi
     *
     * @example
     * Muvaffaqiyatli javob:
     * {
     *     "success": true,
     *     "message": "Barcha rad etilgan ma'lumotlar muvaffaqiyatli o'chirildi. (123 ta yozuv)"
     * }
     *
     * Xatolik javobi:
     * {
     *     "success": false,
     *     "message": "O'chiriladigan ma'lumotlar topilmadi"
     * }
     */
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


    /**
     * Fakultet va kafedralar ma'lumotlarini HEMIS bilan sinxronizatsiya qilish
     *
     * Bu funksiya quyidagi amallarni bajaradi:
     * 1. HEMIS tizimidan yangilangan ma'lumotlarni olish:
     *    - Barcha fakultetlar ro'yxatini oladi
     *    - Barcha kafedralar ro'yxatini oladi
     *
     * 2. Fakultetlarni yangilash:
     *    - Mavjud fakultetlar nomini tekshiradi va yangilaydi
     *    - HEMIS da yo'q fakultetlarni o'chirish uchun belgilaydi (status = false)
     *    - Yangi fakultetlarni qo'shadi
     *
     * 3. Kafedralarni yangilash:
     *    - Mavjud kafedralar nomini tekshiradi va yangilaydi
     *    - Kafedra fakultetga biriktirilishini tekshiradi va yangilaydi
     *    - HEMIS da yo'q kafedralarni o'chirish uchun belgilaydi (status = false)
     *    - Yangi kafedralarni qo'shadi
     *
     * O'zgarishlar monitoringi:
     * - Har bir o'zgarish logga yoziladi
     * - Real vaqtda progress va o'zgarishlar ko'rsatiladi
     * - Yakuniy hisobotda ko'rsatiladi:
     *   * Yangilangan fakultetlar va ularning o'zgarishlari
     *   * O'chirilgan fakultetlar
     *   * Yangi qo'shilgan fakultetlar
     *   * Yangilangan kafedralar va ularning o'zgarishlari
     *   * O'chirilgan kafedralar
     *   * Yangi qo'shilgan kafedralar
     *
     * Xavfsizlik choralari:
     * - Ma'lumotlar bazasida tranzaksiyalar qo'llaniladi
     * - Xatoliklar nazorat qilinadi va logga yoziladi
     * - HEMIS API bilan xavfsiz bog'lanish ta'minlanadi
     *
     * @return void
     * @throws \Exception HEMIS bilan bog'lanishda, ma'lumotlarni saqlashda yoki yangilashda xatolik yuz berganda
     *
     * @example
     * // Barcha fakultet va kafedralarni yangilash
     * $controller->updateDepartments();
     */

     public function updateDepartments()
     {
         try {
             header('Content-Type: text/event-stream');
             header('Cache-Control: no-cache');
             header('Connection: keep-alive');
             header('X-Accel-Buffering: no');

             // O'zgarishlar ro'yxatini saqlash uchun array
             $changes = [
                 'updated_faculties' => [],
                 'deleted_faculties' => [],
                 'new_faculties' => [],
                 'updated_departments' => [],
                 'deleted_departments' => [],
                 'new_departments' => []
             ];

             $this->sendUpdate('Jarayon boshlandi', 0);

             $token = env('API_HEMIS_TOKEN');
             $baseUrl = env('API_HEMIS_URL');

             // Fakultetlarni olish
             $this->sendUpdate('Fakultetlar ro\'yxati yuklanmoqda...', 5);
             $fakultetlarUrl = $baseUrl . '/rest/v1/data/department-list?limit=200&active=1&_structure_type=11&localityType.name=Mahalliy&structureType.name=Fakultet';
             $fakultetlarResponse = json_decode(file_get_contents(
                 $fakultetlarUrl,
                 false,
                 stream_context_create([
                     'http' => [
                         'method' => 'GET',
                         'header' => [
                             'Authorization: Bearer ' . $token,
                             'Content-Type: application/json'
                         ]
                     ]
                 ])
             ), true);

             // Kafedralarni olish
             $this->sendUpdate('Kafedralar ro\'yxati yuklanmoqda...', 10);
             $kafedralarUrl = $baseUrl . '/rest/v1/data/department-list?limit=200&active=1&_structure_type=12&structureType.name=Kafedra';
             $kafedralarResponse = json_decode(file_get_contents(
                 $kafedralarUrl,
                 false,
                 stream_context_create([
                     'http' => [
                         'method' => 'GET',
                         'header' => [
                             'Authorization: Bearer ' . $token,
                             'Content-Type: application/json'
                         ]
                     ]
                 ])
             ), true);

             $hemisFakultetlar = collect($fakultetlarResponse['data']['items']);
             $hemisKafedralar = collect($kafedralarResponse['data']['items']);

             // Fakultetlarni yangilash
             $this->sendUpdate('Fakultetlar tekshirilmoqda...', 20);
             $localFakultetlar = Faculty::all();

             // Mavjud fakultetlarni tekshirish va yangilash
             foreach ($localFakultetlar as $fakultet) {
                 $hemisFakultet = $hemisFakultetlar->firstWhere('id', $fakultet->id);

                 if ($hemisFakultet) {
                     // Fakultet topildi, nomini tekshirish
                     $hemisName = $hemisFakultet['name'] . ' fakulteti';
                     if ($hemisName !== $fakultet->name) {
                         $oldName = $fakultet->name;
                         $fakultet->name = $hemisName;
                         $fakultet->slug = Str::slug($hemisFakultet['name'] . "-fakulteti-sahifasi");
                         $fakultet->save();

                         $changes['updated_faculties'][] = [
                             'id' => $fakultet->id,
                             'old_name' => $oldName,
                             'new_name' => $hemisName
                         ];

                         Log::info("Fakultet nomi yangilandi", [
                             'id' => $fakultet->id,
                             'old_name' => $oldName,
                             'new_name' => $hemisName
                         ]);
                     }
                 } else {
                     // Fakultet HEMISda yo'q
                     $fakultet->status = false;
                     $fakultet->save();

                     $changes['deleted_faculties'][] = [
                         'id' => $fakultet->id,
                         'name' => $fakultet->name
                     ];

                     Log::info("Fakultet o'chirildi", [
                         'id' => $fakultet->id,
                         'name' => $fakultet->name
                     ]);
                 }
             }

             // Yangi fakultetlarni qo'shish
             $this->sendUpdate('Yangi fakultetlar qo\'shilmoqda...', 35);
             foreach ($hemisFakultetlar as $hemisFakultet) {
                 // ID bo'yicha fakultetni tekshirish
                 $existingFaculty = Faculty::find($hemisFakultet['id']);

                 if (!$existingFaculty) {
                     $newName = $hemisFakultet['name'] . ' fakulteti';
                     Faculty::create([
                         'id' => $hemisFakultet['id'],
                         'name' => $newName,
                         'slug' => Str::slug($hemisFakultet['name'] . "-fakulteti-sahifasi"),
                         'status' => true
                     ]);

                     $changes['new_faculties'][] = [
                         'id' => $hemisFakultet['id'],
                         'name' => $newName
                     ];

                     Log::info("Yangi fakultet qo'shildi", [
                         'id' => $hemisFakultet['id'],
                         'name' => $newName
                     ]);
                 }
             }

             // Kafedralarni yangilash
             $this->sendUpdate('Kafedralar tekshirilmoqda...', 50);
             $localKafedralar = Department::all();

             // Mavjud kafedralarni tekshirish
             foreach ($localKafedralar as $kafedra) {
                 $hemisKafedra = $hemisKafedralar->firstWhere('id', $kafedra->id);

                 if (!$hemisKafedra) {
                     // Agar HEMISda topilmasa, o'chirilgan deb belgilaymiz
                     $kafedra->status = false;
                     $kafedra->save();

                     $changes['deleted_departments'][] = [
                         'id' => $kafedra->id,
                         'name' => $kafedra->name,
                         'faculty' => Faculty::find($kafedra->faculty_id)?->name ?? 'Noma\'lum'
                     ];

                     Log::info("Kafedra o'chirildi", [
                         'id' => $kafedra->id,
                         'name' => $kafedra->name,
                         'faculty' => Faculty::find($kafedra->faculty_id)?->name ?? 'Noma\'lum'
                     ]);

                     continue;
                 }

                 // HEMISda topilgan bo'lsa, ma'lumotlarni yangilaymiz
                 $needsUpdate = false;
                 $departmentChanges = [];
                 $hemisName = $hemisKafedra['name'] . ' kafedrasi';

                 if ($hemisName !== $kafedra->name) {
                     $oldName = $kafedra->name;
                     $kafedra->name = $hemisName;
                     $kafedra->slug = Str::slug($hemisKafedra['name'] . "-kafedra-sahifasi");
                     $needsUpdate = true;
                     $departmentChanges['name'] = [
                         'old' => $oldName,
                         'new' => $hemisName
                     ];
                 }

                 if ($hemisKafedra['parent'] !== $kafedra->faculty_id) {
                     $oldFacultyId = $kafedra->faculty_id;
                     $kafedra->faculty_id = $hemisKafedra['parent'];
                     $needsUpdate = true;
                     $departmentChanges['faculty'] = [
                         'old' => Faculty::find($oldFacultyId)?->name ?? 'Noma\'lum',
                         'new' => Faculty::find($hemisKafedra['parent'])?->name ?? 'Noma\'lum'
                     ];
                 }

                 if ($needsUpdate) {
                     $kafedra->save();

                     $changes['updated_departments'][] = [
                         'id' => $kafedra->id,
                         'changes' => $departmentChanges
                     ];

                     Log::info("Kafedra yangilandi", [
                         'id' => $kafedra->id,
                         'changes' => $departmentChanges
                     ]);
                 }
             }

             // Yangi kafedralarni qo'shish
             $this->sendUpdate('Yangi kafedralar qo\'shilmoqda...', 80);
             foreach ($hemisKafedralar as $hemisKafedra) {
                 // ID bo'yicha mavjud kafedralarni tekshirish
                 $existingDepartment = Department::find($hemisKafedra['id']);

                 if (!$existingDepartment) {
                     $newName = $hemisKafedra['name'] . ' kafedrasi';
                     Department::create([
                         'id' => $hemisKafedra['id'],
                         'name' => $newName,
                         'slug' => Str::slug($hemisKafedra['name'] . "-kafedra-sahifasi"),
                         'faculty_id' => $hemisKafedra['parent'],
                         'status' => true
                     ]);

                     $changes['new_departments'][] = [
                         'id' => $hemisKafedra['id'],
                         'name' => $newName,
                         'faculty' => Faculty::find($hemisKafedra['parent'])?->name ?? 'Noma\'lum'
                     ];

                     Log::info("Yangi kafedra qo'shildi", [
                         'id' => $hemisKafedra['id'],
                         'name' => $newName,
                         'faculty' => Faculty::find($hemisKafedra['parent'])?->name ?? 'Noma\'lum'
                     ]);
                 }
             }

             // O'zgarishlar hisoboti
             $summary = $this->generateChangeSummary($changes);
             $this->sendUpdate("Jarayon muvaffaqiyatli yakunlandi.\n\n" . $summary, 100);

             die();
         } catch (\Exception $e) {
             Log::error('Fakultet va kafedralarni yangilashda xatolik: ' . $e->getMessage());
             $this->sendUpdate('Xatolik yuz berdi: ' . $e->getMessage(), 100);
             die();
         }
     }

     private function sendUpdate($message, $progress)
     {
         echo "data: " . json_encode([
             'message' => $message,
             'progress' => $progress
         ]) . "\n\n";
         ob_flush();
         flush();
     }

     private function generateChangeSummary($changes)
     {
         $summary = [];
         $hasChanges = false;

         // Yangilangan fakultetlar
         if (!empty($changes['updated_faculties'])) {
             $hasChanges = true;
             $summary[] = "Yangilangan fakultetlar:";
             foreach ($changes['updated_faculties'] as $faculty) {
                 $summary[] = "- {$faculty['old_name']} → {$faculty['new_name']}";
             }
         }

         // O'chirilgan fakultetlar
         if (!empty($changes['deleted_faculties'])) {
             $hasChanges = true;
             $summary[] = "\nO'chirilgan fakultetlar:";
             foreach ($changes['deleted_faculties'] as $faculty) {
                 $summary[] = "- {$faculty['name']}";
             }
         }

         // Yangi fakultetlar
         if (!empty($changes['new_faculties'])) {
             $hasChanges = true;
             $summary[] = "\nYangi qo'shilgan fakultetlar:";
             foreach ($changes['new_faculties'] as $faculty) {
                 $summary[] = "- {$faculty['name']}";
             }
         }

         // Yangilangan kafedralar
         if (!empty($changes['updated_departments'])) {
             $hasChanges = true;
             $summary[] = "\nYangilangan kafedralar:";
             foreach ($changes['updated_departments'] as $dept) {
                 if (isset($dept['changes']['name'])) {
                     $summary[] = "- {$dept['changes']['name']['old']} → {$dept['changes']['name']['new']}";
                 }
                 if (isset($dept['changes']['faculty'])) {
                     $summary[] = "  Fakulteti: {$dept['changes']['faculty']['old']} → {$dept['changes']['faculty']['new']}";
                 }
             }
         }

         // O'chirilgan kafedralar
         if (!empty($changes['deleted_departments'])) {
             $hasChanges = true;
             $summary[] = "\nO'chirilgan kafedralar:";
             foreach ($changes['deleted_departments'] as $dept) {
                 $summary[] = "- {$dept['name']} ({$dept['faculty']})";
             }
         }

         // Yangi kafedralar
         if (!empty($changes['new_departments'])) {
             $hasChanges = true;
             $summary[] = "\nYangi qo'shilgan kafedralar:";
             foreach ($changes['new_departments'] as $dept) {
                 $summary[] = "- {$dept['name']} ({$dept['faculty']})";
             }
         }

         if (!$hasChanges) {
             return "Jarayon yakunlandi. Hech qanday o'zgarish topilmadi.";
         }

         return implode("\n", $summary);
     }

     public function stopDepartmentsUpdate()
     {
         Cache::put('update_departments_status', 'stopped', 600);
         Log::info('Jarayon foydalanuvchi tomonidan to\'xtatildi');
         return response()->json([
             'success' => true,
             'message' => 'Jarayon to\'xtatildi',
         ]);
     }

    /**
     * O'qituvchining ma'lumotlarini HEMIS tizimidan olish va yangilash
     *
     * Bu funksiya quyidagi amallarni bajaradi:
     * 1. O'qituvchi ID raqami orqali HEMIS dan ma'lumotlarni olish
     * 2. Olingan ma'lumotlarni validatsiya qilish
     *
     * Ma'lumotlar tarkibi:
     * - Shaxsiy ma'lumotlar (ism, familiya, otasining ismi)
     * - Tug'ilgan sanasi
     * - Jinsi
     * - Akademik ma'lumotlar (ilmiy daraja, ilmiy unvon)
     * - Bandlik ma'lumotlari:
     *   * Asosiy ish joyi (11)
     *   * Ichki o'rindoshlik (12)
     *   * Tashqi o'rindoshlik (13)
     *   * Soatbay (14)
     *   * Ichki asosiy o'rindoshlik (15)
     *
     * Muhim eslatmalar:
     * - Funktsiya faqat type=teacher bo'lgan xodimlar ma'lumotini qaytaradi
     * - Ma'lumotlar HEMIS API orqali olinadi
     * - API token env faylida saqlanishi kerak
     *
     * Xatoliklar bilan ishlash:
     * - HEMIS API bilan bog'lanish xatoliklari
     * - Ma'lumotlar topilmaganda xatolik
     * - Ma'lumotlar formati noto'g'ri bo'lganda xatolik
     *
     * @param string $employeeIdNumber O'qituvchining HEMIS ID raqami
     * @return array O'qituvchi haqidagi barcha ma'lumotlar
     * @throws \Exception Quyidagi hollarda:
     *                    - HEMIS ga bog'lanib bo'lmaganda
     *                    - O'qituvchi topilmaganda
     *                    - Ma'lumotlar formati noto'g'ri bo'lganda
     *
     * @example
     * try {
     *     $employeeData = $this->getEmployeeDataFromHemis('12345678');
     *     // Ma'lumotlar bilan ishlash...
     * } catch (\Exception $e) {
     *     Log::error("O'qituvchi ma'lumotlarini olishda xatolik: " . $e->getMessage());
     * }
     */

    public function getEmployeeDataFromHemis($employeeIdNumber)
    {
        try {
            $url = env('API_HEMIS_URL') . "/rest/v1/data/employee-list?type=teacher&search=$employeeIdNumber&limit=200";
            $response = Http::withToken(env('API_HEMIS_TOKEN'))->get($url)->json();

            // Agar ma'lumot topilsa
            if ($response['data']['pagination']['totalCount'] > 0) {
                // totalCount > 1 bo'lsa, barcha ma'lumotlarni olamiz
                if ($response['data']['pagination']['totalCount'] > 1) {
                    Log::info("Xodim bir nechta joyda ishlaydi", [
                        'employee_id' => $employeeIdNumber,
                        'total_count' => $response['data']['pagination']['totalCount']
                    ]);

                    // items massividan departments ni yig'amiz
                    $departments = [];
                    foreach ($response['data']['items'] as $item) {
                        if (isset($item['department']) && isset($item['employmentForm'])) {
                            $departments[] = [
                                'department' => $item['department'],
                                'employmentForm' => $item['employmentForm']
                            ];
                        }
                    }

                    // Birinchi ma'lumotni olamiz (asosiy ma'lumotlar uchun)
                    $employeeData = $response['data']['items'][0];
                    $employeeData['departments'] = $departments;
                } else {
                    // Bitta ma'lumot bo'lsa
                    $employeeData = $response['data']['items'][0];
                    $departments = [];
                    if (isset($employeeData['department']) && isset($employeeData['employmentForm'])) {
                        $departments[] = [
                            'department' => $employeeData['department'],
                            'employmentForm' => $employeeData['employmentForm']
                        ];
                    }
                    $employeeData['departments'] = $departments;
                }

                Log::info("Xodim ma'lumotlari olindi", [
                    'employee_id' => $employeeIdNumber,
                    'departments' => $departments
                ]);

                return $employeeData;
            }

            throw new \Exception("HEMIS_EMPLOYEE_NOT_FOUND");
        } catch (\Exception $e) {
            Log::error("HEMIS API xatolik", [
                'employee_id' => $employeeIdNumber,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function checkInactiveUsers()
    {
        try {
            Log::info('Status 0 bo\'lgan foydalanuvchilarni tekshirish boshlandi');

            // Status 0 bo'lgan foydalanuvchilarni olish
            $inactiveUsers = User::where('status', 0)
                ->whereNotNull('employee_id_number')
                ->get();

            $updatedCount = 0;

            foreach ($inactiveUsers as $user) {
                try {
                    $employeeData = $this->getEmployeeDataFromHemis($user->employee_id_number);

                    if (!empty($employeeData) && !empty($employeeData['departments'])) {
                        $newDepartmentId = $this->getDepartmentId($employeeData['departments']);

                        if ($newDepartmentId) {
                            DB::transaction(function () use ($user, $newDepartmentId) {
                                $user->status = 1;
                                $user->department_id = $newDepartmentId;
                                $user->save();

                                Log::info("Status 0 bo'lgan foydalanuvchi faollashtirildi", [
                                    'user_id' => $user->id,
                                    'name' => $user->name,
                                    'new_department' => $newDepartmentId
                                ]);
                            });
                            $updatedCount++;
                        }
                    }
                } catch (\Exception $e) {
                    if ($e->getMessage() !== "HEMIS_EMPLOYEE_NOT_FOUND") {
                        Log::error("Status 0 tekshirishda xatolik", [
                            'user_id' => $user->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                    continue;
                }
            }

            Log::info("Status 0 bo'lgan foydalanuvchilarni tekshirish yakunlandi", [
                'total_checked' => $inactiveUsers->count(),
                'updated_count' => $updatedCount
            ]);

            return $updatedCount;
        } catch (\Exception $e) {
            Log::error("Status 0 tekshirishda umumiy xatolik", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getDepartmentId($departments)
    {
        try {
            $priorityOrder = [11, 15, 12];
            $departmentsByPriority = [];

            // Har bir department uchun employment code bo'yicha guruhlash
            foreach ($departments as $department) {
                if (isset($department['employmentForm']['code'])) {
                    $code = $department['employmentForm']['code'];
                    if (!isset($departmentsByPriority[$code])) {
                        $departmentsByPriority[$code] = [];
                    }
                    $departmentsByPriority[$code][] = $department;
                }
            }

            // Priority tartibida tekshirish
            foreach ($priorityOrder as $priorityCode) {
                if (isset($departmentsByPriority[$priorityCode])) {
                    // Ushbu priority code uchun birinchi departmentni olish
                    $department = $departmentsByPriority[$priorityCode][0];
                    $departmentId = $department['department']['id'];

                    // Departmentni bazada mavjudligini tekshirish
                    if (Department::where('id', $departmentId)->exists()) {
                        Log::info("Xodim uchun department topildi", [
                            'priority_code' => $priorityCode,
                            'department_id' => $departmentId,
                            'all_departments' => $departments
                        ]);
                        return $departmentId;
                    }
                }
            }

            Log::info("Tegishli bandlik turi bo'yicha kafedra topilmadi", [
                'departments' => $departments,
                'priority_order' => $priorityOrder,
                'grouped_departments' => $departmentsByPriority
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

    public function updateTeacherDepartments()
    {
        try {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Allow-Credentials: true');

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                exit(0);
            }

            // Admin/boshqaruvchilar ro'yxati - tekshirilmaydigan xodimlar
            $excludeEmployees = ['3541911085', '42342342', '3541712020'];

            Log::info('O\'qituvchilar kafedralarini yangilash jarayoni boshlandi');
            $this->sendUpdate('Jarayon boshlandi', 0);

            // Exclude qilingan xodimlarni hisobga olgan holda foydalanuvchilarni olish
            $users = User::whereNotNull('employee_id_number')
                ->whereNotIn('employee_id_number', $excludeEmployees)
                ->get();

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
                    } catch (\Exception $e) {
                        // HEMIS_EMPLOYEE_NOT_FOUND xatosi tekshiriladi
                        if ($e->getMessage() === "HEMIS_EMPLOYEE_NOT_FOUND") {
                            DB::transaction(function () use ($user) {
                                $user->status = 0;
                                $user->save();
                            });

                            Log::info("Foydalanuvchi {$user->name} statusi 0 ga o'zgartirildi chunki HEMIS da topilmadi", [
                                'employee_id' => $user->employee_id_number
                            ]);

                            $this->sendUpdate("Foydalanuvchi {$user->name} HEMIS da topilmadi, status 0 ga o'zgartirildi", round($progress));
                            $skippedCount++;
                            continue;
                        }

                        // Boshqa xatoliklar uchun
                        Log::error("HEMIS so'rovi xatoligi: {$user->name}", [
                            'error' => $e->getMessage(),
                            'user_id' => $user->id,
                            'employee_id' => $user->employee_id_number
                        ]);
                        $errorCount++;
                        continue;
                    }

                    // API javobini tekshirish
                    if (empty($employeeData)) {
                        Log::warning("Foydalanuvchi {$user->name} uchun HEMIS dan ma'lumot olinmadi", [
                            'employee_id' => $user->employee_id_number
                        ]);

                        // Ma'lumot bo'sh bo'lsa ham status 0 ga o'zgartiriladi
                        DB::transaction(function () use ($user) {
                            $user->status = 0;
                            $user->save();
                        });

                        $this->sendUpdate("Foydalanuvchi {$user->name} uchun HEMIS dan ma'lumot olinmadi, status 0 ga o'zgartirildi", round($progress));
                        $skippedCount++;
                        continue;
                    }

                    // Departments massivini tekshirish
                    if (empty($employeeData['departments'])) {
                        Log::warning("Foydalanuvchi {$user->name} uchun departments ma'lumoti yo'q", [
                            'employee_id' => $user->employee_id_number,
                            'hemis_data' => $employeeData
                        ]);

                        // Departments bo'sh bo'lsa ham status 0 ga o'zgartiriladi
                        DB::transaction(function () use ($user) {
                            $user->status = 0;
                            $user->save();
                        });

                        $this->sendUpdate("Foydalanuvchi {$user->name} uchun departments ma'lumoti yo'q, status 0 ga o'zgartirildi", round($progress));
                        $skippedCount++;
                        continue;
                    }

                    // Yangi department_id ni aniqlash
                    $newDepartmentId = $this->getDepartmentId($employeeData['departments']);

                    if (!$newDepartmentId) {
                        // Department topilmaganda status 0 ga o'zgartirish
                        DB::transaction(function () use ($user) {
                            $user->status = 0;
                            $user->save();
                        });

                        Log::warning("Foydalanuvchi {$user->name} statusi 0 ga o'zgartirildi chunki mos department topilmadi", [
                            'employee_id' => $user->employee_id_number,
                            'departments' => $employeeData['departments']
                        ]);

                        $this->sendUpdate("Foydalanuvchi {$user->name} uchun mos department topilmadi, status 0 ga o'zgartirildi", round($progress));
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
                    Log::error("Foydalanuvchini qayta ishlashda xatolik", [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $errorCount++;
                    continue;
                }
            }

            // Status 0 bo'lganlarni qayta tekshirish
            $updatedInactiveCount = $this->checkInactiveUsers();

            $summary = [
                'total' => $totalUsers,
                'success' => $successCount,
                'errors' => $errorCount,
                'skipped' => $skippedCount,
                'reactivated_users' => $updatedInactiveCount
            ];

            Log::info('Jarayon yakunlandi', $summary);

            $this->sendUpdate("Jarayon yakunlandi. Jami: {$totalUsers}, Yangilandi: {$successCount}, Xatoliklar: {$errorCount}, O'tkazib yuborildi: {$skippedCount}, Faollashtirildi: {$updatedInactiveCount}", 100);
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

    /**
     * O'qituvchilarni HEMISdan avtomatik ro'yxatdan o'tkazish
     *
     * Bu funksiya quyidagi amallarni bajaradi:
     * 1. HEMIS tizimidan barcha fakultetlar ro'yxatini oladi
     * 2. Har bir fakultet uchun:
     *    - Fakultetga biriktirilgan barcha o'qituvchilarni oladi
     *    - Har bir o'qituvchi uchun:
     *      * Tekshiradi - allaqachon ro'yxatdan o'tganmi
     *      * Yangi o'qituvchilarni avtomatik ro'yxatdan o'tkazadi
     *
     * O'qituvchilarni ro'yxatdan o'tkazish ketma-ketligi:
     * - HEMIS dan o'qituvchi ma'lumotlarini olish
     * - Kafedraga biriktirish (11, 15, 12 bandlik turlari bo'yicha)
     * - Barcha kerakli ma'lumotlarni saqlash
     *
     * Progress va natijalar real vaqtda ko'rsatiladi:
     * - Umumiy progress
     * - Joriy bajarilyotgan amal
     * - Yakuniy statistika:
     *   * Ro'yxatdan o'tganlar soni
     *   * O'tkazib yuborilganlar soni
     *   * Xatoliklar soni
     *   * Batafsil ma'lumotlar
     *
     * @return void
     * @throws \Exception HEMIS bilan bog'lanishda yoki ma'lumotlarni saqlashda xatolik yuz berganda
     */


    private function makeHemisRequest($url, $attempt = 1, $maxAttempts = 3)
    {
        try {
            if (Cache::get('registration_process_stopped')) {
                throw new \Exception('Jarayon foydalanuvchi tomonidan to\'xtatildi');
            }

            if ($attempt > 1) {
                $seconds = pow(2, $attempt);
                Log::info("API cheklov. {$seconds} soniya kutilmoqda...");
                sleep($seconds);
            }

            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => [
                        'Authorization: Bearer ' . env('API_HEMIS_TOKEN'),
                        'Content-Type: application/json',
                        'Connection: keep-alive'
                    ],
                    'timeout' => 1
                ]
            ]);

            $response = @file_get_contents($url, false, $context);
            Log::info("API Request URL: " . $url);

            if ($response === false) {
                $error = error_get_last();
                throw new \Exception('API response is empty. Error: ' . ($error['message'] ?? 'Unknown error'));
            }

            $data = json_decode($response, true);
            Log::info("API Response:", ['response' => substr(json_encode($data), 0, 1000)]);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response: ' . json_last_error_msg());
            }

            return $data;
        } catch (\Exception $e) {
            Log::warning("API so'rovida xatolik (urinish $attempt/$maxAttempts): " . $e->getMessage());

            if ($attempt < $maxAttempts && (
                strpos($e->getMessage(), '429') !== false ||
                strpos($e->getMessage(), 'timeout') !== false ||
                strpos($e->getMessage(), 'empty') !== false
            )) {
                return $this->makeHemisRequest($url, $attempt + 1, $maxAttempts);
            }

            throw $e;
        }
    }

    private function emitMessage($message, $progress)
    {
        Log::info("Sending update: $message, Progress: $progress");
        echo "data: " . json_encode(['message' => $message, 'progress' => $progress]) . "\n\n";
        ob_flush();
        flush();
    }

    private function findOrCreateUser($userDetails)
    {
        try {
            $user = User::where('employee_id_number', $userDetails['employee_id_number'])->first();

            if (!$user) {
                Log::info("Yangi foydalanuvchi yaratilmoqda: {$userDetails['full_name']}", [
                    'employee_id' => $userDetails['employee_id_number']
                ]);

                // Rasm saqlash
                $fileName = null;
                if (!empty($userDetails['picture'])) {
                    try {
                        $imageContent = file_get_contents($userDetails['picture']);
                        $fileName = 'image_' . time() . '_' . uniqid() . '.jpg';
                        $storagePath = storage_path('app/public/users/image/') . $fileName;
                        file_put_contents($storagePath, $imageContent);
                        Log::info("Rasm saqlandi: $fileName");
                    } catch (\Exception $e) {
                        Log::error("Rasm saqlashda xatolik: " . $e->getMessage());
                    }
                }

                // Kafedra tekshirish va yaratish
                $departmentId = null;
                if (!empty($userDetails['departments'])) {
                    foreach ($userDetails['departments'] as $dept) {
                        $departmentFromHemis = $dept['department'];

                        // Kafedrani bazadan izlash
                        $department = Department::find($departmentFromHemis['id']);

                        if (!$department) {
                            // Agar kafedra topilmasa, fakultetni topishga harakat qilamiz
                            $facultyUrl = env('API_HEMIS_URL') . '/rest/v1/data/department?id=' . $departmentFromHemis['id'];
                            try {
                                $response = $this->makeHemisRequest($facultyUrl);
                                $hemisDepartment = $response['data'] ?? null;

                                if ($hemisDepartment) {
                                    // Agar fakultet ID berilgan bo'lsa
                                    $facultyId = $hemisDepartment['parent'] ?? null;

                                    // Yangi kafedra yaratish
                                    $department = Department::create([
                                        'id' => $departmentFromHemis['id'],
                                        'name' => $departmentFromHemis['name'],
                                        'faculty_id' => $facultyId
                                    ]);

                                    Log::info("Yangi kafedra yaratildi:", [
                                        'id' => $department->id,
                                        'name' => $department->name,
                                        'faculty_id' => $facultyId
                                    ]);
                                }
                            } catch (\Exception $e) {
                                Log::error("Kafedra ma'lumotlarini olishda xatolik: " . $e->getMessage());
                            }
                        }

                        if ($department) {
                            // Employment form kodini tekshirish
                            $employmentFormCode = $dept['employmentForm']['code'] ?? null;
                            if (in_array($employmentFormCode, ['11', '15', '12'])) {
                                $departmentId = $department->id;
                                break;
                            }
                        }
                    }
                }

                if (!$departmentId) {
                    throw new \Exception('Kafedra aniqlanmadi: ' . json_encode($userDetails['departments']));
                }

                $email = !empty($userDetails['email']) ?
                    $userDetails['email'] :
                    $userDetails['employee_id_number'] . '@cspu.uz';

                $user = User::create([
                    'employee_id_number' => $userDetails['employee_id_number'],
                    'name' => $userDetails['full_name'],
                    'first_name' => $userDetails['first_name'],
                    'second_name' => $userDetails['second_name'],
                    'third_name' => $userDetails['third_name'],
                    'gender_code' => $userDetails['gender']['code'] ?? null,
                    'gender_name' => $userDetails['gender']['name'] ?? null,
                    'birth_date' => !empty($userDetails['birth_date']) ? date('Y-m-d', $userDetails['birth_date']) : null,
                    'image' => $fileName,
                    'year_of_enter' => $userDetails['year_of_enter'] ?? null,
                    'academicDegree_code' => $userDetails['academicDegree']['code'] ?? null,
                    'academicDegree_name' => $userDetails['academicDegree']['name'] ?? null,
                    'academicRank_code' => $userDetails['academicRank']['code'] ?? null,
                    'academicRank_name' => $userDetails['academicRank']['name'] ?? null,
                    'department_id' => $departmentId,
                    'login' => $userDetails['login'] ?? $userDetails['employee_id_number'],
                    'uuid' => Str::uuid(),
                    'employee_id' => $userDetails['employee_id'],
                    'user_type' => 'teacher',
                    'email' => $email,
                    'phone' => $userDetails['phone'] ?? null,
                    'password' => Hash::make(Str::random(16)),
                    'status' => 1,
                ]);

                Log::info("Foydalanuvchi yaratildi", [
                    'id' => $user->id,
                    'name' => $user->name,
                    'department_id' => $departmentId,
                    'employment_form' => $userDetails['departments'][0]['employmentForm'] ?? null
                ]);
            }

            return $user;
        } catch (\Exception $e) {
            Log::error("Foydalanuvchi yaratishda xatolik", [
                'error' => $e->getMessage(),
                'user_details' => $userDetails['employee_id_number'],
                'departments' => $userDetails['departments'] ?? null
            ]);
            throw $e;
        }
    }

    private function updateDepartmentName($department, $hemisDepartment, $type = 'kafedra')
    {
        if ($department->name !== $hemisDepartment['name']) {
            Log::info("{$type} nomi yangilanmoqda:", [
                'id' => $department->id,
                'old_name' => $department->name,
                'new_name' => $hemisDepartment['name']
            ]);

            $department->name = $hemisDepartment['name'];
            $department->save();

            return true;
        }
        return false;
    }

    public function registerAllTeachers()
    {
        try {
            Cache::forget('registration_process_stopped');

            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            $this->emitMessage('Jarayon boshlandi', 0);

            // Fakultetlarni olish
            Log::info("HEMIS dan fakultetlar ro'yxati so'ralmoqda...");
            $this->emitMessage('Fakultetlar ro\'yxati yuklanmoqda...', 5);

            $fakultetlarUrl = env('API_HEMIS_URL') . '/rest/v1/data/department-list?' . http_build_query([
                'limit' => 200,
                'active' => 1,
                '_structure_type' => 11,
                'localityType.name' => 'Mahalliy',
                'structureType.name' => 'Fakultet'
            ]);

            $fakultetlar = $this->makeHemisRequest($fakultetlarUrl)['data']['items'];
            Log::info("Fakultetlar soni: " . count($fakultetlar));
            $this->emitMessage('O\'qituvchilar ro\'yxati yuklanmoqda...', 10);

            $totalRegistered = 0;
            $totalSkipped = 0;
            $totalErrors = 0;
            $registrationResults = [
                'registered' => [],
                'skipped' => [],
                'errors' => [],
                'department_updates' => []
            ];

            foreach ($fakultetlar as $index => $fakultet) {
                if (Cache::get('registration_process_stopped')) {
                    Log::info("Jarayon foydalanuvchi tomonidan to'xtatildi");
                    $this->emitMessage("Jarayon to'xtatildi", 100);
                    die();
                }

                $progress = 10 + (($index + 1) / count($fakultetlar)) * 85;

                Log::info("\n==== {$fakultet['name']} fakulteti o'qituvchilari tekshirilmoqda ====");
                $this->emitMessage("Fakultet qayta ishlanmoqda: {$fakultet['name']}", round($progress));

                // Fakultet nomini tekshirish va yangilash
                $existingFaculty = Department::where('id', $fakultet['id'])->first();
                if ($existingFaculty) {
                    if ($this->updateDepartmentName($existingFaculty, $fakultet, 'fakultet')) {
                        $registrationResults['department_updates'][] = [
                            'type' => 'faculty',
                            'old_name' => $existingFaculty->getOriginal('name'),
                            'new_name' => $fakultet['name']
                        ];
                    }
                } else {
                    Department::create([
                        'id' => $fakultet['id'],
                        'name' => $fakultet['name'],
                        'faculty_id' => null
                    ]);
                    Log::info("Yangi fakultet yaratildi: {$fakultet['name']}");
                }

                // Har bir fakultet uchun kafedralarni olish
                $kafedralarUrl = env('API_HEMIS_URL') . '/rest/v1/data/department-list?' . http_build_query([
                    'limit' => 200,
                    'active' => 1,
                    '_structure_type' => 12,
                    'parent' => $fakultet['id']
                ]);

                $kafedralar = $this->makeHemisRequest($kafedralarUrl)['data']['items'];

                foreach ($kafedralar as $kafedra) {
                    if (Cache::get('registration_process_stopped')) {
                        Log::info("Jarayon foydalanuvchi tomonidan to'xtatildi");
                        $this->emitMessage("Jarayon to'xtatildi", 100);
                        die();
                    }

                    Log::info("-- {$kafedra['name']} kafedrasi tekshirilmoqda --");

                    // Kafedra nomini tekshirish va yangilash
                    $existingDepartment = Department::where('id', $kafedra['id'])->first();
                    if ($existingDepartment) {
                        if ($this->updateDepartmentName($existingDepartment, $kafedra, 'kafedra')) {
                            $registrationResults['department_updates'][] = [
                                'type' => 'department',
                                'old_name' => $existingDepartment->getOriginal('name'),
                                'new_name' => $kafedra['name']
                            ];
                        }
                    } else {
                        Department::create([
                            'id' => $kafedra['id'],
                            'name' => $kafedra['name'],
                            'faculty_id' => $fakultet['id']
                        ]);
                        Log::info("Yangi kafedra yaratildi: {$kafedra['name']}");
                    }

                    // Employment formlar bo'yicha o'qituvchilarni olish
                    $employmentForms = [11, 15, 12]; // Asosiy shtat, O'rindoshlik (asosiy), O'rindoshlik (tashqi)
                    $processedTeachers = []; // Qayta ishlanganlarni kuzatish uchun

                    foreach ($employmentForms as $employmentForm) {
                        $teachersUrl = env('API_HEMIS_URL') . '/rest/v1/data/employee-list?' . http_build_query([
                            'type' => 'teacher',
                            'limit' => 200,
                            '_department' => $kafedra['id'],
                            '_employment_form' => $employmentForm
                        ]);

                        try {
                            $response = $this->makeHemisRequest($teachersUrl);
                            $teachers = $response['data']['items'] ?? [];

                            Log::info("Kafedradan olingan o'qituvchilar soni (Employment Form {$employmentForm}): " . count($teachers));

                            foreach ($teachers as $teacher) {
                                if (Cache::get('registration_process_stopped')) {
                                    Log::info("Jarayon foydalanuvchi tomonidan to'xtatildi");
                                    $this->emitMessage("Jarayon to'xtatildi", 100);
                                    die();
                                }

                                usleep(100000); // Rate limiting

                                $employeeId = $teacher['employee_id_number'];

                                // Agar bu o'qituvchi oldin qayta ishlangan bo'lsa, o'tkazib yuboramiz
                                if (in_array($employeeId, $processedTeachers)) {
                                    Log::info("⏩ O'tkazib yuborildi: {$employeeId} - Boshqa employment form bilan allaqachon qayta ishlangan");
                                    continue;
                                }

                                $fullName = $teacher['short_name'] ??
                                    ($teacher['name'] ??
                                        ($teacher['full_name'] ??
                                            "{$teacher['second_name']} {$teacher['first_name']} {$teacher['third_name']}"));

                                // Mavjud foydalanuvchini tekshirish
                                $existingUser = User::where('employee_id_number', $employeeId)->first();

                                if ($existingUser) {
                                    $totalSkipped++;
                                    Log::info("⏩ O'tkazib yuborildi: {$fullName} ({$employeeId}) - Allaqachon ro'yxatdan o'tgan");
                                    $registrationResults['skipped'][] = [
                                        'name' => $fullName,
                                        'department' => $kafedra['name'],
                                        'faculty' => $fakultet['name'],
                                        'reason' => 'Allaqachon ro\'yxatdan o\'tgan'
                                    ];
                                    continue;
                                }

                                try {
                                    Log::info("🔄 Ro'yxatdan o'tkazilmoqda: {$fullName} ({$employeeId}) - Employment Form: {$employmentForm}");

                                    // Employment form ma'lumotini qo'shamiz
                                    $employmentFormName = match ($employmentForm) {
                                        11 => 'Asosiy shtat',
                                        15 => 'O\'rindoshlik (asosiy)',
                                        12 => 'O\'rindoshlik (tashqi)',
                                        default => 'Noma\'lum'
                                    };

                                    $teacherData = [
                                        'employee_id_number' => $employeeId,
                                        'full_name' => $fullName,
                                        'first_name' => $teacher['first_name'] ?? '',
                                        'second_name' => $teacher['second_name'] ?? '',
                                        'third_name' => $teacher['third_name'] ?? '',
                                        'gender' => $teacher['gender'] ?? ['code' => null, 'name' => null],
                                        'birth_date' => $teacher['birth_date'] ?? null,
                                        'year_of_enter' => $teacher['year_of_enter'] ?? null,
                                        'academicDegree' => $teacher['academicDegree'] ?? ['code' => null, 'name' => null],
                                        'academicRank' => $teacher['academicRank'] ?? ['code' => null, 'name' => null],
                                        'login' => $teacher['employee_id_number'],
                                        'uuid' => Str::uuid(),
                                        'employee_id' => $teacher['id'],
                                        'email' => $teacher['email'] ?? $employeeId . '@cspu.uz',
                                        'phone' => $teacher['phone'] ?? null,
                                        'picture' => $teacher['image'] ?? null,
                                        'departments' => [
                                            [
                                                'department' => [
                                                    'id' => $kafedra['id'],
                                                    'name' => $kafedra['name']
                                                ],
                                                'employmentForm' => [
                                                    'code' => (string)$employmentForm,
                                                    'name' => $employmentFormName
                                                ]
                                            ]
                                        ]
                                    ];

                                    $user = $this->findOrCreateUser($teacherData);

                                    if ($user) {
                                        $totalRegistered++;
                                        $processedTeachers[] = $employeeId;
                                        $registrationResults['registered'][] = [
                                            'name' => $fullName,
                                            'department' => $kafedra['name'],
                                            'faculty' => $fakultet['name'],
                                            'employment_form' => $employmentFormName
                                        ];
                                    }
                                } catch (\Exception $e) {
                                    $totalErrors++;
                                    Log::error("O'qituvchi yaratishda xatolik:", [
                                        'teacher_data' => $teacherData ?? [],
                                        'error' => $e->getMessage(),
                                        'trace' => $e->getTraceAsString()
                                    ]);
                                    $registrationResults['errors'][] = [
                                        'name' => $fullName,
                                        'department' => $kafedra['name'],
                                        'faculty' => $fakultet['name'],
                                        'employment_form' => $employmentFormName,
                                        'error' => $e->getMessage()
                                    ];
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error("Kafedra o'qituvchilarini olishda xatolik (Employment Form {$employmentForm}): {$kafedra['name']} - " . $e->getMessage());
                            continue;
                        }
                    }
                }

                Log::info("==== {$fakultet['name']} fakulteti yakunlandi ====");
                Log::info("Fakultet natijalari: ✅{$totalRegistered}, ⏩{$totalSkipped}, ❌{$totalErrors}");
            }

            // Yakuniy hisobot
            $this->generateAndSendSummary($registrationResults, $totalRegistered, $totalSkipped, $totalErrors);

            die();
        } catch (\Exception $e) {
            Log::error('🚫 Umumiy xatolik: ' . $e->getMessage());
            $this->emitMessage('Xatolik yuz berdi: ' . $e->getMessage(), 100);
            die();
        }
    }

    private function generateAndSendSummary($results, $totalRegistered, $totalSkipped, $totalErrors)
    {
        $summary = "Ro'yxatdan o'tkazish jarayoni yakunlandi\n\n";
        $summary .= "Umumiy natijalar:\n";
        $summary .= "✅ Muvaffaqiyatli: {$totalRegistered}\n";
        $summary .= "⏩ O'tkazib yuborildi: {$totalSkipped}\n";
        $summary .= "❌ Xatoliklar: {$totalErrors}\n\n";

        if (!empty($results['registered'])) {
            $summary .= "\nMuvaffaqiyatli ro'yxatdan o'tkazilganlar:\n";
            foreach ($results['registered'] as $item) {
                $summary .= "- {$item['name']} ({$item['faculty']}, {$item['department']})\n";
            }
        }

        if (!empty($results['errors'])) {
            $summary .= "\nXatolik yuz bergan o'qituvchilar:\n";
            foreach ($results['errors'] as $item) {
                $summary .= "- {$item['name']} ({$item['faculty']}, {$item['department']}): {$item['error']}\n";
            }
        }

        Log::info($summary);
        $this->emitMessage($summary, 100);
    }

    public function stopTeachersRegistration()
    {
        try {
            Cache::put('registration_process_stopped', true, 600);
            Log::info('Jarayon foydalanuvchi tomonidan to\'xtatildi');
            return response()->json([
                'success' => true,
                'message' => 'Jarayon to\'xtatildi',
            ]);
        } catch (\Exception $e) {
            Log::error('To\'xtatishda xatolik: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'To\'xtatishda xatolik: ' . $e->getMessage()
            ], 500);
        }
    }
}
