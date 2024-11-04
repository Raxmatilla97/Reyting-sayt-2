<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\StudentsCountForDepart;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class StudentsCountForDepartController extends Controller
{
    public function export()
{
    try {
        return Excel::download(new class implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
        {
            public function collection()
            {
                return Department::where('status', 1)->get();
            }

            public function headings(): array
            {
                return [
                    'id',                  // Kichik harflarda
                    'kafedra_nomi',        // Snake case
                    'talabalar_soni'       // Snake case
                ];
            }

            public function map($department): array
            {
                $studentCount = StudentsCountForDepart::where('departament_id', $department->id)
                    ->first();

                return [
                    $department->id,
                    $department->name,
                    $studentCount ? $studentCount->number : 0
                ];
            }

            public function columnWidths(): array
            {
                return [
                    'A' => 10,
                    'B' => 70,
                    'C' => 20,
                ];
            }

            public function styles($sheet)
            {
                return [
                    1 => [
                        'font' => [
                            'bold' => true,
                            'size' => 12
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'E2E8F0'
                            ]
                        ]
                    ],
                    'A1:C'.$sheet->getHighestRow() => [
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ]
                        ]
                    ],
                    'A2:A'.$sheet->getHighestRow() => [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                        ]
                    ],
                    'C2:C'.$sheet->getHighestRow() => [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                        ]
                    ]
                ];
            }
        }, 'talabalar-soni.xlsx');

    } catch (Exception $e) {
        Log::error('Export error: ' . $e->getMessage());
        return back()->with('error', 'Export qilishda xatolik yuz berdi: ' . $e->getMessage());
    }
}

public function import(Request $request)
{
    try {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new class implements ToModel, WithHeadingRow, WithValidation
        {
            public function model(array $row)
            {
                Log::info('Import row data:', $row); // Debug uchun

                return StudentsCountForDepart::updateOrCreate(
                    ['departament_id' => $row['id']],
                    [
                        'number' => $row['talabalar_soni'],
                        'status' => 1,
                        'updated_at' => now()
                    ]
                );
            }

            public function rules(): array
            {
                return [
                    '*.id' => 'required|exists:departments,id',
                    '*.talabalar_soni' => 'required|integer|min:0'
                ];
            }

            public function customValidationMessages()
            {
                return [
                    '*.id.required' => 'ID maydoni to\'ldirilishi shart',
                    '*.id.exists' => 'Bunday ID li kafedra mavjud emas',
                    '*.talabalar_soni.required' => 'Talabalar soni maydoni to\'ldirilishi shart',
                    '*.talabalar_soni.integer' => 'Talabalar soni butun son bo\'lishi kerak',
                    '*.talabalar_soni.min' => 'Talabalar soni 0 dan kichik bo\'lishi mumkin emas'
                ];
            }
        }, $request->file('file'));

        return response()->json([
            'success' => true,
            'message' => 'Ma\'lumotlar muvaffaqiyatli yangilandi'
        ]);

    } catch (Exception $e) {
        Log::error('Import error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Import qilishda xatolik: ' . $e->getMessage()
        ], 422);
    }
}
}
