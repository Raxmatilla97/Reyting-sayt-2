<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FormsController extends Controller
{
    public function employeeShowForm($tableName, Request $request)
    {
        // Formani shakillantirdigan ma'lumotlarni config papkasi ichidan employee_form_fields.php olib beradi
        $fields = config("employee_form_fields.{$tableName}");
        $section = 'employee'; // yoki 'department'
        $title = config("dep_emp_tables.{$section}.{$tableName}");

        $oldData = null;
        if ($request->has('edit')) {
            $itemId = $request->get('edit');

            // Pointer jadvalidan ma'lumotni olish
            $pointer = PointUserDeportament::find($itemId);

            if ($pointer) {
                // Asosiy tablitsadan ma'lumotlarni olish
                $foreignKeyField = "{$tableName}id"; // masalan: table_1_1_id
                $oldData = DB::table($tableName) // jadval nomidan ortiqcha _ ni olib tashlash
                    ->find($pointer->{$foreignKeyField});

                // Year ma'lumotini pointer jadvalidan olish
                if ($oldData) {
                    $oldData->year = $pointer->year;
                }
            }
        }


        if (!$fields) {
            abort(404, 'Jadval topilmadi.');
        }

        return view('dashboard.form_themplates.employee_form', compact('fields', 'tableName', 'title', 'oldData'));
    }

    public function departmentShowForm($tableName, Request $request)
    {
        // Formani shakillantirdigan ma'lumotlarni config papkasi ichidan employee_form_fields.php olib beradi
        $fields = config("department_forms_fields.{$tableName}");
        $section = 'department'; // yoki 'employee'
        $title = config("dep_emp_tables.{$section}.{$tableName}");

        $oldData = null;
        if ($request->has('edit')) {
            $itemId = $request->get('edit');

            // Pointer jadvalidan ma'lumotni olish
            $pointer = PointUserDeportament::find($itemId);

            if ($pointer) {
                // Asosiy tablitsadan ma'lumotlarni olish
                $foreignKeyField = "{$tableName}id"; // masalan: table_1_7_1_id
                $oldData = DB::table($tableName) // jadval nomidan ortiqcha _ ni olib tashlash
                    ->find($pointer->{$foreignKeyField});

                // Year ma'lumotini pointer jadvalidan olish
                if ($oldData) {
                    $oldData->year = $pointer->year;
                }
            }
        }

        if (!$fields) {
            abort(404, 'Jadval topilmadi.');
        }

        return view('dashboard.form_themplates.department_form', compact('fields', 'tableName', 'title', 'oldData'));
    }

    /**
     * Ma'lumotlarni saqlash yoki tahrirlash
     */
    /**
     * Ma'lumotlarni saqlash yoki tahrirlash
     */
    public function employeeStoreForm(Request $request, $tableName)
    {
        // Foydalanuvchini autentifikatsiyadan o'tganligini tekshirish
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', "Foydalanuvchi tizimga kirmagan.");
        }

        // Config faylidan ruxsat etilgan maydonlarni olish
        $formFields = config('employee_form_fields');

        // Tablitsa mavjudligini tekshirish
        if (!isset($formFields[$tableName])) {
            return redirect()->back()->with('error', "Noto'g'ri tablitsa nomi ko'rsatilgan.");
        }

        // Maydon nomlarini yig'ish
        $allowedFields = [];
        foreach ($formFields[$tableName] as $field) {
            if (isset($field['name'])) {
                $allowedFields[] = $field['name'];
            }
        }

        try {
            DB::beginTransaction();

            // So'rov ma'lumotlarini filtrlash
            $filteredData = array_intersect_key(
                $request->all(),
                array_flip($allowedFields)
            );

            // asos_file uchun fayl yuklash logikasi
            if ($request->hasFile('asos_file')) {
                $validatedData = $request->validate([
                    'asos_file' => 'required|file|mimes:pdf,mp4,mp3,docx,doc|max:102400',
                ], [
                    'asos_file.required' => 'Fayl yuklash majburiy.',
                    'asos_file.file' => 'Yuklangan fayl haqiqiy fayl bo\'lishi kerak.',
                    'asos_file.mimes' => 'Faqat PDF, MP4, MP3, DOCX, DOC formatidagi fayllar ruxsat etiladi.',
                    'asos_file.max' => 'Fayl hajmi 100MB dan katta bo\'lmasligi kerak.',
                ]);

                $file = $request->file('asos_file');
                $path = $file->store('documents', 'public');
                $filteredData['asos_file'] = $path;
            }

            // asos_file2 uchun fayl yuklash logikasi
            if ($request->hasFile('asos_file2')) {
                $validatedData = $request->validate([
                    'asos_file2' => 'required|file|mimes:pdf,mp4,mp3,docx,doc|max:102400',
                ], [
                    'asos_file2.required' => 'Fayl yuklash majburiy.',
                    'asos_file2.file' => 'Yuklangan fayl haqiqiy fayl bo\'lishi kerak.',
                    'asos_file2.mimes' => 'Faqat PDF, MP4, MP3, DOCX, DOC formatidagi fayllar ruxsat etiladi.',
                    'asos_file2.max' => 'Fayl hajmi 100MB dan katta bo\'lmasligi kerak.',
                ]);

                $file2 = $request->file('asos_file2');
                $path2 = $file2->store('documents', 'public');
                $filteredData['asos_file2'] = $path2;
            }

            $now = Carbon::now();

            // Tahrirlash yoki yangi ma'lumot kiritish
            if ($request->edit) {
                \Log::info('Edit ID: ' . $request->edit);

                // Pointer jadvalidan ma'lumotni olish
                $pointer = PointUserDeportament::find($request->edit);

                if (!$pointer) {
                    throw new \Exception('Pointer ma\'lumoti topilmadi');
                }

                // Ustun nomini olish
                $columnName = $tableName . "id";
                \Log::info('Column name: ' . $columnName);

                $relationId = $pointer->$columnName;
                \Log::info('Relation ID: ' . $relationId);

                // Eski ma'lumotni olish
                $oldRecord = DB::table($tableName)->find($relationId);

                if (!$oldRecord) {
                    throw new \Exception('Asosiy jadvaldan ma\'lumot topilmadi');
                }

                // asos_file uchun - Eski faylni o'chirish (agar yangi fayl yuklangan bo'lsa)
                if ($request->hasFile('asos_file') && isset($oldRecord->asos_file)) {
                    Storage::disk('public')->delete($oldRecord->asos_file);
                } elseif (!$request->hasFile('asos_file') && isset($oldRecord->asos_file)) {
                    // Agar yangi fayl yuklanmagan bo'lsa, eski fayl yo'lini saqlash
                    $filteredData['asos_file'] = $oldRecord->asos_file;
                }

                // asos_file2 uchun - Eski faylni o'chirish (agar yangi fayl yuklangan bo'lsa)
                if ($request->hasFile('asos_file2') && isset($oldRecord->asos_file2)) {
                    Storage::disk('public')->delete($oldRecord->asos_file2);
                } elseif (!$request->hasFile('asos_file2') && isset($oldRecord->asos_file2)) {
                    // Agar yangi fayl yuklanmagan bo'lsa, eski fayl yo'lini saqlash
                    $filteredData['asos_file2'] = $oldRecord->asos_file2;
                }

                // Mavjud ma'lumotni yangilash
                DB::table($tableName)
                    ->where('id', $relationId)
                    ->update([
                        ...$filteredData,
                        'updated_at' => $now
                    ]);

                // Pointer jadvalini yangilash
                $pointer->update([
                    'status' => 3,
                    'year' => $request->year,
                    'updated_at' => $now
                ]);

                DB::commit();
                return redirect()->back()->with('success', "Ma'lumotlar muvaffaqiyatli yangilandi va qayta tekshiruvga yuborildi");
            }
            // Yangi ma'lumot kiritish
            else {
                $insertedId = DB::table($tableName)
                    ->insertGetId([
                        ...$filteredData,
                        'created_at' => $now,
                        'updated_at' => null
                    ]);

                // Pointer jadvaliga insert
                $columnData = [
                    'user_id' => $user->id,
                    'status' => 3,
                    'year' => $request->year,
                    'departament_id' => $user->department_id,
                    'updated_at' => null,
                    'created_at' => $now
                ];

                // Tablitsa nomi asosida relation ustunini qo'shish
                $columnName = $tableName . "id";
                $columnData[$columnName] = $insertedId;

                PointUserDeportament::create($columnData);

                DB::commit();
                return redirect()->back()->with('success', "Ma'lumotlar muvaffaqiyatli saqlandi");
            }
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Xatolik: ' . $e->getMessage());

            // Xatolik yuz berganda agar yangi fayllar yuklangan bo'lsa, ularni o'chirish
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            if (isset($path2)) {
                Storage::disk('public')->delete($path2);
            }

            return redirect()
                ->back()
                ->with('error', "Xatolik yuz berdi: " . $e->getMessage())
                ->withInput();
        }
    }

    public function departmentStoreForm(Request $request, $tableName)
    {
        // Foydalanuvchini autentifikatsiyadan o'tganligini tekshirish
        $user = auth()->user();
        if (!$user) {
            return "Foydalanuvchi tizimga kirmagan.";
        }

        // Config faylidan ruxsat etilgan maydonlarni olish
        $formFields = config('department_forms_fields');

        // Tablitsa mavjudligini tekshirish
        if (!isset($formFields[$tableName])) {
            return redirect()->back()->with('error', "Noto'g'ri tablitsa nomi ko'rsatilgan.");
        }

        // Maydon nomlarini yig'ish
        $allowedFields = [];
        foreach ($formFields[$tableName] as $field) {
            if (isset($field['name'])) {
                $allowedFields[] = $field['name'];
            }
        }

        try {
            DB::beginTransaction();

            // So'rov ma'lumotlarini filtrlash
            $filteredData = array_intersect_key(
                $request->all(),
                array_flip($allowedFields)
            );

            // Fayl yuklash logikasi
            if ($request->hasFile('asos_file')) {
                $validatedData = $request->validate([
                    'asos_file' => 'required|file|mimes:pdf|max:2048',
                ], [
                    'asos_file.required' => 'Fayl yuklash majburiy.',
                    'asos_file.file' => 'Yuklangan fayl haqiqiy fayl bo\'lishi kerak.',
                    'asos_file.mimes' => 'Faqat PDF formatidagi fayllar ruxsat etiladi.',
                    'asos_file.max' => 'Fayl hajmi 2MB dan katta bo\'lmasligi kerak.',
                ]);

                $file = $request->file('asos_file');
                $path = $file->store('documents', 'public');
                $filteredData['asos_file'] = $path;
            }

            $now = Carbon::now();

            // Tahrirlash yoki yangi ma'lumot kiritish
            if ($request->edit) {
                \Log::info('Edit ID: ' . $request->edit);

                // Pointer jadvalidan ma'lumotni olish
                $pointer = PointUserDeportament::find($request->edit);

                if (!$pointer) {
                    throw new \Exception('Pointer ma\'lumoti topilmadi');
                }

                // Ustun nomini olish
                $columnName = $tableName . "id";
                \Log::info('Column name: ' . $columnName);

                $relationId = $pointer->$columnName;
                \Log::info('Relation ID: ' . $relationId);

                // Eski ma'lumotni olish
                $oldRecord = DB::table($tableName)->find($relationId);

                if (!$oldRecord) {
                    throw new \Exception('Asosiy jadvaldan ma\'lumot topilmadi');
                }

                // Eski faylni o'chirish (agar yangi fayl yuklangan bo'lsa)
                if ($request->hasFile('asos_file') && isset($oldRecord->asos_file)) {
                    Storage::disk('public')->delete($oldRecord->asos_file);
                } elseif (!$request->hasFile('asos_file') && isset($oldRecord->asos_file)) {
                    // Agar yangi fayl yuklanmagan bo'lsa, eski fayl yo'lini saqlash
                    $filteredData['asos_file'] = $oldRecord->asos_file;
                }

                // Mavjud ma'lumotni yangilash
                DB::table($tableName)
                    ->where('id', $relationId)
                    ->update([
                        ...$filteredData,
                        'updated_at' => $now
                    ]);

                // Pointer jadvalini yangilash
                $pointer->update([
                    'status' => 3,
                    'year' => $request->year,
                    'updated_at' => $now
                ]);

                DB::commit();
                return redirect()->back()->with('success', "Ma'lumotlar muvaffaqiyatli yangilandi va qayta tekshiruvga yuborildi");
            }
            // Yangi ma'lumot kiritish
            else {
                $insertedId = DB::table($tableName)
                    ->insertGetId([
                        ...$filteredData,
                        'created_at' => $now,
                        'updated_at' => null
                    ]);

                // Pointer jadvaliga insert uchun ma'lumot tayyorlash
                $columnData = [
                    'user_id' => $user->id,
                    'status' => 3,
                    'year' => $request->year,
                    'departament_id' => $user->department_id,
                    'departament_info' => true, // Departament ma'lumotlarini aniqlash
                    'updated_at' => null,
                    'created_at' => $now
                ];

                // Tablitsa nomi asosida relation ustunini qo'shish
                $columnName = $tableName . "id";
                $columnData[$columnName] = $insertedId;

                // Pointer jadvaliga insert
                PointUserDeportament::create($columnData);

                DB::commit();
                return redirect()->back()->with('success', "Ma'lumotlar muvaffaqiyatli saqlandi");
            }
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Xatolik: ' . $e->getMessage());

            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()
                ->back()
                ->with('error', "Xatolik yuz berdi: " . $e->getMessage())
                ->withInput();
        }
    }
}
