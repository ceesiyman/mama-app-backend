<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MamaDataController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\MamaTipController;
use App\Http\Controllers\AiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes that require JWT
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

// Password reset via OTP (public)
Route::post('/auth/request-password-reset', [AuthController::class, 'requestPasswordReset']);
Route::post('/auth/verify-otp-reset', [AuthController::class, 'verifyOtpReset']);

// Logout route with remember_token check
Route::post('/auth/logout/{user_id}', [AuthController::class, 'logout'])
    ->middleware('check.remember_token');

// Mama Data route - no middleware
Route::post('/mama-data', [MamaDataController::class, 'store']);

// Mama Data routes
Route::get('/mama-data/{user_id}', [MamaDataController::class, 'show']);

// Journal routes - no authentication
Route::post('/journals', [JournalController::class, 'store']);
Route::get('/journals/{user_id}', [JournalController::class, 'index']);
Route::delete('/journals/{id}', [JournalController::class, 'destroy']);

// Reminder routes - no authentication
Route::get('/reminders/{user_id}', [ReminderController::class, 'index']);
Route::post('/reminders', [ReminderController::class, 'store']);
Route::put('/reminders/{id}', [ReminderController::class, 'update']);
Route::delete('/reminders/{id}', [ReminderController::class, 'destroy']);
Route::patch('/reminders/{id}/status', [ReminderController::class, 'updateStatus']);

// User update route - no authentication
Route::put('/users/{user_id}', [AuthController::class, 'updateUser']);

// User image routes - no authentication
Route::post('/users/{user_id}/image', [AuthController::class, 'updateUserImage']);
Route::get('/users/{user_id}/image', [AuthController::class, 'getUserImage']);

// Mama Tips routes - no authentication
Route::get('/mama-tips', [MamaTipController::class, 'index']);
Route::get('/mama-tips/{id}', [MamaTipController::class, 'show']);


Route::post('/api/ai-request', [AiController::class, 'processRequest']);