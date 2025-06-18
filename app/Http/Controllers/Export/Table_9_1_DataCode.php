<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Table_9_1_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 5; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Shriftlarini o'zgartirish
        $sheet->getStyle('A1:K1000')->getFont()->setName('Times New Roman');

        // Tartib raqam qo'shish
        $sheet->getStyle('A2')->getFont()->setBold(true);

        foreach ($pointUserDeportaments as $pointEntry) {
            $table_9_1_record = $pointEntry->table_9_1; // Tablitsiya relation to'g'ri formatda

            if ($table_9_1_record) {
                try {
                    // Raqam qo'yish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    // Ism familyani Bosh harfini kotta qilish
                    $fullName = $pointEntry->employee->full_name ?? 'N/A';
                    $formattedName = ucwords(strtolower($fullName));
                    $sheet->setCellValue('C' . $row, $formattedName);

                    $sheet->setCellValue('D' . $row, $table_9_1_record->shifr_nomi ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_9_1_record->mualliflar ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_9_1_record->mualliflar_soni ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $table_9_1_record->monografiya_nomi ?? 'N/A');
                    $sheet->setCellValue('H' . $row, $table_9_1_record->kengash_bayoni_sana ?? 'N/A');
                    $sheet->setCellValue('I' . $row, $table_9_1_record->Nashiryot_nomi ?? 'N/A');
                    $sheet->setCellValue('J' . $row, $table_9_1_record->doi_raqami ?? 'N/A');
                    $sheet->setCellValue('K' . $row, $table_9_1_record->scopus_link_url ?? 'N/A');

                    if ($table_9_1_record->asos_file) {
                        $sheet->setCellValue('L' . $row, 'Yuklash');
                        $sheet->getCell('L' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_9_1_record->asos_file));
                    } else {
                        $sheet->setCellValue('L' . $row, 'N/A');
                    }

                    // Column stillari
                    $cellRange = 'A'.$row.':L'.$row;
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

        // Auto-size FALSE - eng oxirgi ustun L
        foreach(range('A','L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_9_1: $writtenRows");
    }
}