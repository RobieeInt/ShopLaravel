<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified','isAdmin',])->group(function () {
    Route::get('/dashboard', function () { return view('dashboardAdmin');})->name('dashboard');
    // Route::get('/dashboard', [DashboardAdminController::class, 'index'])->middleware('role:USER')->name('dashboard');
    // Route::get('/dashboardAdmin', [DashboardAdminController::class, 'index'])->middleware('role:ADMIN')->name('dashboardAdmin');

});


