<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class Table_2_2_1_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 7; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Shrift nomini o'zgartirish
        $sheet->getStyle('A1:K1000')->getFont()->setName('Times New Roman');

        // Tartib raqam belgilash

        foreach ($pointUserDeportaments as $pointEntry) {
            $tatable_2_2_1_record = $pointEntry->table_2_2_1;

            if ($tatable_2_2_1_record) {
                try {
                    // Nomer berish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                     // Capitalize first letters, lowercase the rest
                     $fullName = $pointEntry->employee->full_name ?? 'N/A';
                     $formattedName = ucwords(strtolower($fullName));
                     $sheet->setCellValue('C' . $row, $formattedName);

                    $sheet->setCellValue('D' . $row, $tatable_2_2_1_record->ixtisoslik_shifri ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $tatable_2_2_1_record->darslik_nomi ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $tatable_2_2_1_record->darslik_mualliflar_soni ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $tatable_2_2_1_record->darslik_guvohnomasi ?? 'N/A');
                    $sheet->setCellValue('H' . $row, $tatable_2_2_1_record->darslik_reestr_raqami ?? 'N/A');

                    if ($tatable_2_2_1_record->asos_file) {
                        $sheet->setCellValue('I' . $row, 'Yuklash');
                        $sheet->getCell('I' . $row)->getHyperlink()->setUrl(asset('storage/' . $tatable_2_2_1_record->asos_file));
                    } else {
                        $sheet->setCellValue('I' . $row, 'N/A');
                    }

                    // Textlarga still berish va border
                    $cellRange = 'A' . $row . ':I' . $row;
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
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_1_7_1: $writtenRows");
    }
}
