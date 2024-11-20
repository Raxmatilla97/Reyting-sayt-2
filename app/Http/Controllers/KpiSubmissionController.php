<?php

namespace App\Http\Controllers;

use App\Models\KpiCriteria;
use Illuminate\Http\Request;
use App\Models\KpiSubmission;

class KpiSubmissionController extends Controller
{
    public function index()
    {
        $submissions = KpiSubmission::with(['criteria'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('dashboard.kpi.index', compact('submissions'));
    }

    public function create()
    {
        // KPI kategoriyalarini olish
        $categories = [
            'teaching' => "O'quv va o'quv-uslubiy ishlar",
            'research' => "Ilmiy va innovatsiyalarga oid ishlar",
            'international' => "Xalqaro hamkorlikka oid ishlar",
            'spiritual' => "Ma'naviy-ma'rifiy ishlar"
        ];

        // Barcha mezonlarni olish
        $criteria = KpiCriteria::orderBy('sort_order')->get();

        return view('dashboard.kpi.create', compact('categories', 'criteria'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'criteria_id' => 'required|exists:kpi_criteria,id',
            'description' => 'required|string',
            'proof_file' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('proof_files', 'public');
            $validated['proof_file'] = $path;
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        KpiSubmission::create($validated);

        return redirect()->route('kpi.index')->with('success', 'KPI submission created successfully');
    }

    public function getCriteria($category)
    {
        $criteria = KpiCriteria::where('category', $category)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'max_points', 'required_proof']);

        return response()->json($criteria);
    }
}
