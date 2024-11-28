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

        $query = KpiSubmission::with(['user', 'criteria'])
            ->latest();

        // Tekshiruvchi uchun filter
        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('inspector_id', $user->id)
                    ->whereIn('category', $user->kpi_review_categories ?? [])
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('department_id', $user->department_id);
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

        // Statistics
        $statistics = [
            'total' => $query->count(),
            'pending' => $query->clone()->where('status', 'pending')->count(),
            'approved' => $query->clone()->where('status', 'approved')->count(),
            'rejected' => $query->clone()->where('status', 'rejected')->count(),
        ];

        $submissions = $query->paginate(15);

        // Format dates
        $submissions->each(function ($submission) {
            $submission->formatted_date = Carbon::parse($submission->created_at)->diffForHumans();
        });

        return view('dashboard.kpi.admin.index', compact(
            'submissions',
            'categories',
            'statistics',
            'isAdmin'
        ));
    }

    public function review(Request $request, KpiSubmission $submission)
    {
        $user = Auth::user();

        // Ruxsatni tekshirish
        if (
            !$user->is_admin &&
            ($submission->inspector_id !== $user->id ||
                !in_array($submission->category, $user->kpi_review_categories ?? []) ||
                $submission->user->department_id !== $user->department_id)
        ) {
            return back()->with('error', 'Bu arizani ko\'rib chiqish uchun ruxsat yo\'q');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'points' => 'required_if:status,approved|nullable|numeric|min:0',
            'admin_comment' => 'required|string',
        ]);

        $submission->update($validated);

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
        $faculties = Faculty::all();
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
            $user->update([
                'is_kpi_reviewer' => !empty($request->categories),
                'kpi_review_categories' => $request->categories ?? []
            ]);

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

        $department = Department::where('faculty_id', $validated['faculty_id'])
            ->first();

        if (!$department) {
            Log::error('No department found', ['faculty_id' => $validated['faculty_id']]);
            return response()->json([
                'success' => false,
                'message' => 'Fakultetda bo\'lim topilmadi'
            ], 404);
        }

        $user->update([
            'department_id' => $department->id,
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
                        'name' => $department->faculty->name
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
