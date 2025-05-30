<?php
// max_points_dep_emp.php
return [
    'department' => [
        'table_22_' => ['max' => 5],  // Akademik almashuv dasturlarda ishtirok etuvchi talabalar soni
        'table_23_' => ['max' => 5],  // Yetakchi xorijiy mamlakatlarning ta'lim muassasalaridan taklif etilib, dars berayotgan xorijiy o'qituvchilar
        'table_24_' => ['max' => 10],  // Talabalarning Scopus, Web of Science bazalaridagi maqolalari
    ],
    'employee' => [
        'table_2_' => ['max' => 4],    // Dunyoning nufuzli 1000 taligiga kirgan xorijiy OTMlarda Phd/DSc ilmiy darajasini olganligi
        'table_3_' => ['max' => 3],    // Dunyoning nufuzli 500 taligiga kirgan xorijiy OTMlarda magistratura bosqichida tahsil olganligi
        'table_4_' => ['max' => 6],    // Darslik
        'table_5_' => ['max' => 3],    // O'quv qo'llanma
        'table_6_' => ['max' => 3],    // Dunyoning nufuzli 1000 taligiga kirgan xorijiy OTMlarda o'quv mashg'ulotlari (ma'ruzalar, amaliy mashg'ulotlar, seminarlar) o'tkazganligi
        'table_7_' => ['max' => 3],    // So'nggi uch yilda xorijiy TOP-1000 OTMlarda (yoki Coursera, Udemy, EdX kabi ta'lim platformalarida) mutaxassisligi bo'yicha malaka oshirish kurslaridan o'tganligi
        'table_8_1_' => ['max' => 3],  // Xorijiy tillarni bilish darajasini aniqlovchi sertifikatlari (kamida B2; xorijiy til o'qituvchisi uchun C1)
        'table_8_2_' => ['max' => 2],  // Mutaxassisligi bo'yicha xorijiy til (ingliz tili)da dars berganligi
        'table_9_1_' => ['max' => 5],  // Monografiya (Scopus, Web of Science bazalarida indekslangan)
        'table_9_2_' => ['max' => 3],  // Monografiya (Mahalliy nashriyotlarda)
        'table_10_1_' => ['max' => 15], // Q1-Q2 holatida Scopus, Web of Science bazalarida indekslangan maqola
        'table_10_2_' => ['max' => 10], // Q3 holatida Scopus, Web of Science bazalarida indekslangan maqola
        'table_10_3_' => ['max' => 5], // Q4 holatida Scopus, Web of Science bazalarida indekslangan maqola yoki Scopus, Web of Science bazalarida indekslangan konferensiyada tezis
        'table_11_1_' => ['max' => 5], // Scopus, Web of Sicence bazalaridagi iqtibosga egalik
        'table_11_2_' => ['max' => 3], // ResearchGate platformasidagi iqtibosga egalik
        'table_11_3_' => ['max' => 2], // Google Scholar plarformasidagi iqtibosga egalik -> table_11_2_a_
        'table_12_' => ['max' => 5],   // OAK ro'yxatidagi xorijiy ilmiy jurnallarda maqola
        'table_13_' => ['max' => 2],   // OAK ro'yxatidagi mahalliy ilmiy jurnallarda maqola
     
        'table_14_1_' => ['max' => 3], // Professor-o'qituvchining xalqaro konferensiya va seminarlarda, ilmiy yoki ta’lim loyihalarida (xorijiy, qo‘shma) ma'ruzachi sifatida ishtirok etganligi
        'table_14_2_' => ['max' => 2], // Professor-o'qituvchining xalqaro konferensiya va seminarlarda, ilmiy yoki ta’lim loyihalarida (xorijiy, qo‘shma) ishtirok etganligi
        'table_14_3_' => ['max' => 2], // Talabalarning xalqaro konferensiya va seminarlarda, ilmiy yoki ta’lim loyihalarida (xorijiy, qo‘shma) ishtirok etganligi (bir PO'ga 10 talaba)
        'table_15_1_' => ['max' => 7], // Xalqaro va mahalliy ilmiy (amaliy, innovatsion va fundamental) loyihalar: rahbarlik qilish
        'table_15_2_' => ['max' => 4], // Xalqaro va mahalliy ilmiy (amaliy, innovatsion va fundamental) loyihalar: ishtirok etish
        'table_16_' => ['max' => 5],   // Xo‘jalik shartnomalarida ishtirok etish (professsor-o‘qituvchiga belgilangan rejaning 100 % - 2 mln so'm bajarilganda)
        'table_17_1_' => ['max' => 4], // Ixtirolar, patentlar
        'table_17_2_' => ['max' => 1], // Axborot-kommunikatsiya texnologiyalariga oid dasturlar va elektron bazalari uchun olingan guvohnomalar, mualliflik huquqi bilan himoya qilinadigan turli materiallar

        'table_18_1_' => ['max' => 1], // Professor
        'table_18_2_' => ['max' => 1], // Fan doktori (DSc)
        'table_18_3_' => ['max' => 1], // Dotsent
        'table_18_3_a_' => ['max' => 1], // Fan nomzodi / Falsafa doktori (PhD)
        'table_19_' => ['max' => 6],   // Hisob yilida (2025) PhD/DSc ilmiy daraja (shu jumladan, berilgan professor yoki dotsent ilmiy unvoni) olganligi
        'table_20_1_' => ['max' => 5], // Xalqaro/Respublika miqyosda Radio efir/TV/ommabop gazeta yoki jurnalda universitet nomidan chiqish
        'table_20_2_' => ['max' => 3], // Viloyat miqyosda Radio efir/TV/ommabop gazeta yoki jurnalda universitet nomidan chiqish
        'table_20_3_' => ['max' => 1], // Tuman/shahar miqyosda Radio efir/TV/ommabop gazeta yoki jurnalda universitet nomidan chiqish
        'table_21_1_' => ['max' => 5], // Professor-o'qituvchining nufuzli xalqaro tanlovlarda sovrinli o'rinlarni qo'lgan kiritganligi yoki uning rahbarligida xalqaro olimpiadalarda va nufuzli tanlovlarda sovrinli o'rinlarni qo'lga kiritgan va mukofot (diplom)larga sozovor bo'lgan hamda O'zbekiston Respublikasi Prezidenti davlat stipendiyasi sovrindorlari bo'lgan talabalar
        'table_21_2_' => ['max' => 3], // Professor-o'qituvchining nufuzli Respublika tanlovlarida sovrinli o'rinlarni qo'lgan kiritganligi yoki uning rahbarligida Respublika olimpiadalarda va nufuzli tanlovlarda sovrinli o'rinlarni qo'lga kiritgan va mukofot (diplom)larga sazovor bo'lgan talabalar
    ],
];
