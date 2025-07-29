<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\ShiftContoller;
use App\Http\Controllers\RequiredHoursController;
use App\Http\Controllers\ScheduleController;
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

    // shift code requests (axios)
    Route::get('/shift/list', [ShiftContoller::class, 'shiftCodeList']);

    // required hours routes
    Route::get('/hours', [RequiredHoursController::class, 'registeredRequiredHours'])->name('hours');
    Route::post('/hours/register', [RequiredHoursController::class, 'registerRequiredHours'])->name('hours.register');
    Route::put('/hours/{requiredHours}', [RequiredHoursController::class, 'updateRequiredHour'])->name('hours.update');

    // schedule routes
    Route::inertia('/schedule', 'Employee/Schedule')->name('schedule');
    Route::get('/schedule/list', [ScheduleController::class, 'fetchSchedule']);
    Route::get('/schedule/user', [ScheduleController::class, 'getUserSchedule']);
    Route::post('/schedule/submit', [ScheduleController::class, 'submitSchedule'])->name('schedule.submit');

    // overtime request routes
    Route::post('overtime/request', [OvertimeRequestController::class, 'insertOvertimeRequest'])->name('overtime.request');
});
