<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\PointUserDeportament;

class FormsController extends Controller
{
    public function employeeShowForm($tableName)
    {
        // Formani shakillantirdigan ma'lumotlarni config papkasi ichidan employee_form_fields.php olib beradi 
        $fields = config("employee_form_fields.{$tableName}");

        if (!$fields) {
            abort(404, 'Jadval topilmadi.');
        }

        return view('livewire.pages.form_themplates.employee_form', compact('fields', 'tableName'));
    }

    public function departmentShowForm($tableName)
    {
        // Formani shakillantirdigan ma'lumotlarni config papkasi ichidan employee_form_fields.php olib beradi 
        $fields = config("department_forms_fields.{$tableName}");

        if (!$fields) {
            abort(404, 'Jadval topilmadi.');
        }

        return view('livewire.pages.form_themplates.department_form', compact('fields', 'tableName'));
    }

    public function employeeStoreForm(Request $request, $tableName)
    {

        // Foydalanuvchini aniqlash
        $user = auth()->user();

        // Agar foydalanuvchi autentifikatsiyadan o'tgan bo'lsa, uning ma'lumotlarini ko'rsatish
        if ($user) {

            // Ruxsat etilgan maydonlarni aniqlash
            $allowedFields = [
                'table_1_1_' => ['daraja_bergan_otm_nomi', 'phd_diplom_seryasi', 'phd_diplom_raqami', 'dsc_diplom_seryasi', 'dsc_diplom_raqami', 'mutaxasislik_nomi', 'ishga_qabul_raqam_seryasi'],
                'table_1_2_' => ['xorijiy_davlat_nomi', 'otm_nomi', 'mutaxasisligi', 'faoliyat_nomi', 'muddati', 'asos_file'],
                'table_1_3_1_' => ['doktorlik_diplom_seryasi', 'doktorlik_diplom_raqami', 'ilmiy_unvon_seryasi', 'ilmiy_unvon_raqami', 'mutaxasisligi_nomi', 'ishga_buyrug_rqami_seryasi'],
                'table_1_3_2_' => ['doktorlik_diplom_seryasi', 'doktorlik_diplom_raqami', 'ilmiy_unvon_seryasi', 'ilmiy_unvon_raqami', 'mutaxasisligi_nomi', 'ishga_buyrug_rqami_seryasi'],
                'table_1_4_' => ['ish_joyi', 'ixtisoslik_shifri', 'ixtisoslik_nomi', 'disertatsiya_mavzusi', 'maxsus_kengash_shifri', 'ilmiy_unvon_olganlar', 'ilmiy_unvon_tasdiqlangan_sana'],
                'table_1_5_' => ['jurnalning_nomi', 'jurnal_nashr_yili_oyi', 'maqolaning_nomi', 'maqola_tili', 'google_schoolar_url', 'google_schoolar_iqtiboslar'],
                'table_1_6_1_' => ['xorijiy_jirnal_davlat_nomi', 'ilmiy_jurnal_nomi', 'ilmiy_maqola_nomi', 'nashr_yili_betlari', 'url_manzili', 'mualliflar_soni'],
                'table_1_6_2_' => ['ilmiy_jurnal_nomi', 'ilmiy_maqola_nomi', 'nashr_yili_betlari', 'url_manzili', 'mualliflar_soni'],
                'table_1_9_1_' => ['ixtisoslik_shifri', 'mualliflar_soni', 'monograf_mualliflar_soni', 'monograf_nomi', 'monograf_kengash_bayoni', 'nashryot_nomi', 'natlib_isbn_raqami'],
                'table_1_9_2_' => ['otmlar_nomi', 'asosiy_statdagi_professorlar', 'ixtiro_model_uchun_patent', 'berilgan_sanasi', 'qayd_raqami'],
                'table_1_9_3_' => ['otm_nomi', 'asosiy_shtatdagi_prof_oqituv', 'olingan_guvohnomalar', 'mualliflar_soni', 'berilgan_sana', 'qayd_raqami'],
                'table_2_2_1_' => ['ixtisoslik_shifri', 'darslik_mualliflar_soni', 'darslik_nomi', 'darslik_guvohnomasi', 'darslik_reestr_raqami'],
                'table_2_2_2_' => ['ixtisoslik_shifri', 'qollanma_mualliflar_soni', 'qollanma_nomi', 'qollanma_guvohnomasi', 'qollanma_reestr_raqami'],
                'table_2_4_2_' => ['hujjat_nomi_sanasi', 'xorijiy_davlat_nomi', 'talim_yonalishi', 'xorijiy_va_hamkorlik_loyhasi', 'seminar_knfrensiya_nomi'],
            ];

            if (!array_key_exists($tableName, $allowedFields)) {
                return redirect()->back()->with('error', "Noma'lum yoki ruxsat etilmagan jadval!");
            }

            // So'rov ma'lumotlarini filtrlash
            $filteredData = array_intersect_key(
                $request->all(),
                array_flip($allowedFields[$tableName])
            );

            if ($request->file('asos_file')) {

                // Fayl uchun validatsiya qoidalarini belgilash
                $validatedData = $request->validate(
                    [
                        'asos_file' => 'required|file|mimes:pdf,docx,jpeg,png|max:2048', // 2MB dan kichik fayllar
                    ],
                    [
                        'asos_file.required' => 'Fayl yuklash majburiy.',
                        'asos_file.file' => 'Yuklangan fayl haqiqiy fayl bo\'lishi kerak.',
                        'asos_file.mimes' => 'Faqat PDF, DOCX, JPEG va PNG formatidagi fayllar ruxsat etiladi.',
                        'asos_file.max' => 'Fayl hajmi 2MB dan katta bo\'lmasligi kerak.',
                    ]
                );

                // Validatsiyadan o'tgan faylni olish
                $file = $request->file('asos_file');

                // Fayl uchun saqlash yo'lini yaratish va uni saqlash
                $path = $file->store('documents', 'public');

                // Fayl yo'lini ma'lumotlar bazasiga qo'shish
                $filteredData['asos_file'] = $path;
            }

            // Joriy sana/vaqt qiymatlarini qo'shish
            $now = Carbon::now();
            $filteredData['created_at'] = $now;


            // Ma'lumotlarni bazaga yuklash
            DB::table($tableName)->insert($filteredData);

            
            // Malumotni pointer tablitsiyasiga saqlash
            $userAuth = Auth::user()->id;
            $pointer['user_id'] = $userAuth;
            $pointer['status'] = 3;
            $pointer["$tableName".'id'] = 1;
            
            // Model orqali malumotni qo'shish
            PointUserDeportament::create($pointer);

            return redirect()->back()->with('success', "Ma'lumotlar muvaffaqiyatli saqlandi");

        } else {
            // Agar foydalanuvchi tizimga kirmagan bo'lsa, xabar qaytarish
            return "Foydalanuvchi tizimga kirmagan.";
        }

    }


    public function departmentStoreForm(Request $request, $tableName)
    {

        // Foydalanuvchini aniqlash
        $user = auth()->user();

        // Agar foydalanuvchi autentifikatsiyadan o'tgan bo'lsa, uning ma'lumotlarini ko'rsatish
        if ($user) {

            // Ruxsat etilgan maydonlarni aniqlash
            $allowedFields = [
                'table_1_7_1_' => ['xorijiy_granti_buyurtma_nomi', 'xorijiy_granti_buyurtma_summasi', 'jami_summa'],
                'table_1_7_2_' => ['sohalar_buyurtma_nomi', 'sohalar_buyurtma_summasi', 'jami_summa'],
                'table_1_7_3_' => ['davlat_grant_mavzusi', 'davlat_grant_summasi', 'jami_summa'],
                'table_2_3_1_' => ['xorijiy_oqituvchi_ismi', 'davlati_ish_joyi', 'mutaxasisligi', 'dars_beradigan_fani', 'asos_file'],
                'table_2_3_2_' => ['xorijiy_talaba_ismi', 'davlati', 'talim_yonalishi', 'magister_shifri_nomi', 'asos_file'],
                'table_2_4_1_' => ['hujjat_nomi_sanasi', 'otm_talaba_fish', 'davlat_otm_nomi', 'mutaxasislik_nomi', 'xorijiy_talaba_fish', 'davlat_otm_nomi2', 'mutaxasislik_nomi2'],
                'table_2_4_2_b_' => ['hujjat_nomi_sanasi', 'ism_sharifi', 'xorijiy_davlat_otm_nomi', 'mutaxasislik_nomi', 'loyha_nomi', 'seminar_nomi'],
                'table_2_5_' => ['fish', 'talim_kodi', 'talim_nomi', 'hujjat_nomi_imzosi', 'fanlar_nomi', 'chet_tili_nomi', 'talim_bosqichi', 'talabalar_soni', 'elekron_manzil'],
                'table_3_4_1_' => ['talaba_fish', 'tanlov_musoboqa_nomi', 'otkazilgan_joy_sana', 'fanlari_tanlov_musoqoqa', 'egallagan_orni', 'diplom_serya', 'diplom_raqam', 'izoh'],
                'table_3_4_2_' => ['talaba_fish', 'respublika_tanlov_nomi', 'otkazilgan_joy_sana', 'musobaqalar_nomi', 'egallagan_orni', 'diplom_seryasi', 'diplom_raqami', 'izoh'],
                'table_4_1_' => ['talaba_fish', 'talim_yonalishi', 'oqish_bosqichi', 'sport_klubi_nomi', 'sport_turi', 'sport_klubiga_azolik_sanasi', 'nechanchi_razryad'],

            ];

            if (!array_key_exists($tableName, $allowedFields)) {
                return redirect()->back()->with('error', "Noma'lum yoki ruxsat etilmagan jadval!");
            }

            // So'rov ma'lumotlarini filtrlash
            $filteredData = array_intersect_key(
                $request->all(),
                array_flip($allowedFields[$tableName])
            );

            if ($request->file('asos_file')) {

                // Fayl uchun validatsiya qoidalarini belgilash
                $validatedData = $request->validate(
                    [
                        'asos_file' => 'required|file|mimes:pdf,docx,jpeg,png|max:2048', // 2MB dan kichik fayllar
                    ],
                    [
                        'asos_file.required' => 'Fayl yuklash majburiy.',
                        'asos_file.file' => 'Yuklangan fayl haqiqiy fayl bo\'lishi kerak.',
                        'asos_file.mimes' => 'Faqat PDF, DOCX, JPEG va PNG formatidagi fayllar ruxsat etiladi.',
                        'asos_file.max' => 'Fayl hajmi 2MB dan katta bo\'lmasligi kerak.',
                    ]
                );

                // Validatsiyadan o'tgan faylni olish
                $file = $request->file('asos_file');

                // Fayl uchun saqlash yo'lini yaratish va uni saqlash
                $path = $file->store('documents', 'public');

                // Fayl yo'lini ma'lumotlar bazasiga qo'shish
                $filteredData['asos_file'] = $path;    
 
            }

            // Joriy sana/vaqt qiymatlarini qo'shish
            $now = Carbon::now();
            $filteredData['created_at'] = $now;


            // Ma'lumotlarni bazaga yuklash
            DB::table($tableName)->insert($filteredData);

            // Malumotni pointer tablitsiyasiga saqlash
            $userAuth = Auth::user()->id;
            $pointer['user_id'] = $userAuth;
            $pointer['status'] = 3;
            $pointer["$tableName".'id'] = 1;
            
            // Model orqali malumotni qo'shish
            PointUserDeportament::create($pointer);

            return redirect()->back()->with('success', "Ma'lumotlar muvaffaqiyatli saqlandi");

        } else {
            // Agar foydalanuvchi tizimga kirmagan bo'lsa, xabar qaytarish
            return "Foydalanuvchi tizimga kirmagan.";
        }

    }
}

