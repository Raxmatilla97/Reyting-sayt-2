<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;

class Table_1_5_1_DataCode
{
    public function exportTableData($sheet, $pointUserDeportaments)
    {
        $row = 8; // Ma'lumotlar yozilishi kerak bo'lgan boshlang'ich qator
        $writtenRows = 0;
        $orderNumber = 1; // Tartib raqami uchun o'zgaruvchi

        // Apply Times New Roman font to the whole sheet
        $sheet->getStyle('A1:K1000')->getFont()->setName('Times New Roman');

        // Add header for T/r
        $sheet->setCellValue('A7', 'T/r');
        $sheet->getStyle('A7')->getFont()->setBold(true);

        foreach ($pointUserDeportaments as $pointEntry) {
            $table_1_5_1_record = $pointEntry->table_1_5_1;

            if ($table_1_5_1_record) {
                try {
                    // Add order number
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    // Capitalize first letters, lowercase the rest
                    $fullName = $pointEntry->employee->full_name ?? 'N/A';
                    $formattedName = ucwords(strtolower($fullName));
                    $sheet->setCellValue('C' . $row, $formattedName);

                    $sheet->setCellValue('D' . $row, $table_1_5_1_record->jurnalning_nomi ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_1_5_1_record->jurnal_nashr_yili_oyi ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_1_5_1_record->maqolaning_nomi ?? 'N/A');
                    $sheet->setCellValue('H' . $row, $table_1_5_1_record->maqola_tili ?? 'N/A');

                    // Set URL as hyperlink
                    $url = $table_1_5_1_record->google_schoolar_url ?? 'N/A';
                    if ($url !== 'N/A') {
                        $sheet->setCellValue('I' . $row, $url);
                        $sheet->getCell('I' . $row)->getHyperlink()->setUrl($url);
                        $sheet->getStyle('I' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE));
                        $sheet->getStyle('I' . $row)->getFont()->setUnderline(true);
                    } else {
                        $sheet->setCellValue('I' . $row, 'N/A');
                    }

                    $sheet->setCellValue('J' . $row, $table_1_5_1_record->google_schoolar_iqtiboslar ?? 'N/A');

                    if ($table_1_5_1_record->asos_file) {
                        $sheet->setCellValue('K' . $row, 'Yuklash');
                        $sheet->getCell('K' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_1_5_1_record->asos_file));
                    } else {
                        $sheet->setCellValue('K' . $row, 'N/A');
                    }

                    // Add borders, center-align text, and wrap text for the cells
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
                        Log::info("Written $writtenRows rows to Excel");
                    }
                } catch (\Exception $e) {
                    // Log::error("Error writing row $row: " . $e->getMessage());
                }
            }
        }

        // Auto-size columns
        foreach(range('A','K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_1_5_1: $writtenRows");
    }
}
