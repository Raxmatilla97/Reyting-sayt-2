<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PointUserDeportament;
use App\Models\Tables\Table_11_1_;
use App\Models\Tables\Table_11_2_;
use App\Models\Tables\Table_20_1_;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TestDuplicatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тестовые дубликатлар яратиш';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Тестовые дубликатлар яратилмоқда...');

        try {
            DB::beginTransaction();

            // Биринчи фойдаланувчини топиш
            $user = User::first();
            if (!$user) {
                $this->error('Фойдаланувчи топилмади!');
                return;
            }

            $this->info("Фойдаланувчи: {$user->name}");

            // Table 11 учун тестовые дубликатлар
            $this->createTable11Duplicates($user);
            
            // Table 20 учун тестовые дубликатлар
            $this->createTable20Duplicates($user);

            DB::commit();
            $this->info('Тестовые дубликатлар муваффақиятли яратилди!');

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Хато: ' . $e->getMessage());
        }
    }

    private function createTable11Duplicates($user)
    {
        $this->info('Table 11 дубликатлари яратилмоқда...');

        // Table_11_1 ёзув
        $table111 = Table_11_1_::create([
            'jurnal_nomi' => 'International Journal of Computer Science and Information Technologies',
            'maqola_nomi' => 'Machine Learning Applications in Healthcare Systems',
            'nashr_yili' => '2023',
            'tili' => 'English',
            'index_url' => 'https://scopus.com/article1',
            'iqtiboslar_soni' => '15'
        ]);

        PointUserDeportament::create([
            'user_id' => $user->id,
            'table_11_1_id' => $table111->id,
            'point' => 5,
            'year' => 2023,
            'status' => 1,
            'created_at' => now()->subDays(5)
        ]);

        // Table_11_2 ўхшаш ёзув (дубликат)
        $table112 = Table_11_2_::create([
            'jurnal_nomi' => 'International Journal of Computer Science and Information Technology',
            'maqola_nomi' => 'Machine Learning Applications in Healthcare System',
            'nashr_yili' => '2023',
            'tili' => 'English',
            'index_url' => 'https://webofscience.com/article2',
            'iqtiboslar_soni' => '12'
        ]);

        PointUserDeportament::create([
            'user_id' => $user->id,
            'table_11_2_id' => $table112->id,
            'point' => 3,
            'year' => 2023,
            'status' => 1,
            'created_at' => now()->subDays(3)
        ]);

        // Яна бир Table_11_3 дубликат (агар мавжуд бўлса)
        if (class_exists('\App\Models\Tables\Table_11_3_')) {
            try {
                $table113 = \App\Models\Tables\Table_11_3_::create([
                    'jurnal_nomi' => 'International Journal of Computer Science & Information Technologies',
                    'maqola_nomi' => 'Machine Learning Applications in Healthcare',
                    'nashr_yili' => '2023',
                    'tili' => 'English',
                    'index_url' => 'https://ieee.org/article3',
                    'iqtiboslar_soni' => '18'
                ]);

                PointUserDeportament::create([
                    'user_id' => $user->id,
                    'table_11_3_id' => $table113->id,
                    'point' => 4,
                    'year' => 2023,
                    'status' => 1,
                    'created_at' => now()->subDays(1)
                ]);
            } catch (\Exception $e) {
                $this->warn('Table_11_3_ модели мавжуд эмас: ' . $e->getMessage());
            }
        }

        $this->info('Table 11 дубликатлари яратилди');
    }

    private function createTable20Duplicates($user)
    {
        $this->info('Table 20 дубликатлари яратилмоқда...');

        // Table_20_1 ёзув
        $table201 = Table_20_1_::create([
            'jurnal_nomi' => 'Scientific Research and Development Journal',
            'mualliflar_soni' => '3',
            'asos_url' => 'https://example.com/research1'
        ]);

        PointUserDeportament::create([
            'user_id' => $user->id,
            'table_20_1_id' => $table201->id,
            'point' => 2,
            'year' => 2023,
            'status' => 1,
            'created_at' => now()->subDays(4)
        ]);

        // Table_20_2 ўхшаш ёзув (агар мавжуд бўлса)
        if (class_exists('\App\Models\Tables\Table_20_2_')) {
            try {
                $table202 = \App\Models\Tables\Table_20_2_::create([
                    'jurnal_nomi' => 'Scientific Research and Development',
                    'mualliflar_soni' => '3',
                    'asos_url' => 'https://example.com/research2'
                ]);

                PointUserDeportament::create([
                    'user_id' => $user->id,
                    'table_20_2_id' => $table202->id,
                    'point' => 1,
                    'year' => 2023,
                    'status' => 1,
                    'created_at' => now()->subDays(2)
                ]);
            } catch (\Exception $e) {
                $this->warn('Table_20_2_ модели мавжуд эмас: ' . $e->getMessage());
            }
        }

        $this->info('Table 20 дубликатлари яратилди');
    }
}
