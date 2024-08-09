<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use App\Models\PointUserDeportament;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;

class PointUserDeportamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function list(Request $request)
    {
        if ($request->filled('status')) {
            if ($request->status == 'all') {
                $request->status = "all";
            } elseif ($request->status == 'must_be_confirmed') {
                $request->status = "3";
            } elseif ($request->status == 'approved') {
                $request->status = "1";
            } elseif ($request->status == 'rejected') {
                $request->status = "0";
            }
            // dd($request->category);
        }

        $form_info = [
            'category' => $request->get('status'),
            'name' => $request->get('name'),
            'sort' => $request->get('sort'),
            'start_data' => $request->get('start_data'),
            'end_data' => $request->get('end_data'),
        ];

        $start_date = DateTime::createFromFormat('m/d/Y', $request->input('start_data'));
        $end_date = DateTime::createFromFormat('m/d/Y', $request->input('end_data   '));


        $filter = PointUserDeportament::whereNotNull('status')->get();

        // Agar name parametri mavjud bo'lsa, shu nomga mos keladigan userlarni qidirish
        if ($request->filled('name') || $request->filled('category') || $request->filled('sort') || $request->filled('start_data') && $request->filled('end_data')) {

            $murojatlar = PointUserDeportament::when($request->filled('status') && $request->status != "all", function (Builder $query) use ($request) {
                $query->where('status', $request->status);
            })

            // Search for names if requested
            ->when($request->filled('name'), function (Builder $query) use ($request) {
                $name = '%' . $request->name . '%'; // Escape for LIKE queries
                $query->where(function (Builder $q) use ($name) {
                    $q->whereHas('employee', function (Builder $q) use ($name) {
                        $q->where('first_name', 'like', $name);
                    })
                    ->orWhereHas('employee', function (Builder $q) use ($name) {
                        $q->where('second_name', 'like', $name);
                    })
                    ->orWhereHas('employee', function (Builder $q) use ($name) {
                        $q->where('third_name', 'like', $name);
                    });
                });
            })
            // Order and paginate
            ->orderBy("created_at", $request->sort)
            // ->whereNotNull('status')
            ->when($start_date && $end_date, function (Builder $query) use ($start_date, $end_date) {
                $query->whereBetween('created_at', [$start_date, $end_date]);
            })
            ->paginate(15);
        } else {


            // Agar name parametri mavjud bo'lmasa, barcha userlarni tartib bilan olish
            $murojatlar = PointUserDeportament::orderBy("created_at", 'desc')->paginate(15);



        }


        // Natijani ko'rsatish uchun ko'rinishni qaytarish
        return view('dashboard.incoming_requests', compact('murojatlar', 'filter', 'form_info'));
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
    public function show(PointUserDeportament $pointUserDeportament)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PointUserDeportament $pointUserDeportament)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PointUserDeportament $pointUserDeportament)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointUserDeportament $pointUserDeportament)
    {
        //
    }
}
