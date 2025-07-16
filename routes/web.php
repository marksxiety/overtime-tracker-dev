<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::inertia('/login', 'Auth/Login')->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::inertia('/request', 'Employee/Request')->name('request');
    Route::inertia('/shift', 'Maintenance/ShiftCodes')->name('shifts');
    Route::inertia('/hours', 'Maintenance/RequiredHours')->name('hours');
    Route::inertia('/', 'Main')->name('main');

     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});