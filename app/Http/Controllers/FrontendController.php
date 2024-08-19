<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Faculty;
use App\Models\PointUserDeportament;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fakultetlar statusi 1 bo'lganlarini olamiz
        $faculties = Faculty::where('status', 1)->get();

        // Har bir fakultet uchun tegishli bo'limlar va ularning point yig'indisini hisoblash
        $facultiesWithPoints = $faculties->map(function ($faculty) {
            $faculty->total_points = $faculty->departments->sum(function ($department) {
                return $department->point_user_deportaments->sum('point');
            });
            return $faculty;
        });

        // Top 10 fakultetlarni olamiz
        $topFaculties = $facultiesWithPoints->sortByDesc('total_points')->take(3);

        //-----------------------------------------------------------//

        // Har bir fakultet uchun tegishli bo'limlar va ularning point yig'indisini hisoblash
       // Har bir fakultet uchun tegishli bo'limlar va ularning point yig'indisini hisoblash
    $departmentsWithPoints = collect();
    $usersWithPoints = collect();

    foreach ($faculties as $faculty) {
        foreach ($faculty->departments as $department) {
            // Bo'limning umumiy ballini hisoblash va unga qo'shish
            $department->total_points = $department->point_user_deportaments->sum('point');
            $departmentsWithPoints->push($department);

            // Har bir bo'lim uchun foydalanuvchilarning ballarini hisoblash va ularni o'ziga qo'shish
            foreach ($department->Employee as $user) {
                $user->total_points = $user->department->point_user_deportaments->sum('point');
                $usersWithPoints->push($user);
            }
        }
    }

    // Bo'limlarni top 10 ga ajratish
    $topDepartments = $departmentsWithPoints->sortByDesc('total_points')->take(5);

    // Foydalanuvchilarni top 10 ga ajratish
    $topUsers = $usersWithPoints->sortByDesc('total_points')->take(10);

        //-----------------------------------------------------------//



        return view('welcome', compact('topFaculties', 'topDepartments', 'topUsers'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
