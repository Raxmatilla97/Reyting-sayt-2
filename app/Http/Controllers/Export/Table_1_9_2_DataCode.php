<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;

class Table_1_9_2_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 6; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Shrift nomini o'zgartirish
        $sheet->getStyle('A1:K1000')->getFont()->setName('Times New Roman');

        // Tartib raqam belgilash

        foreach ($pointUserDeportaments as $pointEntry) {
            $table_1_9_2_record = $pointEntry->table_1_9_2;

            if ($table_1_9_2_record) {
                try {
                    // Nomer berish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    $sheet->setCellValue('C' . $row, $table_1_9_2_record->otmlar_nomi ?? 'N/A');
                    $sheet->setCellValue('D' . $row, $table_1_9_2_record->asosiy_statdagi_professorlar ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_1_9_2_record->ixtiro_model_uchun_patent ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_1_9_2_record->berilgan_sanasi ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $table_1_9_2_record->qayd_raqami ?? 'N/A');


                    if ($table_1_9_2_record->asos_file) {
                        $sheet->setCellValue('H' . $row, 'Yuklash');
                        $sheet->getCell('H' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_1_9_2_record->asos_file));
                    } else {
                        $sheet->setCellValue('H' . $row, 'N/A');
                    }

                    // Textlarga still berish va border
                    $cellRange = 'A' . $row . ':H' . $row;
                    $sheet->getStyle($cellRange)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);

                    $writtenRows++;
                    $row++;
                    $orderNumber++; // Tartib raqamini oshirish

                    if ($writtenRows % 10 == 0) {
                        Log::info("Written $writtenRows rows to Excel");
                    }
                } catch (\Exception $e) {
                    // Log::error("Error writing row $row: " . $e->getMessage());
                }
            }
        }

        // Auto-size columns
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_1_7_1: $writtenRows");
    }
}