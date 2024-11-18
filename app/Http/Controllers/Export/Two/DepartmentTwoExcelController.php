<?php

namespace App\Http\Controllers\Export\Two;

use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\Department;
use App\Models\StudentsCountForDepart;
use Illuminate\Http\Request;

class DepartmentTwoExcelController extends Controller
{

    /**
     * Sheet nomi uchun ruxsat etilgan maksimal uzunlik
     */
    private const MAX_SHEET_NAME_LENGTH = 31;

    /**
     * Sheet nomi uchun taqiqlangan belgilar
     */
    private const INVALID_CHARS = ['*', '/', '\\', '?', ':', '[', ']'];

    /**
     * Sheet nomini tozalash va validatsiya qilish
     */
    private function sanitizeSheetName($name)
    {
        // Taqiqlangan belgilarni olib tashlash
        $name = str_replace(self::INVALID_CHARS, ' ', $name);

        // Bo'sh joylarni bitta bo'sh joy bilan almashtirish
        $name = preg_replace('/\s+/', ' ', $name);

        // Boshi va oxiridagi bo'sh joylarni olib tashlash
        $name = trim($name);

        // Uzunlikni cheklash
        if (mb_strlen($name) > self::MAX_SHEET_NAME_LENGTH - 3) { // 3 belgini raqam uchun qoldiramiz
            $name = mb_substr($name, 0, self::MAX_SHEET_NAME_LENGTH - 3);
        }

        return $name;
    }

    /**
     * Unique sheet nomi yaratish
     */
    private function createUniqueSheetName($spreadsheet, $baseName)
    {
        $baseName = $this->sanitizeSheetName($baseName);
        $name = $baseName;
        $counter = 1;

        while ($spreadsheet->sheetNameExists($name)) {
            $suffix = " {$counter}";
            $nameLength = mb_strlen($baseName);
            $suffixLength = mb_strlen($suffix);

            // Agar nom + suffix uzunligi limitdan oshsa, nomni qisqartiramiz
            if ($nameLength + $suffixLength > self::MAX_SHEET_NAME_LENGTH) {
                $baseName = mb_substr($baseName, 0, self::MAX_SHEET_NAME_LENGTH - $suffixLength);
            }

            $name = $baseName . $suffix;
            $counter++;
        }

        return $name;
    }

    // Yo'nalishlar va maksimal ballar
    protected $directions = [
        'table_1_1' => ['max_point' => 3, 'column' => 'F', 'student_divisor' => false],
        'table_1_2' => ['max_point' => 2, 'column' => 'G', 'student_divisor' => false],
        'table_1_3_1_a' => ['max_point' => 2, 'column' => 'H', 'student_divisor' => false],
        'table_1_3_1_b' => ['max_point' => 2, 'column' => 'I', 'student_divisor' => false],
        'table_1_3_2_a' => ['max_point' => 2, 'column' => 'J', 'student_divisor' => false],
        'table_1_3_2_b' => ['max_point' => 2, 'column' => 'K', 'student_divisor' => false],
        'table_1_4' => ['max_point' => 6, 'column' => 'L', 'student_divisor' => false],
        'table_1_5_1' => ['max_point' => 7, 'column' => 'M', 'student_divisor' => false],
        'table_1_5_1_a' => ['max_point' => 6, 'column' => 'N', 'student_divisor' => false],
        'table_1_6_1' => ['max_point' => 10, 'column' => 'O', 'student_divisor' => false],
        'table_1_6_1_a' => ['max_point' => 3, 'column' => 'P', 'student_divisor' => false],
        'table_1_6_2' => ['max_point' => 70, 'column' => 'Q', 'student_divisor' => false],
        'table_1_7_1' => ['max_point' => 2, 'column' => 'R', 'student_divisor' => false],
        'table_1_7_2' => ['max_point' => 2, 'column' => 'S', 'student_divisor' => false],
        'table_1_7_3' => ['max_point' => 1, 'column' => 'T', 'student_divisor' => false],
        'table_1_9_1' => ['max_point' => 1, 'column' => 'U', 'student_divisor' => false],
        'table_1_9_2' => ['max_point' => 2, 'column' => 'V', 'student_divisor' => false],
        'table_1_9_3' => ['max_point' => 3, 'column' => 'W', 'student_divisor' => false],
        'table_2_2_1' => ['max_point' => 3, 'column' => 'X', 'student_divisor' => false],
        'table_2_2_2' => ['max_point' => 2, 'column' => 'Y', 'student_divisor' => false],
        'table_2_3_1' => ['max_point' => 10, 'column' => 'Z', 'student_divisor' => false],
        'table_2_3_2' => ['max_point' => 1, 'column' => 'AA', 'student_divisor' => true],
        'table_2_4_1' => ['max_point' => 10, 'column' => 'AB', 'student_divisor' => true],
        'table_2_4_2' => ['max_point' => 80, 'column' => 'AC', 'student_divisor' => false],
        'table_2_4_2_b' => ['max_point' => 7, 'column' => 'AD', 'student_divisor' => true],
        'table_2_5' => ['max_point' => 3, 'column' => 'AE', 'student_divisor' => false],
        'table_3_4_1' => ['max_point' => 3, 'column' => 'AF', 'student_divisor' => true],
        'table_3_4_2' => ['max_point' => 3, 'column' => 'AG', 'student_divisor' => true],
        'table_4_1' => ['max_point' => 4, 'column' => 'AH', 'student_divisor' => true]
    ];

    public function generateExcel(Request $request)
    {
        try {
            $progressKey = 'excel_progress_' . time();
            session([$progressKey => 0]);

            $reader = IOFactory::createReader('Xlsx');
            $template = storage_path('app/templates/template.xlsx');
            $spreadsheet = $reader->load($template);

            // Template sheetini o'chirib, yangi bo'sh sheet yaratamiz
            $spreadsheet->removeSheetByIndex(0);
            $spreadsheet->createSheet();

            $departments = Department::where('status', 1)->get();
            $totalDepartments = $departments->count();

            foreach ($departments as $index => $department) {
                // Sheet yaratish
                if ($index > 0) {
                    $spreadsheet->createSheet();
                }

                $sheet = $spreadsheet->getSheet($index);

                // Sheet nomini yaratish
                $sheetName = $this->createUniqueSheetName($spreadsheet, $department->name);
                $sheet->setTitle($sheetName);

                // Kafedraning asosiy ma'lumotlari
                $teacher_count = $department->employee()
                    ->where('status', 1)
                    ->count();

                $student_count = StudentsCountForDepart::where('departament_id', $department->id)
                    ->where('status', 1)
                    ->value('number') ?? 0;

                // Asosiy ma'lumotlarni yozish
                $sheet->setCellValue('D7', $teacher_count);
                $sheet->setCellValue('E7', $student_count);

                // Yo'nalishlar bo'yicha hisoblar
                $totalRating = 0;
                foreach ($this->directions as $relation => $info) {
                    $count = $department->point_user_deportaments()
                        ->whereHas($relation)
                        ->where('status', 1)
                        ->count();

                    // Jamini yozish
                    $sheet->setCellValue($info['column'] . '7', $count);

                    // Reyting hisobi
                    if ($info['student_divisor']) {
                        // Talaba soniga bo'linadigan yo'nalishlar
                        if ($student_count > 0) {
                            $value = ($count * $info['max_point']) / $student_count;
                        } else {
                            $value = 0;
                        }
                    } else {
                        // O'qituvchi soniga bo'linadigan yo'nalishlar
                        if ($teacher_count > 0) {
                            $value = ($count * $info['max_point']) / $teacher_count;
                        } else {
                            $value = 0;
                        }
                    }

                    $value = round($value, 2);
                    $sheet->setCellValue($info['column'] . '8', $value);
                    $totalRating += $value;
                }

                // Jami reyting balini AL8 ga yozish
                $sheet->setCellValue('AL8', round($totalRating, 2));
                // O'qituvchilar ro'yxati va ularning ma'lumotlari
                $row = 10;
                $teachers = $department->employee()
                    ->where('status', 1)
                    ->with(['point_user_deportaments' => function ($query) {
                        $query->where('status', 1);
                    }])
                    ->get();

                foreach ($teachers as $index => $teacher) {
                    // O'qituvchi ma'lumotlari qatori
                    $sheet->setCellValue('A' . $row, $index + 1);
                    $sheet->setCellValue('B' . $row, $teacher->name);

                    // Yo'nalishlar bo'yicha ma'lumotlar
                    foreach ($this->directions as $relation => $info) {
                        $count = $teacher->point_user_deportaments()
                            ->whereHas($relation)
                            ->where('status', 1)
                            ->count();

                        $sheet->setCellValue($info['column'] . $row, $count);
                    }

                    // Reyting qatori
                    $teacherTotalRating = 0;
                    foreach ($this->directions as $relation => $info) {
                        $count = $teacher->point_user_deportaments()
                            ->whereHas($relation)
                            ->where('status', 1)
                            ->count();

                        if ($info['student_divisor']) {
                            $value = $student_count > 0 ? ($count * $info['max_point']) / $student_count : 0;
                        } else {
                            $value = $teacher_count > 0 ? ($count * $info['max_point']) / $teacher_count : 0;
                        }

                        $value = round($value, 2);
                        $sheet->setCellValue($info['column'] . ($row + 1), $value);
                        $teacherTotalRating += $value;
                    }

                    // O'qituvchining jami reytingini yozish
                    $sheet->setCellValue('AL' . ($row + 1), round($teacherTotalRating, 2));

                    // Stillar
                    $this->applyTeacherRowStyles($sheet, $row);

                    // Keyingi o'qituvchiga o'tish
                    $row += 2;
                }

                // Jami va reyting qatorlari uchun stillar
                $this->applyTotalRowStyles($sheet);

               // Progress
               $progress = (($index + 1) / $totalDepartments) * 100;
               session([$progressKey => round($progress)]);
            }

            // // Birinchi sheet ni o'chirish
            // $spreadsheet->removeSheetByIndex(0);

           // Faylni saqlash
           $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
           $filename = 'departments_report_' . date('Y-m-d_H-i') . '.xlsx';
           $writer->save(storage_path('app/public/' . $filename));

           return response()->json([
            'success' => true,
            'file' => $filename,
            'progress' => 100
        ]);
        } catch (\Exception $e) {
            \Log::error('Excel generation error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

            return response()->json([
                'success' => false,
                'message' => 'Excel faylini yaratishda xatolik: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function applyTeacherRowStyles($sheet, $row)
    {
        // O'qituvchi qatori
        $sheet->getStyle('A' . $row . ':AL' . $row)->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '00FFFF']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Reyting qatori
        $sheet->getStyle('A' . ($row + 1) . ':AL' . ($row + 1))->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFFF00']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
    }

    protected function applyTotalRowStyles($sheet)
    {
        // Jami qatori
        $sheet->getStyle('A7:AL7')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '00FFFF']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Reyting qatori
        $sheet->getStyle('A8:AL8')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFD700']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Jami reyting bali uchun alohida stil
        $sheet->getStyle('AL8')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFD700']
            ]
        ]);
    }

    public function getProgress(Request $request)
    {
        return response()->json([
            'progress' => session('excel_progress_' . $request->key, 0)
        ]);
    }
}
