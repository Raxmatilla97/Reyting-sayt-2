<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiHemisController;


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

    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    

});


Route::get("/oauth", [ApiHemisController::class, 'redirectToAuthorization'])->name("redirectToAuthorization");
Route::get("/oauth/callback", [ApiHemisController::class, 'handleAuthorizationCallback'])->name("handleAuthorizationCallback");
require __DIR__ . '/auth.php';
