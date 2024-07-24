<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiHemisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\Table11Controller;
use App\Http\Controllers\Table12Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    // Auth bo'lib kirganlar uchun routes

    // Dashboard bosh sahifasi
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fakultetlar uchun CRUD Routerlari
    Route::get('/faculties', [FacultyController::class, 'index'])->name('dashboard.faculties');

    // Kafedralar uchun CRUD Routerlari
    Route::get('/departments', [DepartmentController::class, 'index'])->name('dashboard.departments');

    // O'qituvchilar uchun CRUD Routerlari
    Route::get('/employees', [EmployeeController::class, 'index'])->name('dashboard.employees');

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

});


Route::get("/oauth", [ApiHemisController::class, 'redirectToAuthorization'])->name("redirectToAuthorization");
Route::get("/oauth/callback", [ApiHemisController::class, 'handleAuthorizationCallback'])->name("handleAuthorizationCallback");
require __DIR__ . '/auth.php';
