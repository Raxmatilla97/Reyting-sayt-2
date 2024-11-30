<?php

use App\Http\Controllers\Auth\ApiHemisController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportInfosController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\KpiCriteriaController;
use App\Http\Controllers\KpiReviewController;
use App\Http\Controllers\KpiSubmissionController;
use App\Http\Controllers\PointUserDeportamentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentsCountForDepartController;
use App\Http\Controllers\Export\Two\DepartmentTwoExcelController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Veb Routelari
|--------------------------------------------------------------------------
*/

// Ommaviy routelar
Route::get('/', [FrontendController::class, 'index'])->name('welcome');

// Mehmon foydalanuvchilar uchun routelar
Route::middleware(['guest'])->group(function () {
    Route::get("/oauth", [ApiHemisController::class, 'redirectToAuthorization'])
        ->name("redirectToAuthorization");
    Route::get("/oauth/callback", [ApiHemisController::class, 'handleAuthorizationCallback'])
        ->name("handleAuthorizationCallback");
});

// Autentifikatsiyadan o'tgan foydalanuvchilar uchun routelar
Route::middleware('auth')->group(function () {
    // Boshqaruv paneli
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fakultet routelari
    Route::get('/faculties', [FacultyController::class, 'index'])->name('dashboard.faculties');
    Route::get('/faculty/{slug}', [FacultyController::class, 'facultyShow'])->name('dashboard.facultyShow');
    Route::get('/getItemDetails/{id}', [FacultyController::class, 'getItemDetails'])->name('dashboard.getItemDetails');

    // Kafedra routelari
    Route::get('/departments/{name?}', [DepartmentController::class, 'index'])->name('dashboard.departments');
    Route::get('/department_form_chose', [DepartmentController::class, 'departmentFormChose'])->name('dashboard.department_form_chose');
    Route::get('/show-department-form/{type}', [FormsController::class, 'departmentShowForm'])->name('dashboard.show_department_form');
    Route::post('/send-department-form/{tableName}', [FormsController::class, 'departmentStoreForm'])->name('dashboard.department_store_form');
    Route::get('/department/{slug}', [DepartmentController::class, 'departmentShow'])->name('dashboard.departmentShow');

    // Xodimlar routelari
    Route::get('/employees/{name?}', [EmployeeController::class, 'index'])->name('dashboard.employees');
    Route::get('/employee-form-chose', [EmployeeController::class, 'employeeFormChose'])->name('dashboard.employee_form_chose');
    Route::get('/show-employee-form/{type}', [FormsController::class, 'employeeShowForm'])->name('dashboard.show_employee_form');
    Route::post('/send-employee-form/{tableName}', [FormsController::class, 'employeeStoreForm'])->name('dashboard.employee_store_form');
    Route::get('/employee/{id_employee}', [EmployeeController::class, 'employeeShow'])->name('dashboard.employeeShow');
    Route::get('/my-submitted-information', [EmployeeController::class, 'mySubmittedInformation'])->name('dashboard.my_submitted_information');

    // Profil routelari
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update-department', [ProfileController::class, 'updateDepartment'])->name('profile.update.department');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // KPI routelari
    Route::resource('kpi', KpiSubmissionController::class);
    Route::get('/kpi-submissions/{id}/details', [KpiSubmissionController::class, 'getDetails'])->name('kpi-submissions.details');
    Route::post('/kpi-submissions/{id}/apilation', [KpiSubmissionController::class, 'submitApilation'])->name('kpi-submissions.apilation');
    Route::get('/api/categories', [KpiSubmissionController::class, 'getCategories']);
    Route::get('/kpi/criteria/{category}', [KpiSubmissionController::class, 'getCriteria']);

    // Admin va KPI tekshiruvchilar uchun routelar
    Route::prefix('admin')->group(function () {
        // KPI tekshiruv routelari (Admin va Tekshiruvchilar uchun)
        Route::middleware(['auth', 'is_kpi_reviewer'])->group(function () {
            Route::get('/kpi', [KpiReviewController::class, 'index'])->name('admin.kpi.index');
            Route::put('/kpi/{kpiSubmission}/review', [KpiReviewController::class, 'review'])->name('admin.kpi.review');
        });

        // Admin routelari
        Route::middleware(['isadmin'])->group(function () {
            // KPI tekshiruvchilarni boshqarish
            Route::get('/kpi-reviewers', [KpiReviewController::class, 'reviewersIndex'])->name('admin.kpi-reviewers.index');
            Route::get('/kpi-reviewers/search', [KpiReviewController::class, 'search'])->name('admin.kpi-reviewers.search');
            Route::get('/kpi-reviewers/user/{user}', [KpiReviewController::class, 'getUserDetails'])->name('admin.kpi-reviewers.user-details');
            Route::post('/kpi-reviewers/{user}/update-faculty', [KpiReviewController::class, 'updateUserFaculty'])->name('admin.kpi-reviewers.update-faculty');
            Route::post('/kpi-reviewers/{user}/update', [KpiReviewController::class, 'reviewersUpdate'])->name('admin.kpi-reviewers.update');

            // Murojatlarni boshqarish
            Route::get('/murojatlar-list', [PointUserDeportamentController::class, 'list'])->name('murojatlar.list');
            Route::get('/murojatni-korish/{id?}', [PointUserDeportamentController::class, 'show'])->name('murojatlar.show');
            Route::post('/murojatni-tasdiqlash', [PointUserDeportamentController::class, 'murojatniTasdiqlash'])->name('murojatlar.murojatniTasdiqlash');
            Route::delete('/murojatni-ochirish/{id}', [PointUserDeportamentController::class, 'destroy'])->name('murojaat.destroy');

            // Ma'lumotlarni boshqarish
            Route::post('/update-faculties', [FacultyController::class, 'update'])->name('update.faculties');
            Route::post('/delete-rejected-data', [ConfigurationController::class, 'delete'])->name('delete.rejected.data');
            Route::get('/update-teacher-departments', [ConfigurationController::class, 'updateTeacherDepartments'])->name('update.teacher.departments');
            Route::get('/update-departments', [ConfigurationController::class, 'updateDepartments']);
            Route::post('/stop-departments-update', [ConfigurationController::class, 'stopDepartmentsUpdate']);
            Route::get('/register-all-teachers', [ConfigurationController::class, 'registerAllTeachers']);
            Route::post('/stop-teachers-registration', [ConfigurationController::class, 'stopTeachersRegistration']);

            // Admin mezonlari
            Route::prefix('admin')->name('admin.')->group(function () {
                Route::resource('criteria', KpiCriteriaController::class);
            });

            // Export routelari
            Route::get('/config', [ExportInfosController::class, 'export'])->name('export');
            Route::get('/download', [ExportInfosController::class, 'download'])->name('download');
            Route::get('/test-excel', [ExportInfosController::class, 'testExcelExport'])->name('testExcelExport');

            // Talabalar soni
            Route::get('/student-counts', [StudentsCountForDepartController::class, 'index'])->name('student-counts.index');
            Route::get('/student-counts/export', [StudentsCountForDepartController::class, 'export'])->name('student-counts.export');
            Route::post('/student-counts/import', [StudentsCountForDepartController::class, 'import'])->name('student-counts.import');

            // Kafedra eksporti
            Route::get('/export/department-two/generate', [DepartmentTwoExcelController::class, 'generateExcel'])->name('excel.generate_two');
            Route::get('/export/department-two/download/{filename}', [DepartmentTwoExcelController::class, 'downloadExcel'])->name('excel.download_two');

            // Foydalanuvchi ma'lumotlari
            Route::get('/get-current-user', function () {
                return response()->json(['id' => Auth::id()]);
            });
        });
    });
});

require __DIR__ . '/auth.php';
