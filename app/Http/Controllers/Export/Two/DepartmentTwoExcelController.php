<?php

namespace App\Http\Controllers\Export\Two;


use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\Department;
use App\Models\StudentsCountForDepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentTwoExcelController extends Controller
{


    public function generateExcel(Request $request)
    {
        // SSE headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Nginx uchun

        set_time_limit(0);
        ini_set('memory_limit', '756M');

        try {
            $this->sendSSE('Boshlandi...', 0);

            $templatePath = storage_path('app/templates/template.xlsx');
            if (!file_exists($templatePath)) {
                throw new \Exception('Template file not found');
            }

            $this->sendSSE('Template yuklanmoqda...', 5);

            $reader = IOFactory::createReader('Xlsx');
            $reader->setIncludeCharts(true);
            $spreadsheet = $reader->load($templatePath);
            $templateSheet = $spreadsheet->getActiveSheet();

            $this->sendSSE('Kafedra ma\'lumotlari olinmoqda...', 10);

            $departments = Department::where('status', 1)->get();
            $totalDepartments = $departments->count();

            foreach ($departments as $index => $department) {
                $currentProgress = 10 + (($index + 1) / $totalDepartments * 70);

                $this->sendSSE(
                    sprintf("Qayta ishlanmoqda: %s (%d/%d)",
                        $department->name,
                        $index + 1,
                        $totalDepartments
                    ),
                    (int)$currentProgress
                );

                if ($index === 0) {
                    $sheet = $templateSheet;
                    $sheetName = $this->getUniqueSheetName($spreadsheet, $department, $index);
                    $sheet->setTitle($sheetName);
                } else {
                    $sheet = clone $templateSheet;
                    $sheetName = $this->getUniqueSheetName($spreadsheet, $department, $index);
                    $sheet->setTitle($sheetName);
                    $spreadsheet->addSheet($sheet);
                }

                // Sheet ma'lumotlarini to'ldirish...
                $this->processSheet($sheet, $department);
            }

            $this->sendSSE('Excel fayl saqlanmoqda...', 90);

            if ($spreadsheet->getSheetCount() > 1) {
                $spreadsheet->removeSheetByIndex(0);
            }

            $filename = 'departments_report_' . date('Y-m-d_H-i') . '.xlsx';
            $savePath = storage_path('app/public/' . $filename);

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->setIncludeCharts(true);
            $writer->save($savePath);

            $this->sendSSE('Fayl tayyor!', 100, [
                'success' => true,
                'file' => $filename,
                'download_url' => route('excel.download_two', ['filename' => $filename])
            ]);

            exit();

        } catch (\Exception $e) {
            Log::error('Excel generation error: ' . $e->getMessage());
            $this->sendSSE('Xatolik: ' . $e->getMessage(), 0, ['success' => false]);
            exit();
        }
    }

    private function sendSSE($message, $progress, $additionalData = [])
    {
        $data = array_merge([
            'message' => $message,
            'progress' => $progress
        ], $additionalData);

        echo "data: " . json_encode($data) . "\n\n";

        ob_flush();
        flush();

        // Logga yozish
        Log::info(sprintf("Progress: %d%% - %s", $progress, $message));
    }

    private function processSheet($sheet, $department)
    {
        try {
            // Kafedra nomi
            $sheet->setCellValue('A1', $department->name);

            // O'qituvchilar va talabalar soni
            $teacher_count = $department->employee()
                ->where('status', 1)
                ->count();

            $student_count = StudentsCountForDepart::where('departament_id', $department->id)
                ->where('status', 1)
                ->value('number') ?? 0;

            $sheet->setCellValue('D7', $teacher_count);
            $sheet->setCellValue('E7', $student_count);

            // O'qituvchilar ma'lumotlari
            $teachers = $department->employee()
                ->where('status', 1)
                ->with(['point_user_deportaments' => function ($query) {
                    $query->where('status', 1);
                }])
                ->get();

            $row = 10;
            foreach ($teachers as $teacherIndex => $teacher) {
                $sheet->setCellValue('A' . $row, $teacherIndex + 1);
                $sheet->setCellValue('B' . $row, $teacher->name);

                foreach ($this->directions as $relation => $info) {
                    $count = $teacher->point_user_deportaments()
                        ->whereHas($relation)
                        ->where('status', 1)
                        ->count();

                    $sheet->setCellValue($info['column'] . $row, $count);
                }

                $this->applyTeacherRowStyles($sheet, $row);
                $row += 2;
            }

            $this->applyTotalRowStyles($sheet);

        } catch (\Exception $e) {
            Log::error("Error processing sheet for department {$department->name}: " . $e->getMessage());
            throw $e;
        }
    }
    public function downloadExcel($filename = null)
    {
        try {
            Log::info('Download request for file: ' . $filename);

            if (!$filename) {
                throw new \Exception('Filename not provided');
            }

            $filePath = storage_path('app/public/' . $filename);

            if (!file_exists($filePath)) {
                Log::error('File not found at path: ' . $filePath);
                throw new \Exception('File not found: ' . $filename);
            }

            Log::info('Downloading file: ' . $filename);

            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);

        } catch (\Exception $e) {
            Log::error('Excel download error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function getProgress(Request $request)
    {
        $key = $request->input('key');
        $progress = session($key, ['progress' => 0, 'message' => 'Starting...']);

        return response()->json($progress);
    }

    private function toLatin($text)
    {
        $converter = [
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'yo',
            'ж' => 'j',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'x',
            'ц' => 'ts',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ъ' => '',
            'ы' => 'i',
            'ь' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'Yo',
            'Ж' => 'J',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'Y',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'X',
            'Ц' => 'Ts',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Sch',
            'Ъ' => '',
            'Ы' => 'I',
            'Ь' => '',
            'Э' => 'E',
            'Ю' => 'Yu',
            'Я' => 'Ya',
            'ў' => 'o',
            'Ў' => 'O',
            'қ' => 'q',
            'Қ' => 'Q',
            'ғ' => 'g',
            'Ғ' => 'G',
            'ҳ' => 'h',
            'Ҳ' => 'H'
        ];

        return strtr($text, $converter);
    }

    private function sanitizeSheetName($name)
    {
        // Lotin harflariga o'tkazish
        $name = $this->toLatin($name);

        // Bo'sh joylarni _ bilan almashtirish
        $name = preg_replace('/\s+/', '_', $name);

        // Maxsus belgilarni olib tashlash
        $name = preg_replace('/[^\w\d_]/u', '', $name);

        // Boshi va oxiridagi _ ni olib tashlash
        $name = trim($name, '_');

        // Ketma-ket kelgan _ larni bitta _ ga almashtirish
        $name = preg_replace('/_+/', '_', $name);

        return $name;
    }

    private function getUniqueSheetName($spreadsheet, $department, $index)
    {
        $baseName = $this->sanitizeSheetName($department->name);
        $prefix = sprintf("%02d", $index + 1);
        $maxBaseLength = 28 - strlen($prefix);

        if (mb_strlen($baseName) > $maxBaseLength) {
            $baseName = mb_substr($baseName, 0, $maxBaseLength);
        }

        $name = $prefix . '_' . $baseName;
        $counter = 1;

        while ($spreadsheet->sheetNameExists($name)) {
            $suffix = '_' . $counter;
            $newMaxLength = 31 - (strlen($prefix) + strlen($suffix) + 1);
            if (mb_strlen($baseName) > $newMaxLength) {
                $baseName = mb_substr($baseName, 0, $newMaxLength);
            }
            $name = $prefix . '_' . $baseName . $suffix;
            $counter++;
        }

        return $name;
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
}
