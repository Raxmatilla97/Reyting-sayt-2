<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class Table_2_4_1_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 8; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Shrift nomini o'zgartirish
        $sheet->getStyle('A1:K1000')->getFont()->setName('Times New Roman');

        // Tartib raqam belgilash

        foreach ($pointUserDeportaments as $pointEntry) {
            $table_2_4_1_record = $pointEntry->table_2_4_1;

            if ($table_2_4_1_record) {
                try {
                    // Nomer berish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    $sheet->setCellValue('C' . $row, $table_2_4_1_record->hujjat_nomi_sanasi ?? 'N/A');
                    $sheet->setCellValue('D' . $row, $table_2_4_1_record->otm_talaba_fish ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_2_4_1_record->davlat_otm_nomi ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_2_4_1_record->mutaxasislik_nomi ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $table_2_4_1_record->xorijiy_talaba_fish ?? 'N/A');
                    $sheet->setCellValue('H' . $row, $table_2_4_1_record->davlat_otm_nomi2 ?? 'N/A');
                    $sheet->setCellValue('I' . $row, $table_2_4_1_record->mutaxasislik_nomi2 ?? 'N/A');


                    if ($table_2_4_1_record->asos_file) {
                        $sheet->setCellValue('J' . $row, 'Yuklash');
                        $sheet->getCell('J' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_2_4_1_record->asos_file));
                    } else {
                        $sheet->setCellValue('J' . $row, 'N/A');
                    }

                    // Textlarga still berish va border
                    $cellRange = 'A' . $row . ':J' . $row;
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
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_1_7_1: $writtenRows");
    }
}