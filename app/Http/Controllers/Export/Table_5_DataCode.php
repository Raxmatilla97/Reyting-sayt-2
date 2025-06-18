<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Table_5_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 5; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Shriftlarini o'zgartirish
        $sheet->getStyle('A1:I1000')->getFont()->setName('Times New Roman');

        // Tartib raqam qo'shish
        $sheet->getStyle('A2')->getFont()->setBold(true);

        foreach ($pointUserDeportaments as $pointEntry) {
            $table_5_record = $pointEntry->table_5; // Tablitsiya relation

            if ($table_5_record) {
                try {
                    // Raqam qo'yish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    // Ism familyani Bosh harfini kotta qilish
                    $fullName = $pointEntry->employee->full_name ?? 'N/A';
                    $formattedName = ucwords(strtolower($fullName));
                    $sheet->setCellValue('C' . $row, $formattedName);

                    $sheet->setCellValue('D' . $row, $table_5_record->ixtisoslik_shifri_va_nomi ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_5_record->qollanma_mualliflar_soni ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_5_record->qollanma_nomi ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $table_5_record->guvohnoma_raqami_va_sanasi ?? 'N/A');
                    $sheet->setCellValue('H' . $row, $table_5_record->qollanma_reestr_raqami ?? 'N/A');

                    if ($table_5_record->asos_file) {
                        $sheet->setCellValue('I' . $row, 'Yuklash');
                        $sheet->getCell('I' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_5_record->asos_file));
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

        // Auto-size FALSE
        foreach(range('A','I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_5: $writtenRows");
    }
}