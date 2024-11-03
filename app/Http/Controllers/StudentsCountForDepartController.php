<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\StudentsCountForDepart;

class StudentsCountForDepartController extends Controller
{
    protected $apiUrl;
    protected $apiToken;

    public function __construct()
    {
        // URL ni to'g'irlaymiz
        $this->apiUrl = 'https://student.cspi.uz';
        $this->apiToken = env('API_HEMIS_TOKEN');
    }

    public function updateStudentCounts(Request $request)
    {
        try {
            $departments = Department::where('status', 1)->get();
            $updatedCount = 0;
            $errors = [];

            foreach ($departments as $department) {
                try {
                    $response = Http::withToken($this->apiToken)
                        ->get($this->apiUrl . "/rest/v1/data/student-list?limit=200&_education_type=11&_department=$department->id&_student_status=11");

                    $data = $response->json();
                    $totalCount = $data['data']['pagination']['totalCount'] ?? 0;

                    // Log faqat muhim ma'lumotlarni
                    Log::info("Kafedra: {$department->name} | ID: {$department->id} | Talabalar soni: {$totalCount}");

                    StudentsCountForDepart::updateOrCreate(
                        ['departament_id' => $department->id],
                        [
                            'number' => $totalCount,
                            'status' => 1,
                            'updated_at' => now()
                        ]
                    );

                    $updatedCount++;
                    usleep(200000);

                } catch (\Exception $e) {
                    $errorMessage = "Xatolik: {$department->name} (ID: {$department->id}) - " . $e->getMessage();
                    $errors[] = $errorMessage;
                    Log::error($errorMessage);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$updatedCount} ta kafedra ma'lumotlari yangilandi",
                'updated' => $updatedCount,
                'total' => $departments->count(),
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error("Xatolik yuz berdi: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Xatolik yuz berdi: " . $e->getMessage(),
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }
}
