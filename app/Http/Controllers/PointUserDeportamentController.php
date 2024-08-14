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

    public function murojatniTasdiqlash() {}

    public function show($id)
    {
        // Yuborilgan faylni qidirish
        $information = PointUserDeportament::findOrFail($id);

        // Default surat buni o'zgartirsa bo'ladi
        $default_image = 'https://cspu.uz/storage/app/media/2023/avgust/i.webp';

        $totalPoints = 0.0;

        // Foydalanuvchining barcha pointlarni yig'indisini hisoblash
        $totalPoints = PointUserDeportament::where('user_id', $information->user_id)
            ->sum('point');



            if (!$information) {
                return response()->json(['error' => 'Item not found'], 404);
            }

            $relatedData = [];
            $relationships = $information->getRelationships();

            if (is_array($relationships)) {
                foreach ($relationships as $relationship) {
                    // Foreign key maydoni nomini tuzing
                    $foreignKey = $relationship . '_id';
                    // Foreign key maydoni mavjudligini va null emasligini tekshiring
                    if (isset($information->{$foreignKey}) && !is_null($information->{$foreignKey})) {
                        // Tegishli model sinf nomini dinamik ravishda aniqlang
                        $relatedModelClass = $this->getModelClassForRelation($relationship);
                        // Foreign key ID asosida tegishli elementni oling
                        $relatedData[$relationship] = $relatedModelClass::find($information->{$foreignKey});

                    } else {
                        $relatedData[$relationship] = null; // Tegishli maʼlumotlar topilmadi
                    }
                }
            } else {
                return response()->json(['error' => 'No relationships defined'], 500);
            }



            // $item->year ni ko'rinishga uzatamiz
            $year = $information->year;



        return view('dashboard.show_request', compact('information', 'default_image', 'totalPoints', 'relatedData', 'year'));
    }

    private function getModelClassForRelation($relation)
    {
        // Tegishli model uchun to'liq class nomini tuzish
        return "\\App\\Models\\Tables\\" . ucfirst($relation) . "_";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointUserDeportament $pointUserDeportament)
    {
        //
    }
}
