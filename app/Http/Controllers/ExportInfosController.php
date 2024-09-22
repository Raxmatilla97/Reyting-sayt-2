@ -0,0 +1,551 @@
<?php

namespace App\Http\Controllers;

use Log;
use App\Models\PointUserDeportament;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Export\Table_1_1_DataCode;
use App\Http\Controllers\Export\Table_1_2_DataCode;
use App\Http\Controllers\Export\Table_1_3_1_a_DataCode;
use App\Http\Controllers\Export\Table_1_3_1_b_DataCode;
use App\Http\Controllers\Export\Table_1_3_2_a_DataCode;
use App\Http\Controllers\Export\Table_1_3_2_b_DataCode;
use App\Http\Controllers\Export\Table_1_4_DataCode;
use App\Http\Controllers\Export\Table_1_5_1_DataCode;
use App\Http\Controllers\Export\Table_1_5_1_a_DataCode;
use App\Http\Controllers\Export\Table_1_6_1_DataCode;
use App\Http\Controllers\Export\Table_1_6_1_a_DataCode;
use App\Http\Controllers\Export\Table_1_6_2_DataCode;
use App\Http\Controllers\Export\Table_1_7_1_DataCode;
use App\Http\Controllers\Export\Table_1_7_2_DataCode;
use App\Http\Controllers\Export\Table_1_7_3_DataCode;
use App\Http\Controllers\Export\Table_1_9_1_DataCode;
use App\Http\Controllers\Export\Table_1_9_2_DataCode;
use App\Http\Controllers\Export\Table_1_9_3_DataCode;
use App\Http\Controllers\Export\Table_2_2_1_DataCode;
use App\Http\Controllers\Export\Table_2_2_2_DataCode;
use App\Http\Controllers\Export\Table_2_3_1_DataCode;
use App\Http\Controllers\Export\Table_2_3_2_DataCode;
use App\Http\Controllers\Export\Table_2_4_1_DataCode;
use App\Http\Controllers\Export\Table_2_4_2_DataCode;
use App\Http\Controllers\Export\Table_2_4_2_b_DataCode;
use App\Http\Controllers\Export\Table_2_5_DataCode;
use App\Http\Controllers\Export\Table_3_4_1_DataCode;
use App\Http\Controllers\Export\Table_3_4_2_DataCode;
use App\Http\Controllers\Export\Table_4_1_DataCode;


class ExportInfosController extends Controller
{
    private $progress = 0;


    public function export()
    {

        return view('dashboard.download');
    }

    public function download()
    {
        return new StreamedResponse(function () {
            try {
                $this->sendUpdate('Boshlash', 0);

                // Ma'lumotlarni yuklash
                $this->sendUpdate('Ma\'lumotlar yuklanmoqda...', 5);
                $pointUserDeportaments = PointUserDeportament::with([
                    'department',
                    'employee',
                    'table_1_1',
                    'table_1_2',
                    'table_1_3_1_a',
                    'table_1_3_1_b',
                    'table_1_3_2_a',
                    'table_1_3_2_b',
                    'table_1_4',
                    'table_1_5_1',
                    'table_1_5_1_a',
                    'table_1_6_1',
                    'table_1_6_2',
                    'table_1_7_1',
                    'table_1_7_2',
                    'table_1_7_3',
                    'table_1_9_1',
                    'table_1_9_2',
                    'table_1_9_3',
                    'table_2_2_1',
                    'table_2_2_2',
                    'table_2_3_1',
                    'table_2_3_2',
                    'table_2_4_1',
                    'table_2_4_2',
                    'table_2_4_2_b',
                    'table_2_5',
                    'table_3_4_1',
                    'table_3_4_2',
                    'table_4_1',


                ])
                    ->where('status', 1)
                    ->get();
                $this->sendUpdate('Ma\'lumotlar yuklandi', 10);

                // Shablonni yuklash
                $this->sendUpdate('Shablon yuklanmoqda...', 15);
                $templatePath = storage_path('app/templates/base_template.xlsx');
                $spreadsheet = IOFactory::load($templatePath);
                $this->sendUpdate('Shablon yuklandi', 20);

                $tables = [
                    'table_1_1',
                    'table_1_2',
                    'table_1_3_1_a',
                    'table_1_3_1_b',
                    'table_1_3_2_a',
                    'table_1_3_2_b',
                    'table_1_4',
                    'table_1_5_1',
                    'table_1_5_1_a',
                    'table_1_6_1',
                    'table_1_6_1_a',
                    'table_1_6_2',
                    'table_1_7_1',
                    'table_1_7_2',
                    'table_1_7_3',
                    'table_1_9_1',
                    'table_1_9_2',
                    'table_1_9_3',
                    'table_2_2_1',
                    'table_2_2_2',
                    'table_2_3_1',
                    'table_2_3_2',
                    'table_2_4_1',
                    'table_2_4_2',
                    'table_2_4_2_b',
                    'table_2_5',
                    'table_3_4_1',
                    'table_3_4_2',
                    'table_4_1',


                ];

                $progressPerTable = 60 / count($tables);
                $currentProgress = 20;

                foreach ($tables as $index => $table) {
                    $this->sendUpdate($table . ' ma\'lumotlari to\'ldirilmoqda...', $currentProgress);

                    $sheetName = $this->getSheetNameForTable($table);
                    $sheet = $spreadsheet->getSheetByName($sheetName);

                    if (!$sheet) {
                        $sheet = $spreadsheet->createSheet();
                        $sheet->setTitle($sheetName);
                    }

                    $methodName = 'fill' . str_replace('_', '', ucfirst($table)) . 'Data';
                    if (method_exists($this, $methodName)) {
                        $this->$methodName($sheet, $pointUserDeportaments);
                    } else {
                        $this->fillDefaultData($sheet, $pointUserDeportaments, $table);
                    }

                    $currentProgress += $progressPerTable;
                    $this->sendUpdate($table . ' ma\'lumotlari to\'ldirildi', $currentProgress);
                }

                $this->sendUpdate('Excel fayl tayyorlanmoqda...', 85);
                $filename = 'all_data_' . time() . '.xlsx';
                $path = storage_path('app/public/' . $filename);

                $writer = new Xlsx($spreadsheet);
                $writer->save($path);

                $this->sendUpdate('Excel fayl saqlandi va u endi yuklab olinadi, 1-3 minut kuting...', 95);

                // Excel sahifalarini nomlari tekshirish
                $this->logExcelData($path, 'Table 1 1'); // Qo'shimcha sahifani ham tekshiramiz
                $this->logExcelData($path, 'Table 1 2');
                $this->logExcelData($path, 'Table 1 3 1 a');
                $this->logExcelData($path, 'Table 1 3 1 b');
                $this->logExcelData($path, 'Table 1 3 2 a');
                $this->logExcelData($path, 'Table 1 3 2 b');
                $this->logExcelData($path, 'Table 1 4');
                $this->logExcelData($path, 'Table 1 5 1');
                $this->logExcelData($path, 'Table 1 5 1 a');
                $this->logExcelData($path, 'Table 1 6 1');
                $this->logExcelData($path, 'Table 1 6 1 a');
                $this->logExcelData($path, 'Table 1 6 2');
                $this->logExcelData($path, 'Table 1 7 1');
                $this->logExcelData($path, 'Table 1 7 2');
                $this->logExcelData($path, 'Table 1 7 3');
                $this->logExcelData($path, 'Table 1 9 1');
                $this->logExcelData($path, 'Table 1 9 2');
                $this->logExcelData($path, 'Table 1 9 3');
                $this->logExcelData($path, 'Table 2 2 1');
                $this->logExcelData($path, 'Table 2 2 2');
                $this->logExcelData($path, 'Table 2 3 1');
                $this->logExcelData($path, 'Table 2 3 2');
                $this->logExcelData($path, 'Table 2 4 1');
                $this->logExcelData($path, 'Table 2 4 2');
                $this->logExcelData($path, 'Table 2 4 2 b');
                $this->logExcelData($path, 'Table 2 5');
                $this->logExcelData($path, 'Table 3 4 1');
                $this->logExcelData($path, 'Table 3 4 2');
                $this->logExcelData($path, 'Table 4 1');

                $this->sendUpdate('Fayl yuklab olishga tayyor', 98);

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

    private function sendUpdate($message, $progress)
    {
        $this->progress = $progress;
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
            ini_set('memory_limit', '8G'); // 8GB ga oshirildi

            // Ma'lumotlarni qismlab olish
            $chunkSize = 500; // Chunkning hajmi kamaytirildi
            $totalChunks = ceil($pointUserDeportaments->count() / $chunkSize);
            $currentChunk = 0;

            foreach ($pointUserDeportaments->chunk($chunkSize) as $chunk) {
                $currentChunk++;
                // Log::info("Processing chunk {$currentChunk} of {$totalChunks}");

                $callback($sheet, $chunk);

                // Har bir qismdan so'ng xotirani tozalash
                $this->clearMemory($sheet);

                // Progressni log qilish
                $progress = round(($currentChunk / $totalChunks) * 100, 2);
                // Log::info("Progress: {$progress}%");
            }

            // Oxirida qo'shimcha xotirani tozalash
            $sheet->garbageCollect();
            // Log::info("Memory cleanup completed");
        } catch (\Exception $e) {
            // Log::error('Error in data processing: ' . $e->getMessage());
            // Log::error('Error trace: ' . $e->getTraceAsString());
            throw $e;
        } finally {
            // Xotirani tozalash
            $this->clearMemory($sheet);
            // Log::info("Final memory cleanup completed");
            // Log::info("Peak memory usage: " . $this->formatBytes(memory_get_peak_usage(true)));
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

    private function fillTable11Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table12 = new Table_1_1_DataCode();
            $table12->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable12Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table11 = new Table_1_2_DataCode();
            $table11->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable131aData($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table131a = new Table_1_3_1_a_DataCode();
            $table131a->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable131bData($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table131b = new Table_1_3_1_b_DataCode();
            $table131b->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable132aData($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table132a = new Table_1_3_2_a_DataCode();
            $table132a->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable132bData($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table132b = new Table_1_3_2_b_DataCode();
            $table132b->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable14Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table14 = new Table_1_4_DataCode();
            $table14->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable151Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table151 = new Table_1_5_1_DataCode();
            $table151->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable151aData($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table151a = new Table_1_5_1_a_DataCode();
            $table151a->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable161Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table161 = new Table_1_6_1_DataCode();
            $table161->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable161aData($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table161a = new Table_1_6_1_a_DataCode();
            $table161a->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable162Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table162 = new Table_1_6_2_DataCode();
            $table162->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable171Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table171 = new Table_1_7_1_DataCode();
            $table171->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable172Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table172 = new Table_1_7_2_DataCode();
            $table172->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable173Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table173 = new Table_1_7_3_DataCode();
            $table173->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable191Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table191 = new Table_1_9_1_DataCode();
            $table191->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable192Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table192 = new Table_1_9_2_DataCode();
            $table192->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable193Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table193 = new Table_1_9_3_DataCode();
            $table193->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable221Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table221 = new Table_2_2_1_DataCode();
            $table221->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable222Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table222 = new Table_2_2_2_DataCode();
            $table222->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable231Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table231 = new Table_2_3_1_DataCode();
            $table231->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable232Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table232 = new Table_2_3_2_DataCode();
            $table232->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable241Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table241 = new Table_2_4_1_DataCode();
            $table241->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable242Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table242 = new Table_2_4_2_DataCode();
            $table242->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable242bData($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table242b = new Table_2_4_2_b_DataCode();
            $table242b->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable25Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table25 = new Table_2_5_DataCode();
            $table25->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable341Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table341 = new Table_3_4_1_DataCode();
            $table341->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable342Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table342 = new Table_3_4_2_DataCode();
            $table342->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillTable41Data($sheet, $pointUserDeportaments)
    {
        $this->manageMemory(function ($sheet, $chunk) {
            $table41 = new Table_4_1_DataCode();
            $table41->exportTableData($sheet, $chunk);
        }, $sheet, $pointUserDeportaments);
    }

    private function fillDefaultData($sheet, $pointUserDeportaments, $table)
    {
        $sheet->setCellValue('A1', 'Default data for ' . $table);
        $sheet->setCellValue('A2', 'Total records: ' . count($pointUserDeportaments));
    }

    private function logExcelData($path, $sheetName)
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getSheetByName($sheetName);
        $data = $sheet->toArray(null, true, true, true);
        // Log::info('Excel data after saving: ' . json_encode(array_slice($data, 7, 5)));
    }
}
