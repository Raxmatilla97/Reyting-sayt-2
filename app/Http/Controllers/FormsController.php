<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FormsController extends Controller
{
    public function showForm($tableName)
    {
        $fields = config("forms.{$tableName}");

        if (!$fields) {
            abort(404, 'Jadval topilmadi.');
        }

        return view('livewire.pages.frontend.forms', compact('fields', 'tableName'));
    }

    public function storeForm(Request $request, $tableName)
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
                $path = $file->store('/public/documents');

                // Fayl yo'lini ma'lumotlar bazasiga qo'shish
                $filteredData['asos_file'] = $path;
            }


            // $filteredData'dan tashqari, fayl yo'lini ham qo'shamiz
            $filteredData = array_intersect_key(
                $request->except(['asos_file']), // "asos_file"ni olib tashlaymiz, chunki bu fayl emas, ma'lumotlarni o'z ichiga oladi
                array_flip($allowedFields[$tableName])
            );

            // Joriy sana/vaqt qiymatlarini qo'shish
            $now = Carbon::now();
            $filteredData['created_at'] = $now;


            // Ma'lumotlarni bazaga yuklash
            DB::table($tableName)->insert($filteredData);

            return redirect()->back()->with('success', "Ma'lumotlar muvaffaqiyatli saqlandi");

        } else {
            // Agar foydalanuvchi tizimga kirmagan bo'lsa, xabar qaytarish
            return "Foydalanuvchi tizimga kirmagan.";
        }

    }
}
