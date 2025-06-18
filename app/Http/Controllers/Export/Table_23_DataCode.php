<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Table_23_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 5; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Shriftlarini o'zgartirish
        $sheet->getStyle('A1:H1000')->getFont()->setName('Times New Roman');

        // Tartib raqam qo'shish
        $sheet->getStyle('A2')->getFont()->setBold(true);

        foreach ($pointUserDeportaments as $pointEntry) {
            $table_23_record = $pointEntry->table_23; // Tablitsiya relation to'g'ri formatda

            if ($table_23_record) {
                try {
                    // Raqam qo'yish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    $sheet->setCellValue('C' . $row, $table_23_record->xorijiy_oqituvchi_fish ?? 'N/A');
                    $sheet->setCellValue('D' . $row, $table_23_record->davlat_asosy_ish ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_23_record->mutaxasislik ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_23_record->dars_bebradigan_fan ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $table_23_record->scopus_id ?? 'N/A');

                    if ($table_23_record->asos_file) {
                        $sheet->setCellValue('H' . $row, 'Yuklash');
                        $sheet->getCell('H' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_23_record->asos_file));
                    } else {
                        $sheet->setCellValue('H' . $row, 'N/A');
                    }

                    // Column stillari
                    $cellRange = 'A'.$row.':H'.$row;
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
                        // Log::info("Written $writtenRows rows to Excel");
                    }
                } catch (\Exception $e) {
                    // Log::error("Error writing row $row: " . $e->getMessage());
                }
            }
        }

        // Auto-size FALSE - eng oxirgi ustun H
        foreach(range('A','H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_23: $writtenRows");
    }
}