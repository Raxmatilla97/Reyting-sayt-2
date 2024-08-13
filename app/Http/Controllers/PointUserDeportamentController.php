<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use App\Models\PointUserDeportament;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

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
        // Check if the 'category' field is filled and set the appropriate 'status'
        if ($request->filled('category')) {
            switch ($request->category) {
                case 'all':
                    $request->merge(['status' => 'all']);
                    break;
                case 'must_be_confirmed':
                    $request->merge(['status' => '3']);
                    break;
                case 'approved':
                    $request->merge(['status' => '1']);
                    break;
                case 'rejected':
                    $request->merge(['status' => '0']);
                    break;
            }
        }

        $form_info = [
            'category'   => $request->get('category'),
            'name'       => $request->get('name'),
            'sort'       => $request->get('sort'),
            'start_data' => $request->get('start_data'),
            'end_data'   => $request->get('end_data'),
        ];

        // Handle date formatting, ensuring correct input and avoiding potential null value issues
        $start_date = $request->filled('start_data')
            ? DateTime::createFromFormat('m/d/Y', $request->input('start_data'))
            : null;
        $end_date = $request->filled('end_data')
            ? DateTime::createFromFormat('m/d/Y', $request->input('end_data'))
            : null;

        // Apply filters only if necessary fields are filled
        $filter = PointUserDeportament::whereNotNull('status')->get();

        if ($request->filled('name') || $request->filled('category') || $request->filled('sort') || ($request->filled('start_data') && $request->filled('end_data'))) {
            $murojatlar = PointUserDeportament::when($request->filled('category') && $request->status !== 'all', function (Builder $query) use ($request) {
                $query->where('status', $request->status);
            })
                ->when($request->filled('name'), function (Builder $query) use ($request) {
                    $name = '%' . $request->name . '%'; // Prepare the search term for LIKE queries
                    $query->where(function (Builder $q) use ($name) {
                        $q->whereHas('employee', function (Builder $q) use ($name) {
                            $q->where('first_name', 'like', $name)
                                ->orWhere('second_name', 'like', $name)
                                ->orWhere('third_name', 'like', $name);
                        });
                    });
                })
                ->when($request->filled('sort'), function (Builder $query) use ($request) {
                    $query->orderBy('created_at', $request->sort);
                })
                ->when($start_date && $end_date, function (Builder $query) use ($start_date, $end_date) {
                    $query->whereBetween('created_at', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')]);
                })
                ->paginate(15);
        } else {
            // If no filters are applied, return all records ordered by creation date
            $murojatlar = PointUserDeportament::orderBy('created_at', 'desc')->paginate(15);
        }

        // Department va Employee konfiguratsiyalarini olish
        $departmentCodlari = Config::get('dep_emp_tables.department');
        $employeeCodlari = Config::get('dep_emp_tables.employee');

        // Ikkala massivni birlashtirish
        $jadvallarCodlari = array_merge($departmentCodlari, $employeeCodlari);

        // Har bir massiv elementiga "key" nomli yangi maydonni qo'shish
        $arrayKey = [];
        foreach ($jadvallarCodlari as $key => $value) {
            $arrayKey[$key . 'id'] = $key; // $key . 'id' qiymatini o'rnating
        }


        // Ma'lumotlar massivini tekshirish
        foreach ($murojatlar as $item) {
            foreach ($arrayKey as $column => $originalKey) {
                // column tekshiriladi
                if (isset($item->$column)) {
                    // $murojaat_nomi o'rnatiladi
                    $item->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $item->murojaat_codi = $originalKey;
                    break;
                }
            }
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
