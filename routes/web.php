<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Auth\ApiHemisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PointUserDeportamentController;
use App\Http\Controllers\ExportInfosController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/', [FrontendController::class, 'index'])->name('welcome');

// routes/web.php
Route::get('/sse-test', function () {
    ini_set('max_execution_time', 300); // 5 daqiqa
    set_time_limit(300);
    Log::info('Max execution time: ' . ini_get('max_execution_time'));
    return response()->stream(function () {
        echo "data: " . json_encode(['message' => 'Test message', 'progress' => 50]) . "\n\n";
        ob_flush();
        flush();
        sleep(2);
        echo "data: " . json_encode(['message' => 'Test complete', 'progress' => 100]) . "\n\n";
        ob_flush();
        flush();
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
});

Route::middleware('auth')->group(function () {


    // Dashboard bosh sahifasi
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fakultetlar uchun CRUD Routerlari
    Route::get('/faculties', [FacultyController::class, 'index'])->name('dashboard.faculties');

    // Kafedralar uchun CRUD Routerlari
    Route::get('/departments/{name?}', [DepartmentController::class, 'index'])->name('dashboard.departments');

    // O'qituvchilar uchun CRUD Routerlari
    Route::get('/employees/{name?}', [EmployeeController::class, 'index'])->name('dashboard.employees');

    // O'qituvchilar malumotini bolimini tanlash sahifasi
    Route::get('/employee-form-chose', [EmployeeController::class, 'employeeFormChose'])->name('dashboard.employee_form_chose');

    // Forma sahifasini ko'rsatish uchun Route
    Route::get('/show-employee-form/{type}', [FormsController::class, 'employeeShowForm'])->name('dashboard.show_employee_form');

    // Formadagi malumotlarni umumiy routerga yuborish
    Route::post('/send-employee-form/{tableName}', [FormsController::class, 'employeeStoreForm'])->name('dashboard.employee_store_form');

    // Kafedra malumotini bolimini tanlash sahifasi
    Route::get('/department_form_chose', [DepartmentController::class, 'departmentFormChose'])->name('dashboard.department_form_chose');

    // Forma sahifasini ko'rsatish uchun Route
    Route::get('/show-department-form/{type}', [FormsController::class, 'departmentShowForm'])->name('dashboard.show_department_form');

    // Formadagi malumotlarni umumiy routerga yuborish
    Route::post('/send-department-form/{tableName}', [FormsController::class, 'departmentStoreForm'])->name('dashboard.department_store_form');

    Route::view('profile', 'profile')->name('profile');

    // O'qituvchi yuborgan ma'lumotlarni o'ziga tegishlisini ko'rish
    Route::get('/my-submitted-information', [EmployeeController::class, 'mySubmittedInformation'])->name('dashboard.my_submitted_information');

    // Fakultet ma'lumotlarini show qilish
    Route::get('/faculty/{slug}', [FacultyController::class, 'facultyShow'])->name('dashboard.facultyShow');

    // Fakultet sahifasida Itemlarni Ko'rish tugmasini bosganda Ajax so'rov yuborish routeri
    Route::get('/getItemDetails/{id}', [FacultyController::class, 'getItemDetails'])->name('dashboard.getItemDetails');

    // Kafedra ma'lumotlarini show qilish
    Route::get('/department/{slug}', [DepartmentController::class, 'departmentShow'])->name('dashboard.departmentShow');

    // O'qituvchi ma'lumotlarini show qilish
    Route::get('/employee/{id_employee}', [EmployeeController::class, 'employeeShow'])->name('dashboard.employeeShow');

    // Kafedrani o'zgartirish
    Route::patch('/profile/update-department', [ProfileController::class, 'updateDepartment'])->name('profile.update.department');

    //Faqat adminlar uchun routelar
    Route::middleware(['auth', 'isadmin'])->group(function () {
        // Murojaatlarni ro'yxati va ko'rish
        Route::get('/murojatlar-list', [PointUserDeportamentController::class, 'list'])->name('murojatlar.list');
        Route::get('/murojatni-korish/{id?}', [PointUserDeportamentController::class, 'show'])->name('murojatlar.show');

        // Murojaatlarni tasdiqlash va o'chirish routeri
        Route::post('/murojatni-tasdiqlash', [PointUserDeportamentController::class, 'murojatniTasdiqlash'])->name('murojatlar.murojatniTasdiqlash');
        Route::delete('/murojatni-ochirish/{id}', [PointUserDeportamentController::class, 'destroy'])->name('murojaat.destroy');

        // Fakultet va kafedra ma'lumotlarini HemisApi dan yangilab olish routeri
        Route::post('/update-faculties', [FacultyController::class, 'update'])->name('update.faculties');

        // Ma'lumotlarni excelga export qilish
        Route::get('/config', [ExportInfosController::class, 'export'])->name('export');
        Route::get('/download', [ExportInfosController::class, 'download'])->name('download');
        Route::get('/test-excel', [ExportInfosController::class, 'testExcelExport'])->name('testExcelExport');

        // Hamma rad etilgan ma'lumotlarni o'chirish
        Route::post('/delete-rejected-data', [ConfigurationController::class, 'delete'])->name('delete.rejected.data')->middleware('web');

        // O'qituvchilar sonini configga yozish
        Route::get('/update-teachers-count', [ConfigurationController::class, 'updateTeachersCount'])->name('update.teachers.count');
        Route::post('/stop-teachers-update', [ConfigurationController::class, 'stopTeachersUpdate'])->name('stop.teachers.update');
        Route::get('/check-progress', [ConfigurationController::class, 'checkProgress'])->name('check.progress');

        // O'qituvchilarni kafedradagi ayni damdagi o'rnga o'tqazish

        Route::get('/update-teacher-departments', [ConfigurationController::class, 'updateTeacherDepartments'])
        ->name('update.teacher.departments');




    });

    // Auth bo'lib kirganlar uchun routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get("/oauth", [ApiHemisController::class, 'redirectToAuthorization'])->name("redirectToAuthorization");
Route::get("/oauth/callback", [ApiHemisController::class, 'handleAuthorizationCallback'])->name("handleAuthorizationCallback");


require __DIR__ . '/auth.php';
