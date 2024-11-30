<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\KpiCriteria;
use Illuminate\Http\Request;
use App\Models\KpiSubmission;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KpiReviewController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;

        // Statistika uchun asosiy query
        $statsQuery = KpiSubmission::query();

        // Agar admin bo'lmasa, statistika faqat unga tegishli ma'lumotlar uchun hisoblanadi
        if (!$isAdmin && $user->is_kpi_reviewer) {
            $statsQuery->where(function ($q) use ($user) {
                $q->whereHas('user', function ($userQuery) use ($user) {
                    $userQuery->whereHas('department', function ($dq) use ($user) {
                        $dq->whereHas('faculty', function ($fq) use ($user) {
                            $fq->where('id', $user->kpi_faculty_id);
                        });
                    });
                })
                    ->whereIn('category', $user->kpi_review_categories ?? []);
            });
        }

        // Statistikani hisoblash
        $statistics = [
            'total' => $statsQuery->count(),
            'pending' => $statsQuery->clone()->where('status', 'pending')->count(),
            'approved' => $statsQuery->clone()->where('status', 'approved')->count(),
            'rejected' => $statsQuery->clone()->where('status', 'rejected')->count(),
        ];

        // Ma'lumotlar uchun asosiy query
        $query = KpiSubmission::with(['user.department.faculty', 'criteria'])
            ->latest();

        // Administrator emas bo'lsa (tekshiruvchi)
        if (!$isAdmin && $user->is_kpi_reviewer) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('user', function ($userQuery) use ($user) {
                    $userQuery->whereHas('department', function ($dq) use ($user) {
                        $dq->whereHas('faculty', function ($fq) use ($user) {
                            $fq->where('id', $user->kpi_faculty_id);
                        });
                    });
                })
                    ->whereIn('category', $user->kpi_review_categories ?? [])
                    ->where(function ($subQ) use ($user) {
                        $subQ->where('status', 'pending')  // Kutilayotgan ma'lumotlar
                            ->orWhere('inspector_id', $user->id); // Yoki o'zi tekshirgan ma'lumotlar
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category filter - faqat ruxsat berilgan kategoriyalarni ko'rsatish
        if (!$isAdmin) {
            $categories = array_intersect_key(
                KpiCriteria::categories(),
                array_flip($user->kpi_review_categories ?? [])
            );
        } else {
            $categories = KpiCriteria::categories();
        }

        if (
            $request->filled('category') &&
            ($isAdmin || in_array($request->category, $user->kpi_review_categories ?? []))
        ) {
            $query->where('category', $request->category);
        }

        $submissions = $query->paginate(15);

        return view('dashboard.kpi.admin.index', compact(
            'submissions',
            'categories',
            'statistics',
            'isAdmin'
        ));
    }


    public function review(Request $request, KpiSubmission $kpiSubmission)
    {
        $user = Auth::user();

        // Ruxsatni tekshirish
        if (!$user->is_admin && !$user->is_kpi_reviewer) {
            return back()->with('error', 'Bu arizani ko\'rib chiqish uchun ruxsat yo\'q');
        }

        // Agar tekshiruvchi bo'lsa qo'shimcha huquqlarni tekshirish
        if (!$user->is_admin && $user->is_kpi_reviewer) {
            // Fakultet va kategoriya tekshirish
            $hasPermission = $kpiSubmission->user->department->faculty->id === $user->kpi_faculty_id &&
                in_array($kpiSubmission->category, $user->kpi_review_categories ?? []);

            if (!$hasPermission) {
                return back()->with('error', 'Bu arizani ko\'rib chiqish uchun ruxsat yo\'q');
            }
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'points' => 'required_if:status,approved|nullable|numeric|min:0',
            'admin_comment' => 'required|string',
        ]);

        // Tekshiruvchi ID sini saqlash
        $validated['inspector_id'] = $user->id;

        $kpiSubmission->update($validated);

        return redirect()->route('admin.kpi.index')
            ->with('success', 'Ariza muvaffaqiyatli ko\'rib chiqildi');
    }



    public function reviewersIndex(Request $request)
    {
        $user = auth()->user();
        $query = User::with(['department.faculty']);

        if ($request->filled('search')) {
            $search = $request->search;
            $term = '%' . $search . '%';

            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', $term)
                    ->orWhere('second_name', 'like', $term)
                    ->orWhere('third_name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('employee_id_number', 'like', $term);
            });

            if (!$user->is_admin && $user->department && $user->department->faculty_id) {
                $facultyId = $user->department->faculty_id;
                $query->whereHas('department', function ($q) use ($facultyId) {
                    $q->where('faculty_id', $facultyId);
                });
            }
        } else {
            $query->where('is_kpi_reviewer', true)
                ->when(!$user->is_admin && $user->department && $user->department->faculty_id, function ($q) use ($user) {
                    $facultyId = $user->department->faculty_id;
                    $q->whereHas('department', function ($q) use ($facultyId) {
                        $q->where('faculty_id', $facultyId);
                    });
                });
        }

        $users = $query->orderBy('first_name')->paginate(15);
        $reviewerCount = User::where('is_kpi_reviewer', true)->count();
        $faculties = Faculty::where('status', 1)->get();
        $categories = [
            'teaching' => "O'quv va o'quv-uslubiy ishlar",
            'research' => "Ilmiy va innovatsiyalarga oid ishlar",
            'international' => "Xalqaro hamkorlikga oid ishlar",
            'spiritual' => "Ma'naviy-ma'rifiy ishlar"
        ];



        return view('dashboard.kpi.admin.reviewer_manager', compact('users', 'reviewerCount', 'categories', 'faculties'));
    }




    public function search(Request $request)
    {
        Log::info('Search request received', ['term' => $request->search]);

        $search = $request->get('search');
        $term = '%' . $search . '%';

        $users = User::where(function ($q) use ($term) {
            $q->where('first_name', 'like', $term)
                ->orWhere('second_name', 'like', $term)
                ->orWhere('third_name', 'like', $term)
                ->orWhere('email', 'like', $term);
        })
            ->with(['department.faculty'])
            ->take(10)
            ->get();

        $formatted = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->second_name . ' ' . $user->third_name,
                'email' => $user->email,
                'department' => [
                    'name' => $user->department?->name,
                    'faculty' => [
                        'name' => $user->department?->faculty?->name
                    ]
                ]
            ];
        });

        Log::info('Search results', ['count' => $formatted->count()]);

        return response()->json($formatted);
    }

    public function reviewersUpdate(Request $request, User $user)
    {
        try {
            // Agar kategoriyalar bo'sh bo'lsa yoki umuman yuborilmagan bo'lsa
            if (empty($request->categories)) {
                $user->update([
                    'is_kpi_reviewer' => false,
                    'kpi_review_categories' => null,
                    'kpi_faculty_id' => null
                ]);
            } else {
                $user->update([
                    'is_kpi_reviewer' => true,
                    'kpi_review_categories' => $request->categories
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tekshiruvchi ma\'lumotlari yangilandi',
                'is_kpi_reviewer' => $user->is_kpi_reviewer,
                'categories' => $user->kpi_review_categories
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating reviewer categories', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi'
            ], 500);
        }
    }

    public function updateUserDepartment(Request $request, User $user)
    {
        $department = Department::where('faculty_id', $request->faculty_id)->first();

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Fakultetda bo\'lim topilmadi'
            ], 400);
        }

        $user->update([
            'department_id' => $department->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foydalanuvchi fakultetga muvaffaqiyatli biriktirildi'
        ]);
    }

    public function updateUserFaculty(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'faculty_id' => 'required|exists:faculties,id'
            ]);

            Log::info('Faculty update request', [
                'user_id' => $user->id,
                'faculty_id' => $validated['faculty_id'],
                'request_data' => $request->all()
            ]);

            // Foydalanuvchini KPI fakultetga biriktirish
            $user->update([
                'kpi_faculty_id' => $validated['faculty_id'],
                'is_kpi_reviewer' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foydalanuvchi fakultetga muvaffaqiyatli biriktirildi',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->second_name . ' ' . $user->third_name,
                    'email' => $user->email,
                    'department' => [
                        'faculty' => [
                            'name' => Faculty::find($validated['faculty_id'])->name
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Update error', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getFacultyUsers(Faculty $faculty)
    {
        $users = User::whereHas('department', function ($query) use ($faculty) {
            $query->where('faculty_id', $faculty->id);
        })->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->second_name . ' ' . $user->third_name,
                'email' => $user->email
            ];
        });

        return response()->json($users);
    }

    public function getUserDetails(User $user)
    {
        $user->load('department.faculty');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->second_name . ' ' . $user->third_name,
                'email' => $user->email,
                'is_kpi_reviewer' => $user->is_kpi_reviewer,
                'kpi_review_categories' => $user->kpi_review_categories,
                'department' => [
                    'name' => $user->department?->name,
                    'faculty' => [
                        'name' => $user->department?->faculty?->name
                    ]
                ]
            ],
            'categories' => [
                'teaching' => "O'quv va o'quv-uslubiy ishlar",
                'research' => "Ilmiy va innovatsiyalarga oid ishlar",
                'international' => "Xalqaro hamkorlikga oid ishlar",
                'spiritual' => "Ma'naviy-ma'rifiy ishlar"
            ]
        ]);
    }
}
