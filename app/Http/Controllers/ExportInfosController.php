<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Department;
use App\Models\PointUserDeportament;
use App\Models\StudentsCountForDepart;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet; // Bu yerda to'g'ri import


use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Http\Controllers\Export\Table_2_DataCode;
use App\Http\Controllers\Export\Table_3_DataCode;
use App\Http\Controllers\Export\Table_4_DataCode;
use App\Http\Controllers\Export\Table_7_DataCode;
use App\Http\Controllers\Export\Table_8_1_DataCode;
use App\Http\Controllers\Export\Table_8_2_DataCode;
use App\Http\Controllers\Export\Table_9_1_DataCode;
use App\Http\Controllers\Export\Table_9_2_DataCode;
use App\Http\Controllers\Export\Table_10_1_DataCode;
use App\Http\Controllers\Export\Table_10_2_DataCode;
use App\Http\Controllers\Export\Table_10_3_DataCode;
use App\Http\Controllers\Export\Table_11_1_DataCode;
use App\Http\Controllers\Export\Table_11_2_DataCode;
use App\Http\Controllers\Export\Table_11_3_DataCode;
use App\Http\Controllers\Export\Table_12_DataCode;
use App\Http\Controllers\Export\Table_13_DataCode;
use App\Http\Controllers\Export\Table_14_1_DataCode;
use App\Http\Controllers\Export\Table_14_2_DataCode;
use App\Http\Controllers\Export\Table_14_3_DataCode;
use App\Http\Controllers\Export\Table_15_1_DataCode;
use App\Http\Controllers\Export\Table_15_2_DataCode;
use App\Http\Controllers\Export\Table_16_DataCode;
use App\Http\Controllers\Export\Table_17_1_DataCode;
use App\Http\Controllers\Export\Table_17_2_DataCode;
use App\Http\Controllers\Export\Table_18_1_DataCode;
use App\Http\Controllers\Export\Table_18_2_DataCode;
use App\Http\Controllers\Export\Table_18_3_DataCode;
use App\Http\Controllers\Export\Table_18_3_a_DataCode;
use App\Http\Controllers\Export\Table_19_DataCode;
use App\Http\Controllers\Export\Table_20_1_DataCode;
use App\Http\Controllers\Export\Table_20_2_DataCode;
use App\Http\Controllers\Export\Table_20_3_DataCode;
use App\Http\Controllers\Export\Table_21_1_DataCode;
use App\Http\Controllers\Export\Table_21_2_DataCode;
use App\Http\Controllers\Export\Table_22_DataCode;
use App\Http\Controllers\Export\Table_23_DataCode;
use App\Http\Controllers\Export\Table_24_DataCode;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportInfosController extends Controller
{
    private $progress = 0;
    private $tables = [
        'table_2',
        'table_3',
        'table_4',
        'table_7',
        'table_8_1',
        'table_8_2',
        'table_9_1',
        'table_9_2',
        'table_10_1',
        'table_10_2',
        'table_10_3',
        'table_11_1',
        'table_11_2',
        'table_11_3',
        'table_12',
        'table_13',
        'table_14_1',
        'table_14_2',
        'table_14_3',
        'table_15_1',
        'table_15_2',
        'table_16',
        'table_17_1',
        'table_17_2',
        'table_18_1',
        'table_18_2',
        'table_18_3',
        'table_19',
        'table_20_1',
        'table_20_2',
        'table_20_3',
        'table_21_1',
        'table_21_2',
        'table_22',
        'table_23',
        'table_24',
    ];

    // Jadvallar uchun ustun nomlari - har bir jadval uchun shu formatda qo'shiladi
    private $tableHeaders = [
        'table_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Ilmiy daraja bergan xorijiy OTM nomi',
            'E' => 'PhD (falsafa doktori yoki fan nomzodi) diplom seriyasi va raqami',
            'F' => 'DSc (fan doktori) diplom seriyasi va raqami',
            'G' => 'Ixtisoslik nomi',
            'H' => 'Ishga qabul qilinganligi to\'g\'risidagi buyruq raqami va sanasi',
            'I' => 'Asos (buyruq, qaror, shartnoma va boshqalar)',
        ],
        'table_3' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Magistrlik darajasi bergan xorijiy OTM nomi',
            'E' => 'Magistrlik diplomi seriyasi va raqami',
            'F' => 'Mutaxassislik nomi',
            'G' => 'Ishga qabul qilinganligi to\'g\'risidagi buyruq raqami va sanasi',
            'H' => 'Asos (buyruq, qaror, shartnoma va boshqalar)',
        ],
        'table_4' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Ixtisoslik shifri va nomi',
            'E' => 'Darslik mualliflar soni',
            'F' => 'Darslik nomi',
            'G' => 'Darslik OTFIV qoshidagi muvofiqlashtiruvchi Kengashdan o\'tganligi to\'g\'risidagi guvohnoma raqami va sanasi',
            'H' => 'Darslik reestr raqami',
            'I' => 'Asos (Vazirlik buyrug\'i yoki guvohnoma nusxasi)',
        ],
        'table_7' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Kurs nomi',
            'E' => 'Xorijiy davlat nomi yoki platforma nomi',
            'F' => 'Sertifikat sanasi va raqami',
            'G' => 'Link URL',
            'H' => 'Asos',
        ],
        'table_8_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Xorijiy tillarni bilish darajasi (masalan, B2, C1)',
            'E' => 'Xorijiy til turi (masalan, ingliz tili, nemis tili, turk tili va boshqa)',
            'F' => 'Sertifikat nomi (masalan, Milliy sertifkat, IELTS va boshqa)',
            'G' => 'Sertifikat sanasi va raqami',
            'H' => 'Sertifikat muddati',
            'I' => 'Asos (sertifikat nusxasi)',
        ],
        'table_8_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Ingliz tilida ta\'lim beriladigan ta\'lim yo\'nalishlari (mutaxassisliklar) kodi va nomi',
            'E' => 'ChDPU rektorining buyrug\'i sanasi va raqami',
            'F' => 'Ingliz tilida dars berilayotgan fanlar nomi',
            'G' => 'Ingliz tilidagi sillabusning HEMISga yuklanganligi haqida linki (havolasi)',
            'H' => 'Asos (Ingliz tilini bilish darajasi sertifikati nusxasi)',
        ],
        'table_9_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Ixtisoslik shifri va nomi',
            'E' => 'Muallif(lar)ning F.I.Sh.',
            'F' => 'Monografiya mualliflar soni',
            'G' => 'Monografiya nomi',
            'H' => 'Monografiya nashrga tavsiya qilinganligi haqida tegishli kengash bayoni, sanasi',
            'I' => 'Nashriyot nomi',
            'J' => 'DOI raqami (agar bo\'lsa)',
            'K' => 'Scopus, Web of Science bazalarida indekslangan linki (havolasi)',
            'L' => 'Asos',
        ],
        'table_9_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Ixtisoslik shifri va nomi',
            'E' => 'Muallifning F.I.Sh.',
            'F' => 'Monografiya mualliflar soni',
            'G' => 'Monografiya nomi',
            'H' => 'Monografiya nashrga tavsiya qilinganligi haqida tegishli kengash bayoni, sanasi',
            'I' => 'Nashriyot nomi',
            'J' => 'ISBN raqami',
            'K' => 'Asos',
        ],
        'table_10_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Xorijiy ilmiy jurnal nashr etilgan davlat nomi',
            'E' => 'Ilmiy jurnal nomi',
            'F' => 'Ilmiy maqola nomi',
            'G' => 'Nashr yili, betlari',
            'H' => 'Maqolaning jurnaldagi linki (havolasi)',
            'I' => 'Scopus, Web of Science bazalarida indekslangan linki (havolasi)',
            'J' => 'Mualliflar soni',
            'K' => 'Asos (maqolaning PDF nusxasi)',
        ],
        'table_10_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Xorijiy ilmiy jurnal nashr etilgan davlat nomi',
            'E' => 'Ilmiy jurnal nomi',
            'F' => 'Ilmiy maqola nomi',
            'G' => 'Nashr yili, betlari',
            'H' => 'Maqolaning jurnaldagi linki (havolasi)',
            'I' => 'Scopus, Web of Science bazalarida indekslangan linki (havolasi)',
            'J' => 'Mualliflar soni',
            'K' => 'Asos (maqolaning PDF nusxasi)',
        ],
        'table_10_3' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Xorijiy ilmiy jurnal/konferensiya nashr etilgan davlat nomi',
            'E' => 'Ilmiy jurnal/konferensiya nomi',
            'F' => 'Ilmiy maqola/tezis nomi',
            'G' => 'Nashr yili, betlari',
            'H' => 'Maqola/tesizning jurnaldagi linki (havolasi)',
            'I' => 'Scopus, Web of Science bazalarida indekslangan linki (havolasi)',
            'J' => 'Mualliflar soni',
            'K' => 'Asos (maqola/tezisning PDF nusxasi)',
        ],
        'table_11_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Jurnalning nomi',
            'E' => 'Jurnalning nashr etilgan yili va oyi',
            'F' => 'Maqolaning nomi',
            'G' => 'Maqolaning qaysi tilda chop etilganligi',
            'H' => 'Scopus, Web of Science bazalarida indekslangan linki (havolasi)',
            'I' => 'Scopus, Web of Science bazalarida mavjud bo\'lgan ushbu materialga iqtiboslar soni',
        ],
        'table_11_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Jurnalning nomi',
            'E' => 'Jurnalning nashr etilgan yili va oyi',
            'F' => 'Maqolaning nomi',
            'G' => 'Maqolaning qaysi tilda chop etilganligi',
            'H' => 'ResearchGate bazasida indekslangan linki (havolasi)',
            'I' => 'ResearchGate bazasida mavjud bo\'lgan ushbu materialga iqtiboslar soni',
        ],
        'table_11_3' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Jurnalning nomi',
            'E' => 'Jurnalning nashr etilgan yili va oyi',
            'F' => 'Maqolaning nomi',
            'G' => 'Maqolaning qaysi tilda chop etilganligi',
            'H' => 'Google Scholar bazasida indekslangan linki (havolasi)',
            'I' => 'Google Scholar bazasida mavjud bo\'lgan ushbu materialga iqtiboslar soni',
        ],
        'table_12' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Xorijiy ilmiy jurnal nashr etilgan davlat nomi',
            'E' => 'Ilmiy jurnal nomi',
            'F' => 'Ilmiy maqola nomi',
            'G' => 'Nashr yili, betlari',
            'H' => 'Maqolaning jurnaldagi linki (havolasi)',
            'I' => 'Mualliflar soni',
            'J' => 'OAK ro\'yxatida tan olinuvchi asosi',
            'K' => 'Asos (maqolaning PDF nusxasi)',
        ],
        'table_13' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Ilmiy jurnal nomi',
            'E' => 'Ilmiy maqola nomi',
            'F' => 'Nashr yili, betlari',
            'G' => 'Maqolaning jurnaldagi linki (havolasi)',
            'H' => 'Mualliflar soni',
            'I' => 'OAK ro\'yxatida tan olinuvchi asosi',
            'J' => 'Asos (maqolaning PDF nusxasi)',
        ],
        'table_14_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Hamkorlik dasturiga asos bo\'luvchi hujjat nomi va imzolangan sanasi',
            'E' => 'Davlat va OTM nomi',
            'F' => 'Hamkorlikda bajarilayotgan loyihalar/tezis nomi',
            'G' => 'Xalqaro konferensiya va seminarlar nomi',
            'H' => 'Asos',
        ],
        'table_14_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Hamkorlik dasturiga asos bo\'luvchi hujjat nomi va imzolangan sanasi',
            'E' => 'Davlat va OTM nomi',
            'F' => 'Hamkorlikda bajarilayotgan loyihalar/tezis nomi',
            'G' => 'Xalqaro konferensiya va seminarlar nomi',
            'H' => 'Asos',
        ],
        'table_14_3' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Hamkorlik dasturiga asos bo\'luvchi hujjat nomi va imzolangan sanasi',
            'E' => 'Talabaning F.I.Sh.',
            'F' => 'Davlat va OTM nomi',
            'G' => 'Talabaning ta\'lim yo\'nalishi (mutaxassislik) nomi',
            'H' => 'Hamkorlikda bajarilayotgan loyihalar/tezis nomi',
            'I' => 'Xalqaro konferensiya va seminarlar nomi',
            'J' => 'Asos',
        ],
        'table_15_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Xorijiy ilmiy tadqiqot markazlari grantlari va xorijiy ilmiy fondlari buyurtmalari nomi',
            'E' => 'Xorijiy ilmiy tadqiqot markazlari grantlari va xorijiy ilmiy fondlari buyurtmalari summasi',
            'F' => 'Jami summasi',
            'G' => 'Asos',
        ],
        'table_15_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Xorijiy ilmiy tadqiqot markazlari grantlari va xorijiy ilmiy fondlari buyurtmalari nomi',
            'E' => 'Xorijiy ilmiy tadqiqot markazlari grantlari va xorijiy ilmiy fondlari buyurtmalari summasi',
            'F' => 'Jami summasi',
            'G' => 'Asos',
        ],
        'table_16' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Sohalar buyurtmalari asosida o\'tkazilgan ilmiy (ilmiy-ijodiy) tadqiqotlar buyurtma nomi',
            'E' => 'Sohalar buyurtmalari asosida o\'tkazilgan ilmiy (ilmiy-ijodiy) tadqiqotlar buyurtma summasi',
            'F' => 'Jami summasi',
            'G' => 'Asos',
        ],
        'table_17_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Patent nomi',
            'E' => 'Patent berilgan sana',
            'F' => 'Ro\'yxatdan o\'tkazilgan raqami',
            'G' => 'Patent internet manzili (giperhavola)',
            'H' => 'Huquq egalari soni',
            'I' => 'Asos',
        ],
        'table_17_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Guvohnoma nomi',
            'E' => 'Berilgan sanasi',
            'F' => 'Ro\'yxatdan o\'tkazilgan raqami',
            'G' => 'Guvohnoma internet manzili (giperhavola)',
            'H' => 'Huquq egalari soni',
            'I' => 'Asos',
        ],
        'table_18_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Professor ilmiy unvonini berilgan diplom seriyasi va raqmi',
            'E' => 'IK faoliyat ko\'rsatayotgan muassasa nomi',
            'F' => 'Ixtisoslik shifri va nomi',
            'G' => 'Ishga qabul qilinganligi to\'g\'risidagi buyruq raqami va sanasi',
            'H' => 'Asos (diplom nusxasi)',
        ],
        'table_18_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Fan doktori (DSc) diplomi seriyasi va raqami',
            'E' => 'IK faoliyat ko\'rsatayotgan muassasa nomi, IK raqami',
            'F' => 'Ixtisoslik shifri va nomi',
            'G' => 'Ishga qabul qilinganligi to\'g\'risidagi buyruq raqami va sanasi',
            'H' => 'Asos (diplom nusxasi)',
        ],
        'table_18_3' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Dotsent ilmiy unvonini yoki unga tenglashtirilgan diplom seriyasi va raqami',
            'E' => 'IK faoliyat ko\'rsatayotgan muassasa nomi',
            'F' => 'Ixtisoslik shifri va nomi',
            'G' => 'Ishga qabul qilinganligi to\'g\'risidagi buyruq raqami va sanasi',
            'H' => 'Asos (diplom nusxasi)',
        ],
        'table_18_3_a' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Fan doktori (PhD) diplomi seriyasi va raqami',
            'E' => 'IK faoliyat ko\'rsatayotgan muassasa nomi, IK raqami',
            'F' => 'Ixtisoslik shifri va nomi',
            'G' => 'Ishga qabul qilinganligi to\'g\'risidagi buyruq raqami va sanasi',
            'H' => 'Asos (diplom nusxasi)',
        ],
        'table_19' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Ish joyi (OTM nomi)',
            'E' => 'IK faoliyat ko\'rsatayotgan muassasa nomi, IK raqami',
            'F' => 'Ixtisoslik shifri va nomi',
            'G' => 'Dissertatsiya mavzusi',
            'H' => 'Ilmiy daraja yoki ilmiy unvon berish to\'g\'risidagi Maxsus kengash qarorining OAK tomonidan tasdiqlangan sanasi',
            'I' => 'Asos (diplom nusxasi)',
        ],
        'table_20_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Mualliflar soni',
            'E' => 'Radio efir/TV/ommabop gazeta yoki jurnal nomi',
            'F' => 'Radio efir/TV/ommabop gazeta yoki jurnal tizimlardagi internet manzili',
            'G' => 'Asos (materialning elektron nusxasi)',
        ],
        'table_20_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Mualliflar soni',
            'E' => 'Radio efir/TV/ommabop gazeta yoki jurnal nomi',
            'F' => 'Radio efir/TV/ommabop gazeta yoki jurnal tizimlardagi internet manzili',
            'G' => 'Asos (materialning elektron nusxasi)',
        ],
        'table_20_3' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Mualliflar soni',
            'E' => 'Radio efir/TV/ommabop gazeta yoki jurnal nomi',
            'F' => 'Radio efir/TV/ommabop gazeta yoki jurnal tizimlardagi internet manzili',
            'G' => 'Asos (materialning elektron nusxasi)',
        ],
        'table_21_1' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Professor-o\'qituvchi/Talabaning F.I.Sh.',
            'E' => 'Xalqaro olimpiadalar, nufuzli tanlov va sport musobaqalar nomi',
            'F' => 'O\'tkazilgan joy va sanasi',
            'G' => 'Olimpiada fanlari, tanlov va musobaqalar nomi',
            'H' => 'Egallagan o\'rni',
            'I' => 'Diplom seriyasi va raqami',
            'J' => 'Asos',
        ],
        'table_21_2' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'F.I.SH.',
            'D' => 'Professor-o\'qituvchi/Talabaning F.I.Sh.',
            'E' => 'Respublika olimpiadalar, nufuzli tanlov va sport musobaqalar nomi',
            'F' => 'O\'tkazilgan joy va sanasi',
            'G' => 'Olimpiada fanlari, tanlov va musobaqalar nomi',
            'H' => 'Egallagan o\'rni',
            'I' => 'Diplom seriyasi va raqami',
            'J' => 'Asos',
        ],
        'table_22' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'Almashuv dasturiga asos bo\'luvchi hujjat nomi va imzolangan sanasi',
            'D' => 'Almashuv dasturlari asosida xorijiy OTMda ta\'lim olayotgan talabaning F.I.Sh.',
            'E' => 'Davlat va OTM nomi',
            'F' => 'Ta\'lim yo\'nalishi (mutaxassislik) nomi',
            'G' => 'Almashuv dasturlari asosida ChDPUda ta\'lim olayotgan xorijlik talabaning F.I.Sh.',
            'H' => 'Ta\'lim yo\'nalishi (mutaxassislik) nomi',
            'I' => 'Asos',
        ],
        'table_23' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'Xorijiy o\'qituvchining F.I.Sh.',
            'D' => 'Davlati va (asosiy) ish joyi',
            'E' => 'Mutaxassisligi',
            'F' => 'Dars beradigan fani',
            'G' => 'Scopus ID raqami',
            'H' => 'Asos',
        ],
        'table_24' => [
            'A' => '№',
            'B' => 'Kafedra nomi',
            'C' => 'Talabaning F.I.Sh.',
            'D' => 'Davlat grant summasi',
            'E' => 'Xorijiy ilmiy jurnal nashr etilgan davlat nomi',
            'F' => 'Ilmiy jurnal nomi',
            'G' => 'Ilmiy maqola nomi',
            'H' => 'Nashr yili, betlari',
            'I' => 'Scopus, Web of Science bazalarida indekslangan linki (havolasi)',
            'J' => 'Mualliflar soni (ilmiy rahbardan tashqari)',
            'K' => 'Asos (maqolaning PDF nusxasi)',
        ],
    ];

    public function export()
    {
        $studentCounts = StudentsCountForDepart::with('department')->get();
        return view('dashboard.config', compact('studentCounts'));
    }

    public function download()
    {
        return new StreamedResponse(function () {
            try {
                ini_set('memory_limit', '40G');
                set_time_limit(600);

                $this->sendUpdate('Boshlash', 0);

                // Ma'lumotlarni yuklash
                $this->sendUpdate('Ma\'lumotlar yuklanmoqda...', 5);

                // Yangi shablonni yaratish
                $this->sendUpdate('Yangi shablon tayyorlanmoqda...', 10);
                $spreadsheet = $this->createNewTemplate();
                $this->sendUpdate('Yangi shablon tayyorlandi', 15);

                $progressPerTable = 80 / count($this->tables);
                $currentProgress = 15;

                foreach ($this->tables as $table) {
                    $this->sendUpdate($table . ' ma\'lumotlari to\'ldirilmoqda...', $currentProgress);

                    $sheetName = $this->getSheetNameForTable($table);
                    $sheet = $spreadsheet->getSheetByName($sheetName);

                    if (!$sheet) {
                        // Agar mavjud bo'lmasa, yangi list yaratish
                        $sheet = $spreadsheet->createSheet();
                        $sheet->setTitle($sheetName);
                        $this->setupSheetTemplate($sheet, $table);
                    }

                    $methodName = 'fill' . str_replace('_', '', ucfirst($table)) . 'Data';
                    if (method_exists($this, $methodName)) {
                        PointUserDeportament::with([$table])
                            ->where('status', 1)
                            ->chunk(80000, function ($chunk) use ($sheet, $methodName) {
                                $this->$methodName($sheet, $chunk);
                            });
                    } else {
                        PointUserDeportament::with([$table])
                            ->where('status', 1)
                            ->chunk(80000, function ($chunk) use ($sheet, $table) {
                                $this->fillDefaultData($sheet, $chunk, $table);
                            });
                    }

                    $currentProgress += $progressPerTable;
                    $this->sendUpdate($table . ' ma\'lumotlari to\'ldirildi', $currentProgress);
                }

                // Yangi shablonni saqlash
                $templatePath = storage_path('app/templates/base_template_new.xlsx');
                $templateWriter = new Xlsx($spreadsheet);
                $templateWriter->save($templatePath);
                $this->sendUpdate('Yangi shablon saqlandi', 90);

                $this->sendUpdate('Excel fayl tayyorlanmoqda...', 95);
                $filename = 'all_data_' . time() . '.xlsx';
                $path = storage_path('app/public/' . $filename);

                $writer = new Xlsx($spreadsheet);
                $writer->save($path);

                $this->sendUpdate('Excel fayl saqlandi va u endi yuklab olinadi, 1-3 minut kuting...', 98);

                // Faylni yuborish
                $fileContent = file_get_contents($path);
                $this->sendUpdate('Fayl yuklanmoqda...', 99);
                echo "data: " . json_encode(['type' => 'file', 'content' => base64_encode($fileContent), 'filename' => $filename]) . "\n\n";
                flush();

                // Faylni o'chirish
                unlink($path);

                $this->sendUpdate('Yuklash tugadi', 100);
            } catch (\Exception $e) {
                $this->sendUpdate('Xatolik yuz berdi: ' . $e->getMessage(), 100);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    /**
     * Yangi shablon yaratish
     */
    private function createNewTemplate()
    {
        $spreadsheet = new Spreadsheet();

        // Birinchi varaqni o'chirish (default sheet)
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));
        if ($sheetIndex !== null) {
            $spreadsheet->removeSheetByIndex($sheetIndex);
        }

        // Har bir jadval uchun varaq yaratish
        foreach ($this->tables as $table) {
            $sheetName = $this->getSheetNameForTable($table);
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($sheetName);

            // Varaqni tayyorlash (sarlavhalar, stillar)
            $this->setupSheetTemplate($sheet, $table);
        }

        return $spreadsheet;
    }

    /**
     * Varaqlarda shablonni o'rnatish (sarlavhalar, stillar, va h.k.)
     */
    /**
     * Varaqlarda shablonni o'rnatish (sarlavhalar, stillar, va h.k.)
     */
    private function setupSheetTemplate($sheet, $tableName)
    {
        // Sarlavha qo'shish - jadval nomini katta harflarda ko'rsatish
        $tableTitle = strtoupper($this->getSheetNameForTable($tableName));
        $sheet->setCellValue('A1', $tableTitle);

        // Sarlavha qancha ustunlarni qamrab olishi kerakligini aniqlash
        $headerCount = isset($this->tableHeaders[$tableName]) ? count($this->tableHeaders[$tableName]) : 10;
        $lastColumn = chr(64 + $headerCount); // ASCII code: A = 65, B = 66, etc.
        $sheet->mergeCells('A1:' . $lastColumn . '1');

        // Sarlavha stili
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'name' => 'Times New Roman',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Qatorlar balandligi o'rnatish - Ma'lumotlar uchun boshlang'ich qator soni
        for ($i = 1; $i <= 6; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }
        $sheet->getRowDimension(1)->setRowHeight(40); // Sarlavha qatori balandroq

        // Ustun sarlavhalari
        if (isset($this->tableHeaders[$tableName])) {
            $headers = $this->tableHeaders[$tableName];
            $row = 1; // Ustun sarlavhalari qatori
            $maxRowHeight = 40; // Minimum qator balandligi

            foreach ($headers as $column => $header) {
                $sheet->setCellValue($column . $row, $header);

                // Ustun kengligini satr uzunligiga qarab kattalashtirib berish
                // Har bir belgi uchun o'rtacha 8 piksel, qo'shimcha 40 piksel (20px*2 padding)
                $headerLength = mb_strlen($header, 'UTF-8');
                $wordsCount = str_word_count($header) ?: 1; // So'zlar soni
                $averageLineLength = 25; // O'rtacha satr uzunligi

                // Satrlar sonini hisoblash - har 25 belgidan so'ng yangi satr
                $estimatedLines = ceil($headerLength / $averageLineLength);

                // Ustun kengligi - belgilar soni + padding
                $colWidth = min(max(15, $headerLength * 0.9 + 5), 80);
                $sheet->getColumnDimension($column)->setWidth($colWidth);

                // Qator balandligini hisoblash - har bir satr 15px
                $rowHeightNeeded = max(40, $estimatedLines * 20);
                $maxRowHeight = max($maxRowHeight, $rowHeightNeeded);

                // Ustunga stil berish
                $sheet->getStyle($column . $row)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'name' => 'Times New Roman',
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                        'indent' => 1, // 1 birlik indentatsiya (~8-10px)
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFEEEEEE'],
                    ],
                ]);
            }

            // Header qatorining balandligini o'rnatish
            $sheet->getRowDimension($row)->setRowHeight($maxRowHeight);

            // Ma'lumotlar tushadigan joyni belgilash (7-qatordan boshlab)
            $sheet->getStyle('A7')->getFont()->setBold(true);
        }

        // Umumiy still o'rnatish
        $range = 'A1:' . $lastColumn . '1000';
        $sheet->getStyle($range)->getFont()->setName('Times New Roman');

        // Qo'shimcha formatlash - hamma ustunlarda matn o'raladi va padding qo'shiladi
        for ($i = 7; $i <= 100; $i++) {
            for ($col = 'A'; $col <= $lastColumn; $col++) {
                $sheet->getStyle($col . $i)->getAlignment()->setWrapText(true);
                $sheet->getStyle($col . $i)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle($col . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle($col . $i)->getAlignment()->setIndent(2); // Matnga padding qo'shish (~20px)

                // Har bir qatorga chegaralar qo'shish
                $sheet->getStyle($col . $i)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            }
            // Qator balandligini to'g'rilash
            $sheet->getRowDimension($i)->setRowHeight(30);
        }

        // Birinchi ustun (№) kengligini o'rnatish
        $sheet->getColumnDimension('A')->setWidth(5);

        // Kafedra nomi ustuni kengligini o'rnatish
        $sheet->getColumnDimension('B')->setWidth(30);

        // F.I.SH. ustuni kengligini o'rnatish
        $sheet->getColumnDimension('C')->setWidth(30);

        // Ekranga sig'ishini ta'minlash uchun ko'rish sohasi (view) o'rnatish
        $sheet->getSheetView()->setZoomScale(85); // 85% zoom

        // Birinchi qator va ustun muzlatish
        $sheet->freezePane('A7');
    }

    private function sendUpdate($message, $progress)
    {
        \Log::info("Sending update: $message, Progress: $progress");
        echo "data: " . json_encode(['message' => $message, 'progress' => $progress]) . "\n\n";
        ob_flush();
        flush();
    }

    private function getSheetNameForTable($table)
    {
        return str_replace('_', ' ', ucwords($table));
    }

    private function manageMemory($callback, $sheet, $pointUserDeportaments)
    {
        try {
            // Xotirani oshirish
            ini_set('memory_limit', '40G');

            // Ma'lumotlarni qismlab olish
            $chunkSize = 80000;
            $totalChunks = ceil($pointUserDeportaments->count() / $chunkSize);
            $currentChunk = 0;

            foreach ($pointUserDeportaments->chunk($chunkSize) as $chunk) {
                $currentChunk++;

                $callback($sheet, $chunk);

                // Har bir qismdan so'ng xotirani tozalash
                $this->clearMemory($sheet);

                // Progressni log qilish
                $progress = round(($currentChunk / $totalChunks) * 100, 2);
            }

            // Oxirida qo'shimcha xotirani tozalash
            $sheet->garbageCollect();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            // Xotirani tozalash
            $this->clearMemory($sheet);
        }
    }

    private function clearMemory($sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Foydalanilmagan qatorlarni tozalash
        for ($row = $highestRow; $row > $highestRow - 100 && $row > 1; $row--) {
            $range = 'A' . $row . ':' . $highestColumn . $row;
            $sheet->removeConditionalStyles($range);
        }

        $sheet->garbageCollect();
        Calculation::getInstance()->flushInstance();
        gc_collect_cycles();
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function fillDefaultData($sheet, $pointUserDeportaments, $table)
    {
        $sheet->setCellValue('A1', 'Default data for ' . $table);
        $sheet->setCellValue('A2', 'Total records: ' . count($pointUserDeportaments));
    }
    // Yangi metodlarni qo'shamiz

    private function fillTable2Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable3Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_3_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable4Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_4_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable7Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_7_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable81Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_8_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable82Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_8_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable91Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_9_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable92Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_9_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable101Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_10_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable102Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_10_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable103Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_10_3_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable111Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_11_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable112Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_11_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable112aData($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_11_3_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable12Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_12_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable13Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_13_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable141Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_14_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable142Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_14_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable143Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_14_3_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable151Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_15_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable152Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_15_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable16Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_16_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable171Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_17_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable172Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_17_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable181Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_18_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable182Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_18_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable183Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_18_3_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable183aData($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_18_3_a_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable19Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_19_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable201Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_20_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable202Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_20_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable203Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_20_3_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable211Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_21_1_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable212Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_21_2_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable22Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_22_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable23Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_23_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable24Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $tableData = new Table_24_DataCode();
            $tableData->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function logExcelData($path, $sheetName)
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($path);
        $worksheet = $spreadsheet->getSheetByName($sheetName);

        if ($worksheet) {
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            \Log::info("Sheet '$sheetName': Highest row: $highestRow, Highest column: $highestColumn");
        } else {
            \Log::warning("Sheet '$sheetName' not found in the Excel file.");
        }
    }
}
