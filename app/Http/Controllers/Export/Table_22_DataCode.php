<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Table_22_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 7; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Shriftlarini o'zgartirish
        $sheet->getStyle('A1:J1000')->getFont()->setName('Times New Roman');

        // Tartib raqam qo'shish
        $sheet->getStyle('A2')->getFont()->setBold(true);

        foreach ($pointUserDeportaments as $pointEntry) {
            $table_22_record = $pointEntry->table_22; // Tablitsiya relation to'g'ri formatda

            if ($table_22_record) {
                try {
                    // Raqam qo'yish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    $sheet->setCellValue('C' . $row, $table_22_record->xujjat_nomi_sana ?? 'N/A');
                    $sheet->setCellValue('D' . $row, $table_22_record->talaba_fish ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_22_record->otm_nomi ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_22_record->musaxasislik ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $table_22_record->cspu_talaba_fish ?? 'N/A');
                    $sheet->setCellValue('H' . $row, $table_22_record->cspu_musaxasislik ?? 'N/A');

                    if ($table_22_record->asos_file) {
                        $sheet->setCellValue('I' . $row, 'Yuklash');
                        $sheet->getCell('I' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_22_record->asos_file));
                    } else {
                        $sheet->setCellValue('I' . $row, 'N/A');
                    }

                    // Column stillari
                    $cellRange = 'A'.$row.':I'.$row;
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

        // Auto-size FALSE - eng oxirgi ustun I
        foreach(range('A','I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_22: $writtenRows");
    }
}