<?php

namespace App\Http\Controllers\Export;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Table_14_3_DataCode
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
            $table_14_3_record = $pointEntry->table_14_3; // Tablitsiya relation to'g'ri formatda

            if ($table_14_3_record) {
                try {
                    // Raqam qo'yish
                    $sheet->setCellValue('A' . $row, $orderNumber);

                    $sheet->setCellValue('B' . $row, $pointEntry->department->name ?? 'N/A');

                    // Ism familyani Bosh harfini kotta qilish
                    $fullName = $pointEntry->employee->full_name ?? 'N/A';
                    $formattedName = ucwords(strtolower($fullName));
                    $sheet->setCellValue('C' . $row, $formattedName);

                    $sheet->setCellValue('D' . $row, $table_14_3_record->hujjat_nomi_sanasi ?? 'N/A');
                    $sheet->setCellValue('E' . $row, $table_14_3_record->otm_talaba_fish ?? 'N/A');
                    $sheet->setCellValue('F' . $row, $table_14_3_record->davlat_otm_nomi ?? 'N/A');
                    $sheet->setCellValue('G' . $row, $table_14_3_record->mutaxasislik_nomi ?? 'N/A');
                    $sheet->setCellValue('H' . $row, $table_14_3_record->tezis_nomi ?? 'N/A');
                    $sheet->setCellValue('I' . $row, $table_14_3_record->konf_nomi ?? 'N/A');

                    if ($table_14_3_record->asos_file) {
                        $sheet->setCellValue('J' . $row, 'Yuklash');
                        $sheet->getCell('J' . $row)->getHyperlink()->setUrl(asset('storage/' . $table_14_3_record->asos_file));
                    } else {
                        $sheet->setCellValue('J' . $row, 'N/A');
                    }

                    // Column stillari
                    $cellRange = 'A'.$row.':J'.$row;
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

        // Auto-size FALSE - eng oxirgi ustun J
        foreach(range('A','J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(false);
        }

        // Log::info("Total rows written to table_14_3: $writtenRows");
    }
}