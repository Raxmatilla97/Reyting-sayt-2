<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Table_12_DataCode
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
            $table_12_record = $pointEntry->table_12; // Tablitsiya relation to'g'ri formatda

            if ($table_12_record) {
                try {
                    // Raqam qo'yish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    // Ism familyani Bosh harfini kotta qilish
                    $fullName = $pointEntry->employee->full_name ?? 'N/A';
                    $formattedName = ucwords(strtolower($fullName));
                    $sheet->setCellValue('C' . $row, $formattedName);

                    $sheet->setCellValue('D' . $row, $table_12_record->davlat_nomi ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_12_record->ilmiy_jurnal_nomi ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_12_record->ilmiy_maqola_nomi ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $table_12_record->yili_betlari ?? 'N/A');
                    $sheet->setCellValue('H' . $row, $table_12_record->url_link ?? 'N/A');
                    $sheet->setCellValue('I' . $row, $table_12_record->mualliflar_soni ?? 'N/A');
                    $sheet->setCellValue('J' . $row, $table_12_record->assos_url ?? 'N/A');

                    if ($table_12_record->asos_file) {
                        $sheet->setCellValue('K' . $row, 'Yuklash');
                        $sheet->getCell('K' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_12_record->asos_file));
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

        // Log::info("Total rows written to table_12: $writtenRows");
    }
}