<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Table_9_2_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 7; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Shriftlarini o'zgartirish
        $sheet->getStyle('A1:K1000')->getFont()->setName('Times New Roman');

        // Tartib raqam qo'shish
        $sheet->getStyle('A2')->getFont()->setBold(true);

        foreach ($pointUserDeportaments as $pointEntry) {
            $table_9_2_record = $pointEntry->table_9_2; // Tablitsiya relation to'g'ri formatda

            if ($table_9_2_record) {
                try {
                    // Raqam qo'yish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    // Ism familyani Bosh harfini kotta qilish
                    $fullName = $pointEntry->employee->full_name ?? 'N/A';
                    $formattedName = ucwords(strtolower($fullName));
                    $sheet->setCellValue('C' . $row, $formattedName);

                    $sheet->setCellValue('D' . $row, $table_9_2_record->shifri_nomi ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_9_2_record->muallif_fish ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_9_2_record->mualliflar_soni ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $table_9_2_record->monografiya_nomi ?? 'N/A');
                    $sheet->setCellValue('H' . $row, $table_9_2_record->kengash_bayoni_sana ?? 'N/A');
                    $sheet->setCellValue('I' . $row, $table_9_2_record->nashiriyot_nomi ?? 'N/A');
                    $sheet->setCellValue('J' . $row, $table_9_2_record->isn_raqam ?? 'N/A');

                    if ($table_9_2_record->asos_file) {
                        $sheet->setCellValue('K' . $row, 'Yuklash');
                        $sheet->getCell('K' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_9_2_record->asos_file));
                    } else {
                        $sheet->setCellValue('K' . $row, 'N/A');
                    }

                    // Column stillari
                    $cellRange = 'A'.$row.':K'.$row;
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

        // Auto-size FALSE - eng oxirgi ustun K
        foreach(range('A','K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_9_2: $writtenRows");
    }
}