<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MamaDataController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ReminderController;

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

// Auth routes
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

// Public route for getting user details
Route::get('user/{id}', [AuthController::class, 'getUserDetails']);

// Mama Data route - no middleware
Route::post('/mama-data', [MamaDataController::class, 'store']);

// Journal routes - no authentication
Route::post('/journals', [JournalController::class, 'store']);
Route::get('/journals/{user_id}', [JournalController::class, 'index']);
Route::delete('/journals/{id}', [JournalController::class, 'destroy']);

// Reminder routes - no authentication
Route::get('/reminders/{user_id}', [ReminderController::class, 'index']);
Route::post('/reminders', [ReminderController::class, 'store']);
Route::put('/reminders/{id}', [ReminderController::class, 'update']);
Route::delete('/reminders/{id}', [ReminderController::class, 'destroy']);
