<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShiftContoller;
use App\Http\Controllers\RequiredHoursController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::inertia('/login', 'Auth/Login')->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::inertia('/request', 'Employee/Request')->name('request');
    Route::inertia('/', 'Main')->name('main');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

     // shift code routes
    Route::get('/shift', [ShiftContoller::class, 'registeredShiftCodes'])->name('shifts');
    Route::post('/shift/register', [ShiftContoller::class, 'insertShiftCode'])->name('shift.register'); // insertion route
    Route::put('/shift/{shift}', [ShiftContoller::class, 'updateShiftCode'])->name('shift.update'); // update route
    Route::delete('/shift/{shift}', [ShiftContoller::class, 'deleteShiftCode'])->name('shift.delete'); // delete route

    // required hours routes
    Route::get('/hours', [RequiredHoursController::class, 'registeredRequiredHours'])->name('hours');
    Route::post('/hours/register', [RequiredHoursController::class, 'registerRequiredHours'])->name('hours.register');
});