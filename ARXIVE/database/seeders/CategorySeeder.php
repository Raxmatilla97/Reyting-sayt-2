<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Barcha reyting kategoriyalari nomlari massivga joylangan!
        $data = [
            [
                'name' => 'OTMning asosiy shtatdagi PO‘lari soni',
            ],
            [
                'name' => 'Talabalar soni (sirtqi bo‘lim talabalari tuzatish koeffitsienti orkali qo‘shilgan)',
            ],
            [
                'name' => 'Dunyoning nufuzli 1000 taligiga kiruvchi OTMlarning PhD yoki DSc ilmiy darajasini olgan PO‘lar soni',
            ],
            [
                'name' => 'Dunyoning nufuzli 1000 taligiga kiruvchi OTMlarida o‘quv mashg‘ulotlari o‘tkazgan PO‘lar',
            ],
            [
                'name' => 'Fan doktori (DSc) ilmiy darajasiga ega PO‘lar ulushi',
            ],
            [
                'name' => 'Professor unvoniga ega PO‘lar ulushi',
            ],
            [
                'name' => 'Fan nomzodi (PhD) ilmiy darajasiga ega PO‘lar ulushi',
            ],
            [
                'name' => 'Dotsent unvoniga ega PO‘lar ulushi',
            ],
            [
                'name' => 'Ilmiy saloxiyat, %',
            ],
            [
                'name' => 'Hisob yilida (2023) dissertatsiya himoyalari soni (shu jumladan, berilgan professor yoki dotsent ilmiy unvonlari ham)',
            ],
            [
                'name' => 'Halqaro ko‘rsatkichlarga ko‘ra professor-o‘qituvchilarning ilmiy maqolalariga «Google Scholar» bazasida mavjud bo‘lgan jurnallar bo‘yicha iqtiboslar haqida',
            ],
            [
                'name' => 'Halqaro ko‘rsatkichlarga ko‘ra professor-o‘qituvchilarning ilmiy maqolalariga «Web of Science», «Scopus» bazalarida mavjud bo‘lgan jurnallar bo‘yicha iqtiboslar haqida',
            ],
            [
                'name' => 'Xalqaro jurnallardagi ilmiy maqolalar soni',
            ],
            [
                'name' => '«Web of Science», «Scopus» bazasiga kiritilgan jurnallarda',
            ],
            [
                'name' => 'Respublika ilmiy jurnallaridagi (OAK ro‘yxatidagi) ilmiy maqolalar soni',
            ],
            [
                'name' => 'Xorijiy ilmiy tadqiqot markazlari grantlari va xorijiy ilmiy fondlari mablag‘lari',
            ],
            [
                'name' => 'Sohalar buyurtmalari asosida o‘tkazilgan tadqiqotlardan olingan mablag‘lar',
            ],
            [
                'name' => 'Davlat grantlari asosida o‘tkazilgan tadqiqotlardan olingan mablag‘lar',
            ],
            [
                'name' => 'Nashr etilgan monografiyalar soni',
            ],
            [
                'name' => 'Intellektual mulk uchun olingan himoya hujjatlari (patentlar) soni',
            ],
            [
                'name' => 'Axborot-kommunikatsiya texnologiyalariga oid dasturlar va elektron bazalari uchun olingan guvohnomalar, mualliflik huquqi bilan himoya qilinadigan turli materiallar soni',
            ],
            [
                'name' => 'Darsliklar soni',
            ],
            [
                'name' => 'O‘quv qo‘llanmalar soni',
            ],
            [
                'name' => 'Xorijiy o‘qituvchilar soni',
            ],
            [
                'name' => 'Xorijiy talabalar soni',
            ],
            [
                'name' => 'Akademik almashuv dasturlarda ishtirok etuvchi talabalar soni',
            ],
            [
                'name' => 'Xalqaro konferensiya va seminarlarda, ilmiy yoki ta’lim loyihalarida (xorijiy, qo‘shma) ishtirok etgan talabalar soni',
            ],
            [
                'name' => 'Xalqaro konferensiya va seminarlarda, ilmiy yoki ta’lim loyihalarida (xorijiy, qo‘shma) ishtirok etgan PO‘lar soni',
            ],
            [
                'name' => 'Horijiy tilda o‘qitiladigan o‘quv kurs(fan)lari soni',
            ],
            [
                'name' => 'Hisob yilida OTMni bitirgan bitiruvchilar soni',
            ],
            [
                'name' => 'Ishga joylashgan bitiruvchilar soni',
            ],
            [
                'name' => 'Xalqaro olimpiadalarda va nufuzli tanlovlarda sovrinli o‘rinlarni qo‘lga kiritgan va mukofot (diplom)larga sozovor bo‘lgan hamda O‘zbekiston Respublikasi Prezidenti davlat stipendiyasi sovrindorlari bo‘lgan talabalar soni',
            ],
            [
                'name' => 'Respublika olimpiadalarda va nufuzli tanlovlarda sovrinli o‘rinlarni qo‘lga kiritgan va mukofot (diplom)larga sazovor bo‘lgan talabalar soni',
            ],
            [
                'name' => 'Sport klubiga a’zo bo‘lib, tashkil etilgan sport seksiyalarida jismoniy tarbiya va sport bilan muntazam shug‘ullanuvchi sport tasnifiga (sportchi razryadlari) ega bo‘lgan talabalar*',
            ],
            [
                'name' => 'Oliy ta’lim muassasasining sport moddiy texnik bazasi bilan ta’minlanganligi',
            ],
        ];

        // Massivdagi har bir ma'lumotni olish va ma'lumotlar bazasiga joylash
        foreach ($data as $item) {
            $item['slug'] = Str::slug($item['name']);
            $item['status'] = true;
            Category::create($item);
        }
    }
}
