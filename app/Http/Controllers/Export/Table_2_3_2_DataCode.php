<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class Table_2_3_2_DataCode
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
            $table_2_3_2_record = $pointEntry->table_2_3_2;

            if ($table_2_3_2_record) {
                try {
                    // Nomer berish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    $sheet->setCellValue('C' . $row, $table_2_3_2_record->xorijiy_talaba_ismi ?? 'N/A');
                    $sheet->setCellValue('D' . $row, $table_2_3_2_record->davlati ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_2_3_2_record->talim_yonalishi ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_2_3_2_record->magister_shifri_nomi ?? 'N/A');

                    if ($table_2_3_2_record->asos_file) {
                        $sheet->setCellValue('G' . $row, 'Yuklash');
                        $sheet->getCell('G' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_2_3_2_record->asos_file));
                    } else {
                        $sheet->setCellValue('G' . $row, 'N/A');
                    }

                    // Textlarga still berish va border
                    $cellRange = 'A' . $row . ':G' . $row;
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
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_1_7_1: $writtenRows");
    }
}
