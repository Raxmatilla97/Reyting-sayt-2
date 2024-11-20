<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KpiSubmission;
use App\Models\KpiCriteria;

class KpiReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = KpiSubmission::with(['user', 'criteria'])
        ->latest();

    // Filter by status
    if ($request->has('status')) {
        $query->where('status', $request->status);
    }

    // Filter by category
    if ($request->has('category')) {
        $query->where('category', $request->category);
    }

    $submissions = $query->paginate(15);
    $categories = KpiCriteria::categories();

               return view('dashboard.kpi.admin.index', compact('submissions', 'categories'));

    }

    public function review(Request $request, KpiSubmission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'points' => 'required_if:status,approved|nullable|numeric|min:0',
            'admin_comment' => 'required|string',
        ]);

        $submission->update($validated);

        return redirect()->route('admin.kpi.index')
            ->with('success', 'KPI submission reviewed successfully');
    }
}
