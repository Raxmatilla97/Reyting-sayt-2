<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KpiSubmission;

class KpiReviewController extends Controller
{
    public function index()
    {
        $submissions = KpiSubmission::with('user')
            ->where('status', 'pending')
            ->get();
        return view('dashboard.kpi.index', compact('submissions'));
    }

    public function review(Request $request, KpiSubmission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'points' => 'required_if:status,approved|nullable|numeric|min:0',
            'admin_comment' => 'required|string',
        ]);

        $submission->update($validated);

        return redirect()->route('dashboard.kpi.index')
            ->with('success', 'KPI submission reviewed successfully');
    }
}
