<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KpiCriteria;

class KpiCriteriaController extends Controller
{
    public function index()
    {
        $criteria = KpiCriteria::orderBy('sort_order')->paginate(10);
        return view('dashboard.kpi.admin.criteria.index', compact('criteria'));
    }

    public function create()
    {
        $categories = KpiCriteria::categories();
        return view('dashboard.kpi.admin.criteria.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'max_points' => 'required|numeric|min:0',
            'calculation_method' => 'required|string',
            'evaluation_period' => 'required|string',
            'required_proof' => 'nullable|string',
            'sort_order' => 'required|integer|min:0'
        ]);

        KpiCriteria::create($validated);

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Mezon muvaffaqiyatli yaratildi');
    }

    public function edit(KpiCriteria $criterion)
    {
        $categories = KpiCriteria::categories();
        return view('dashboard.kpi.admin.criteria.edit', compact('criterion', 'categories'));
    }

    public function update(Request $request, KpiCriteria $criterion)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'max_points' => 'required|numeric|min:0',
            'calculation_method' => 'required|string',
            'evaluation_period' => 'required|string',
            'required_proof' => 'nullable|string',
            'sort_order' => 'required|integer|min:0'
        ]);

        $criterion->update($validated);

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Mezon muvaffaqiyatli yangilandi');
    }

    public function destroy(KpiCriteria $criterion)
    {
        $criterion->delete();

        return redirect()->route('admin.criteria.index')
            ->with('success', 'Mezon muvaffaqiyatli o\'chirildi');
    }
}
