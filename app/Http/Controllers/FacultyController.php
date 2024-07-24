<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\PointUserDeportament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

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

        $faculty_items = PointUserDeportament::whereIn('departament_id', $faculty->departments->pluck('id'))->paginate('15');

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
        foreach ($faculty_items as $faculty_item) {
            foreach ($arrayKey as $column => $originalKey) {
                // column tekshiriladi
                if (isset($faculty_item->$column)) {
                    // $murojaat_nomi o'rnatiladi
                    $faculty_item->murojaat_nomi = $jadvallarCodlari[$originalKey];
                    $faculty_item->murojaat_codi = $originalKey;
                    break;
                }
            }
        }

        return view('livewire.pages.dashboard.faculty.show', compact('faculty', 'faculty_items'));
    }


    public function getItemDetails($id)
    {
        $item = PointUserDeportament::where('id', $id)->first();

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

         // Prepare an array to hold related data
         $relatedData = [];

         // Fetch related data based on relationships
         $relationships = $item->getRelationships();

         foreach ($relationships as $relationship) {
             // Ensure the relationship method exists on the model
             if (method_exists($item, $relationship)) {
                 // Get the related column name and ID from the model
                 $foreignColumn = $this->getForeignColumnForRelation($relationship);
                 dd($foreignColumn);
                 $relatedId = $item->{$foreignColumn};

                 // Check if relatedId is not null and fetch the related record
                 if ($relatedId) {
                     $relatedModelClass = $this->getModelClassForRelation($relationship);
                     if ($relatedModelClass) {
                         // Debugging: Print column and ID to verify
                         \Log::info("Fetching data from {$relatedModelClass} where {$foreignColumn} = {$relatedId}");

                         $relatedData[$relationship] = $relatedModelClass::where($foreignColumn, $relatedId)->first();

                         // Debugging: Print fetched data
                         \Log::info("Fetched data: ", $relatedData[$relationship] ? $relatedData[$relationship]->toArray() : []);
                     }
                 }
             } else {
                 $relatedData[$relationship] = null; // Relationship method does not exist
             }
         }



        $view = view('livewire.pages.dashboard.faculty.item-details', compact('item', 'relatedData'))->render();

        return response()->json(['html' => $view]);
    }
// Helper method to map relationship names to model classes dynamically
protected function getModelClassForRelation($relation)
{
    // Map relationship names to model namespaces
    $modelNamespace = '\\App\\Models\\Tables\\';
    $models = [
        'table_1_1' => 'Table_1_1_',
        'table_1_2' => 'Table_1_2_',
        'table_1_3_1' => 'Table_1_3_1_',
        'table_1_3_2' => 'Table_1_3_2_',
        'table_1_4' => 'Table_1_4_',
        'table_1_5' => 'Table_1_5_',
        'table_1_6_1' => 'Table_1_6_1_',
        'table_1_6_2' => 'Table_1_6_2_',
        'table_1_7_1' => 'Table_1_7_1_',
        'table_1_7_2' => 'Table_1_7_2_',
        'table_1_7_3' => 'Table_1_7_3_',
        'table_1_9_1' => 'Table_1_9_1_',
        'table_1_9_2' => 'Table_1_9_2_',
        'table_1_9_3' => 'Table_1_9_3_',
        'table_2_2_1' => 'Table_2_2_1_',
        'table_2_2_2' => 'Table_2_2_2_',
        'table_2_3_1' => 'Table_2_3_1_',
        'table_2_3_2' => 'Table_2_3_2_',
        'table_2_4_1' => 'Table_2_4_1_',
        'table_2_4_2' => 'Table_2_4_2_',
        'table_2_4_2_b' => 'Table_2_4_2_b_',
        'table_2_5' => 'Table_2_5_',
        'table_3_4_1' => 'Table_3_4_1_',
        'table_3_4_2' => 'Table_3_4_2_',
        'table_4_1' => 'Table_4_1_',
    ];

    // Construct the full class name
    $modelClass = $modelNamespace . ($models[$relation] ?? null);

    return $modelClass ? $modelClass : null;
}

// Helper method to get the foreign column name for a given relation
protected function getForeignColumnForRelation($relation)
{
    // Map relationship names to their corresponding foreign column names
    $foreignColumns = [
        'table_1_1' => 'id',
        'table_1_2' => 'id',
        'table_1_3_1' => 'id',
        'table_1_3_2' => 'id',
        'table_1_4' => 'id',
        'table_1_5' => 'table_1_5_id',
        'table_1_6_1' => 'table_1_6_1_id',
        'table_1_6_2' => 'table_1_6_2_id',
        'table_1_7_1' => 'table_1_7_1_id',
        'table_1_7_2' => 'table_1_7_2_id',
        'table_1_7_3' => 'table_1_7_3_id',
        'table_1_9_1' => 'table_1_9_1_id',
        'table_1_9_2' => 'table_1_9_2_id',
        'table_1_9_3' => 'table_1_9_3_id',
        'table_2_2_1' => 'table_2_2_1_id',
        'table_2_2_2' => 'table_2_2_2_id',
        'table_2_3_1' => 'table_2_3_1_id',
        'table_2_3_2' => 'table_2_3_2_id',
        'table_2_4_1' => 'table_2_4_1_id',
        'table_2_4_2' => 'table_2_4_2_id',
        'table_2_4_2_b' => 'table_2_4_2_b_id',
        'table_2_5' => 'table_2_5_id',
        'table_3_4_1' => 'table_3_4_1_id',
        'table_3_4_2' => 'table_3_4_2_id',
        'table_4_1' => 'table_4_1_id',
    ];

    return $foreignColumns[$relation] ?? 'id'; // Default to 'id' if not found
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
