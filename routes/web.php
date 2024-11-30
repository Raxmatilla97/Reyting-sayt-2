<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormsController;
use Illuminate\Contracts\Session\Session;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KpiReviewController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ExportInfosController;
use App\Http\Controllers\KpiCriteriaController;
use App\Http\Controllers\Auth\ApiHemisController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\KpiSubmissionController;
use App\Http\Controllers\PointUserDeportamentController;
use App\Http\Controllers\StudentsCountForDepartController;
use App\Http\Controllers\Export\Two\DepartmentTwoExcelController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/



// Frontend va Auth routelari
Route::get('/', [FrontendController::class, 'index'])->name('welcome');

// Auth routelari
Route::middleware(['guest'])->group(function () {
    // Login sahifasi
    Route::get('/login', function() {
        return view('auth.login');
    })->name('login');

    // HEMIS auth routelari
    Route::get("/oauth", [ApiHemisController::class, 'redirectToAuthorization'])
        ->name("redirectToAuthorization");
    Route::get("/oauth/callback", [ApiHemisController::class, 'handleAuthorizationCallback'])
        ->name("handleAuthorizationCallback");
});

// Autentifikatsiya qilingan foydalanuvchilar uchun routelar
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routelari
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::patch('/update-department', [ProfileController::class, 'updateDepartment'])
            ->name('profile.update.department');
    });

    // Fakultet routelari
    Route::prefix('faculties')->group(function () {
        Route::get('/', [FacultyController::class, 'index'])->name('dashboard.faculties');
        Route::get('/{slug}', [FacultyController::class, 'facultyShow'])->name('dashboard.facultyShow');
        Route::get('/getItemDetails/{id}', [FacultyController::class, 'getItemDetails'])
            ->name('dashboard.getItemDetails');
    });

    // Kafedra routelari
    Route::prefix('departments')->group(function () {
        Route::get('/{name?}', [DepartmentController::class, 'index'])->name('dashboard.departments');
        Route::get('/form/chose', [DepartmentController::class, 'departmentFormChose'])
            ->name('dashboard.department_form_chose');
        Route::get('/show-form/{type}', [FormsController::class, 'departmentShowForm'])
            ->name('dashboard.show_department_form');
        Route::post('/send-form/{tableName}', [FormsController::class, 'departmentStoreForm'])
            ->name('dashboard.department_store_form');
        Route::get('/{slug}', [DepartmentController::class, 'departmentShow'])
            ->name('dashboard.departmentShow');
    });

    // O'qituvchilar routelari
    Route::prefix('employees')->group(function () {
        Route::get('/{name?}', [EmployeeController::class, 'index'])->name('dashboard.employees');
        Route::get('/form/chose', [EmployeeController::class, 'employeeFormChose'])
            ->name('dashboard.employee_form_chose');
        Route::get('/show-form/{type}', [FormsController::class, 'employeeShowForm'])
            ->name('dashboard.show_employee_form');
        Route::post('/send-form/{tableName}', [FormsController::class, 'employeeStoreForm'])
            ->name('dashboard.employee_store_form');
        Route::get('/my-information', [EmployeeController::class, 'mySubmittedInformation'])
            ->name('dashboard.my_submitted_information');
        Route::get('/{id_employee}', [EmployeeController::class, 'employeeShow'])
            ->name('dashboard.employeeShow');
    });

    // KPI routelari
    Route::prefix('kpi')->group(function () {
        Route::resource('/', KpiSubmissionController::class);
        Route::get('/submissions/{id}/details', [KpiSubmissionController::class, 'getDetails'])
            ->name('kpi-submissions.details');
        Route::post('/submissions/{id}/apilation', [KpiSubmissionController::class, 'submitApilation'])
            ->name('kpi-submissions.apilation');
        Route::get('/api/categories', [KpiSubmissionController::class, 'getCategories']);
        Route::get('/criteria/{category}', [KpiSubmissionController::class, 'getCriteria']);
    });

    // KPI Reviewer routelari
    Route::prefix('admin/kpi-reviewers')
        ->middleware(['auth', 'is_kpi_reviewer'])
        ->group(function () {
            Route::get('/', [KpiReviewController::class, 'reviewersIndex'])
                ->name('admin.kpi-reviewers.index');
            Route::get('/search', [KpiReviewController::class, 'search'])
                ->name('admin.kpi-reviewers.search');
            Route::get('/user/{user}', [KpiReviewController::class, 'getUserDetails'])
                ->name('admin.kpi-reviewers.user-details');
            Route::post('/{user}/update-faculty', [KpiReviewController::class, 'updateUserFaculty'])
                ->name('admin.kpi-reviewers.update-faculty');
            Route::post('/{user}/update', [KpiReviewController::class, 'reviewersUpdate'])
                ->name('admin.kpi-reviewers.update');
            Route::get('/kpi', [KpiReviewController::class, 'index'])
                ->name('admin.kpi.index');
            Route::put('/kpi/{submission}/review', [KpiReviewController::class, 'review'])
                ->name('admin.kpi.review');
    });

    // Admin routelari
    Route::middleware(['auth', 'isadmin'])->prefix('admin')->group(function () {
        // Murojatlar
        Route::get('/murojatlar', [PointUserDeportamentController::class, 'list'])
            ->name('murojatlar.list');
        Route::get('/murojatlar/{id?}', [PointUserDeportamentController::class, 'show'])
            ->name('murojatlar.show');
        Route::post('/murojatlar/tasdiqlash', [PointUserDeportamentController::class, 'murojatniTasdiqlash'])
            ->name('murojatlar.murojatniTasdiqlash');
        Route::delete('/murojatlar/{id}', [PointUserDeportamentController::class, 'destroy'])
            ->name('murojaat.destroy');

        // Ma'lumotlarni yangilash
        Route::post('/update-faculties', [FacultyController::class, 'update'])
            ->name('update.faculties');
        Route::get('/update-departments', [ConfigurationController::class, 'updateDepartments']);
        Route::post('/stop-departments-update', [ConfigurationController::class, 'stopDepartmentsUpdate']);
        Route::get('/register-all-teachers', [ConfigurationController::class, 'registerAllTeachers']);
        Route::post('/stop-teachers-registration', [ConfigurationController::class, 'stopTeachersRegistration']);
        Route::get('/update-teacher-departments', [ConfigurationController::class, 'updateTeacherDepartments'])
            ->name('update.teacher.departments');

        // Export/Import
        Route::prefix('export')->group(function () {
            Route::get('/config', [ExportInfosController::class, 'export'])->name('export');
            Route::get('/download', [ExportInfosController::class, 'download'])->name('download');
            Route::get('/test', [ExportInfosController::class, 'testExcelExport'])
                ->name('testExcelExport');
            Route::get('/department-two/generate', [DepartmentTwoExcelController::class, 'generateExcel'])
                ->name('excel.generate_two');
            Route::get('/department-two/download/{filename}', [DepartmentTwoExcelController::class, 'downloadExcel'])
                ->name('excel.download_two');
        });

        // Talabalar soni
        Route::prefix('student-counts')->group(function () {
            Route::get('/', [StudentsCountForDepartController::class, 'index'])
                ->name('student-counts.index');
            Route::get('/export', [StudentsCountForDepartController::class, 'export'])
                ->name('student-counts.export');
            Route::post('/import', [StudentsCountForDepartController::class, 'import'])
                ->name('student-counts.import');
        });

        // KPI mezonlari
        Route::resource('criteria', KpiCriteriaController::class)->names('admin.criteria');

        // Konfiguratsiya
        Route::post('/delete-rejected-data', [ConfigurationController::class, 'delete'])
            ->name('delete.rejected.data');
    });

    // Utility routelari
    Route::get('/get-current-user', function () {
        return response()->json(['id' => Auth::id()]);
    });
});

// SSE test routeri
Route::get('/sse-test', function () {
    ini_set('max_execution_time', 300);
    set_time_limit(300);
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

// Session tozalash routeri
Route::get('/clear-session', function() {
    Session::flush();
    Auth::logout();
    return redirect('/login');
})->name('clear.session');


require __DIR__ . '/auth.php';
