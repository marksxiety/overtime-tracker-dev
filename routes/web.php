<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\ShiftContoller;
use App\Http\Controllers\RequiredHoursController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['guest'])->group(function () {
    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::inertia('/login', 'Auth/Login')->name('login');
});

Route::get('/', function (Request $request) {
    $role = Auth::user()->role;
    return match ($role) {
        'admin' => Inertia::render('Admin/Index'),
        'approver' => app(OvertimeRequestController::class)->fetchTotalOvertimeRequests($request),
        'employee' => app(OvertimeRequestController::class)->fetchOvertimeRequestsBySession($request),
        default => redirect()->route('unauthorized'),
    };
})->middleware('auth')->name('main');

Route::middleware('employee')->group(function () {
    Route::inertia('/request', 'Employee/Request')->name('request');

    // shift code list for registering schedule requests (axios)
    Route::get('/employee/shift/list', [ShiftContoller::class, 'shiftCodeList']);

    // schedule routes
    Route::inertia('/schedule', 'Employee/Schedule')->name('schedule');
    Route::get('/schedule/list', [ScheduleController::class, 'fetchSchedule']);
    Route::get('/schedule/user', [ScheduleController::class, 'getUserSchedule']);
    Route::post('/schedule/submit', [ScheduleController::class, 'submitSchedule'])->name('schedule.submit');

    // overtime request routes
    Route::post('/overtime/request', [OvertimeRequestController::class, 'insertOvertimeRequest'])->name('overtime.request');
    Route::post('/overtime/update/employee', [OvertimeRequestController::class, 'updateOvertimeRequestStatus'])->name('overtime.update.employee');

    Route::inertia('/profile', 'Profile')->name('profile.employee');
});

Route::middleware('approver')->group(function () {

    Route::get('/shift', [ShiftContoller::class, 'registeredShiftCodes'])->name('shifts');
    Route::post('/shift/register', [ShiftContoller::class, 'insertShiftCode'])->name('shift.register'); // insertion route
    Route::put('/shift/{shift}', [ShiftContoller::class, 'updateShiftCode'])->name('shift.update'); // update route
    Route::delete('/shift/{shift}', [ShiftContoller::class, 'deleteShiftCode'])->name('shift.delete'); // delete route

    // required hours routes
    Route::get('/hours', [RequiredHoursController::class, 'registeredRequiredHours'])->name('hours');
    Route::post('/hours/register', [RequiredHoursController::class, 'registerRequiredHours'])->name('hours.register');
    Route::put('/hours/{requiredHours}', [RequiredHoursController::class, 'updateRequiredHour'])->name('hours.update');

    Route::post('/overtime/update/approver', [OvertimeRequestController::class, 'updateOvertimeRequestStatus'])->name('overtime.update.approver');
    Route::get('/overtime/filing', [OvertimeRequestController::class, 'fetchOvertimeRequestsViaStatus'])->name('overtime.filing');
    Route::get('/overtime/pending', [OvertimeRequestController::class, 'fetchOvertimeRequestsViaStatus'])->name('overtime.pending');
    Route::get('/overtime/filed', [OvertimeRequestController::class, 'fetchOvertimeRequestsViaStatus'])->name('overtime.filed');

    Route::inertia('/schedule/manage', 'Approver/ManageSchedule')->name('schedule.manage');
    Route::get('/schedule/employee/list', action: [ScheduleController::class, 'fetchEmployeeSchedule']);

    Route::get('/approver/shift/list', [ShiftContoller::class, 'shiftCodeList']);
});

Route::get('/404', fn() => Inertia::render('Unauthorized'))->name('404');

Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');
