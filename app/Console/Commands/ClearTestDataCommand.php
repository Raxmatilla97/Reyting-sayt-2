<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PointUserDeportament;
use App\Models\Tables\Table_11_1_;
use App\Models\Tables\Table_11_2_;
use App\Models\Tables\Table_20_1_;
use Illuminate\Support\Facades\DB;

class ClearTestDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:clear-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тестовые дубликатларни тозалаш';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Тестовые дубликатлар тозаланмоқда...');

        try {
            DB::beginTransaction();

            // Table 11 тестовые ёзувларни ўчириш
            $this->info('Table 11 тестовые маълумотларни ўчирмоқда...');
            
            // PointUserDeportament ёзувларини топиш ва ўчириш
            $table11Records = PointUserDeportament::where(function($query) {
                $query->whereNotNull('table_11_1_id')
                      ->orWhereNotNull('table_11_2_id')
                      ->orWhereNotNull('table_11_3_id');
            })->get();

            foreach ($table11Records as $record) {
                if ($record->table_11_1_id) {
                    Table_11_1_::find($record->table_11_1_id)?->delete();
                }
                if ($record->table_11_2_id) {
                    Table_11_2_::find($record->table_11_2_id)?->delete();
                }
                if ($record->table_11_3_id && class_exists('\App\Models\Tables\Table_11_3_')) {
                    \App\Models\Tables\Table_11_3_::find($record->table_11_3_id)?->delete();
                }
                $record->delete();
            }

            // Table 20 тестовые ёзувларни ўчириш
            $this->info('Table 20 тестовые маълумотларни ўчирмоқда...');
            
            $table20Records = PointUserDeportament::where(function($query) {
                $query->whereNotNull('table_20_1_id')
                      ->orWhereNotNull('table_20_2_id')
                      ->orWhereNotNull('table_20_3_id');
            })->get();

            foreach ($table20Records as $record) {
                if ($record->table_20_1_id) {
                    Table_20_1_::find($record->table_20_1_id)?->delete();
                }
                if ($record->table_20_2_id && class_exists('\App\Models\Tables\Table_20_2_')) {
                    \App\Models\Tables\Table_20_2_::find($record->table_20_2_id)?->delete();
                }
                if ($record->table_20_3_id && class_exists('\App\Models\Tables\Table_20_3_')) {
                    \App\Models\Tables\Table_20_3_::find($record->table_20_3_id)?->delete();
                }
                $record->delete();
            }

            DB::commit();
            $this->info('Тестовые дубликатлар муваффақиятли тозаланди!');

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Хато: ' . $e->getMessage());
        }
    }
}
