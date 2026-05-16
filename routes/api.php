<?php

use App\Http\Controllers\Api\AttendanceSyncController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\PayrollController;
use App\Http\Controllers\Api\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Staff Management
    Route::get('/staff/sync-down', [StaffController::class, 'syncDown']);
    Route::post('/staff/register', [StaffController::class, 'register']);

    // Attendance
    Route::post('/attendance/sync-up', [AttendanceSyncController::class, 'syncUp']);

    // Payroll
    Route::get('/payroll/generate', [PayrollController::class, 'generate']);

    // Holidays
    Route::get('/holidays', [HolidayController::class, 'index']);
    Route::post('/holidays', [HolidayController::class, 'store']);
});
