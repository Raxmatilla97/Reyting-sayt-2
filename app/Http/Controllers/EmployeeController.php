<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Employee::paginate(15);

        return view('livewire.pages.dashboard.employee.index', compact('employee'));
    }

    public function employeeFormChose(){

        
        $jadvallar_codlari = [
            'table_1_1_' => "Chirchiq davlat pedagogika universitetida dunyoning nufuzli 1000 taligiga kiruvchi oliy ta’lim muassasalarida PhD (falsafa doktori yoki fan nomzodi) yoki DSc (fan doktori) kabi ilmiy darajalarni olgan professor-o‘qituvchilar ulushi haqidag ",
            'table_1_2_' => "Chirchiq davlat pedagogika universitetida reytingni aniqlash yilida dunyoning nufuzli 1000 taligiga kiruvchi oliy ta’lim muassasalarida o‘quv mashg‘ulotlar (ma’ruzalar, amaliy mashg‘ulotlar, seminar-treninglar) o‘tkazgan professor-o‘qituvchilar haqidagi",
            'table_1_3_1_' => "Chirchiq davlat pedagogika universitetida fan doktori (DSc-fan doktori) ilmiy darajasiga ega (shuningdek, ilmiy darajaga ega bo‘lmay professor ilmiy unvonini olgan yoki unga tenglashtirilgan) professor-o‘qituvchilar haqida",
            'table_1_3_2_' => "Chirchiq davlat pedagogika universitetida fan nomzodi (PhD) ilmiy darajasiga ega (shuningdek, ilmiy darajaga ega bo‘lmay dotsent ilmiy unvonini olgan) professor-o‘qituvchilar ulushi haqida",
            'table_1_4_' => "Chirchiq davlat pedagogika universitetida hisob yilida ilmiy daraja yoki ilmiy unvon olgan professor-o‘qituvchilar haqida",
            'table_1_5_' => "Chirchiq davlat pedagogika universitetida halqaro ko‘rsatkichlarga ko‘ra professor-o‘qituvchilarning ilmiy maqolalariga («Web of Science», «Scopus», «Google Scholar» yoki boshqa xalqaro e’tirof etilgan bazalarda mavjud bo‘lgan jurnallar bo‘yicha) iqtiboslar haqida  ",
            'table_1_6_1_' => "Chirchiq davlat pedagogika universitetida reytingni aniqlash yilida xalqaro ilmiy jurnallarda («Web of Science», «Scopus» va Vazirlar Mahkamasi huzuridagi Oliy attestatsiya komissiyasi ro‘yxatiga kiritilgan jurnallarda) chop etilgan ilmiy maqolalar haqida",
            'table_1_6_2_' => "Chirchiq davlat pedagogika universitetida reytingni aniqlash yilida Respublika ilmiy jurnallaridagi (OAK ro‘yxatidagi) ilmiy maqolalar haqida ",
            'table_1_9_1_' => "Chirchiq davlat pedagogika universiteti Boshlang'ich ta'lim fakulteti Boshlang'ich ta'lim metodikasi kafedrasida reytingi aniqlanayotgan yilda bajarilgan ilmiy-tadqiqot ishlarining samaradorligi haqida",
            'table_1_9_2_' => "Chirchiq davlat pedagogika universitetida reytingi aniqlanayotgan yilda professor-o‘qituvchilari tomonidan ixtiro, foydali model, sanoat namunalari va seleksiya yutuqlari uchun olingan patentlar (tegishli tashkilotlar tomonidan tasdiqlangan normativ hujjatlar asosida) haqida",
            'table_1_9_3_' => "Chirchiq davlat pedagogika universitetida reytingi aniqlanayotgan yilda professor-o‘qituvchilari tomonidan axborot-kommunikatsiya texnologiyalariga oid dasturlar va elektron ma’lumotlar bazalari uchun olingan guvohnomalar, mualliflik huquqi bilan himoya qilinadigan turli materiallar haqida",
            'table_2_2_1_' => "Chirchiq davlat pedagogika universiteti Boshlang'ich ta'lim fakulteti Boshlang'ich ta'lim metodikasi kafedrasida hisob yilida oliy ta’lim muassasasi professor-o‘qituvchilari tomonidan yozib tayyorlangan va belgilangan tartibda ro‘yxatdan o‘tkazilgan darsliklar haqida",
            'table_2_2_2_' => "Chirchiq davlat pedagogika universiteti Boshlang'ich ta'lim fakulteti Boshlang'ich ta'lim metodikasi kafedrasida hisob yilida oliy ta’lim muassasasi professor-o‘qituvchilari tomonidan yozib tayyorlangan va belgilangan tartibda ro‘yxatdan o‘tkazilgan o‘quv qo‘llanmalar haqida",
            'table_2_4_2_' => "Chirchiq davlat pedagogika universitetida hisob yilida xorijiy oliy ta’lim muassasalari bilan xalqaro konferensiyalar, seminarlar, ilmiy yoki o‘quv loyihalarda talabalar va o‘qituvchilar ishtirok etishi xaqidagi",
          
        ];

        return view('livewire.pages.dashboard.employee_form', compact('jadvallar_codlari'));
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
