<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculties = Faculty::with('departments.point_user_deportaments')->paginate(15);

        foreach ($faculties as $faculty) {
            $faculty->totalPoints = 0;
            foreach ($faculty->departments as $department) {
                foreach ($department->point_user_deportaments as $test) {
                    $faculty->totalPoints += $test->point;
                }
            }
        }
        return view('livewire.pages.dashboard.faculty.index', compact('faculties'));
    }

    public function facultyShow($slug)
    {
        $faculty = Faculty::where('slug', $slug)->firstOrFail();

        return view('livewire.pages.dashboard.faculty.show', compact('faculty'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Faculty $faculty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faculty $faculty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        //
    }
}
