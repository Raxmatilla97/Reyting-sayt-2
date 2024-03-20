<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::paginate(15);       

        return view('livewire.pages.dashboard.department.index', compact('departments'));
    }

    public function departmentFormChose(){
        
        // Kafedra ma'lumotlarini yuklash uchun uni bo'limlari ro'yxati
        $jadvallar_codlari = [
            'table_1_7_1_' => "Chirchiq davlat pedagogika universitetida xorijiy ilmiy tadqiqot markazlari grantlari va xorijiy ilmiy fondlari buyurtmalari hisobiga 2023 yilda olingan mablag‘lar bo‘yicha",
            'table_1_7_2_' => "Chirchiq davlat pedagogika universitetida sohalar buyurtmalari asosida o‘tkazilgan ilmiy (ilmiy-ijodiy) tadqiqotlardan 2023 yilda  olingan moliyaviy mablag‘lar bo‘yicha ",
            'table_1_7_3_' => "Chirchiq davlat pedagogika universitetida davlat grantlari asosida o‘tkazilgan tadqiqotlardan 2023 yilda olingan mablag‘lar haqida ",
            'table_2_3_1_' => "Chirchiq davlat pedagogika universitetida reytingi aniqlanayotgan yilda xorijiy o‘qituvchilar (mos ravishda umumiy songa nisbatan % da) ulushi haqidagi",
            'table_2_3_2_' => "Chirchiq davlat pedagogika universitetida reytingi aniqlanayotgan yilda xorijiy talabalar (mos ravishda umumiy songa nisbatan % da) ulushi haqidagi",
            'table_2_4_1_' => "Chirchiq davlat pedagogika universitetida hisob yilida xorijiy oliy ta’lim muassasalari bilan akademik almashuv dasturlari (talabalar tomonidan) haqida",
            'table_2_4_2_b_' => "Chirchiq davlat pedagogika universitetida hisob yilida xorijiy oliy ta’lim muassasalari bilan xalqaro konferensiyalar, seminarlar, ilmiy yoki o‘quv loyihalarda talabalar ishtirok etishi xaqidagi",
            'table_2_5_' => "Chirchiq davlat pedagogika universitetida Ta’lim yo‘nalishlari (mutaxassisliklari) bo‘yicha chet tilida o‘qitiladigan fanlar salmog‘i (mutaxassislik fanlarining jami soniga nisbatan %da) haqida",
            'table_3_4_1_' => "Chirchiq davlat pedagogika universitetida reyting yilida xalqaro olimpiadalarida, nufuzli tanlovlar sovrinli o‘rinlarni qo‘lga kiritgan hamda mukofot (diplom)larga sazovor bo‘lgan talabalar salmog‘i haqida  ",
            'table_3_4_2_' => "Chirchiq davlat pedagogika universitetida reyting yilida respublika olimpiadalarida, nufuzli tanlovlar sovrinli o‘rinlarni qo‘lga kiritgan hamda mukofot (diplom)larga sazovor bo‘lgan talabalar salmog‘i haqida  ",
            'table_4_1_' => "Chirchiq davlat pedagogika universitetida sport klubiga a’zo bo‘lib, tashkil etilgan sport seksiyalarida jismoniy tarbiya va sport bilan muntazam shug‘ullanuvchi sport tasnifiga (sportchi razryadlari) ega bo‘lgan talabalar*",
         
           
          
        ];

        return view('livewire.pages.dashboard.department_form', compact('jadvallar_codlari'));
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
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
    }
}
