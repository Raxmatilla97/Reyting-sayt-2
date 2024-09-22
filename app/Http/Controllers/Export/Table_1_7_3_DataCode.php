<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;

class Table_1_7_3_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 9; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Shrift nomini o'zgartirish
        $sheet->getStyle('A1:K1000')->getFont()->setName('Times New Roman');

        // Tartib raqam belgilash
        $sheet->setCellValue('A7', 'T/r');
        $sheet->getStyle('A7')->getFont()->setBold(true);

        foreach ($pointUserDeportaments as $pointEntry) {
            $table_1_7_3_record = $pointEntry->table_1_7_3;

            if ($table_1_7_3_record) {
                try {
                    // Nomer berish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    $sheet->setCellValue('C' . $row, $table_1_7_3_record->davlat_grant_mavzusi ?? 'N/A');
                    $sheet->setCellValue('D' . $row, $table_1_7_3_record->davlat_grant_summasi ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_1_7_3_record->jami_summa ?? 'N/A');

                    if ($table_1_7_3_record->asos_file) {
                        $sheet->setCellValue('F' . $row, 'Yuklash');
                        $sheet->getCell('F' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_1_7_3_record->asos_file));
                    } else {
                        $sheet->setCellValue('F' . $row, 'N/A');
                    }

                    // Textlarga still berish va border
                    $cellRange = 'A'.$row.':F'.$row;
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
        foreach(range('A','F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_1_7_1: $writtenRows");
    }
}
