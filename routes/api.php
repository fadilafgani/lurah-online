<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\UnitAccountController;

Route::post('/admin/login', [AuthController::class, 'login']);

Route::post('/complaints', [ComplaintController::class, 'store']);
Route::get('/complaints/{ticket_code}', [ComplaintController::class, 'show']);
Route::get('/statistics', [ComplaintController::class, 'statistics']);

Route::middleware('auth:sanctum')->group(function () {
    // Dipakai navbar & kartu "sedang masuk": halaman admin dirender tanpa
    // sesi, jadi identitas pemakai token harus bisa ditanyakan tersendiri.
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('admin')->group(function () {
        Route::get('/admin/unit-accounts', [UnitAccountController::class, 'index']);
        Route::post('/admin/unit-accounts', [UnitAccountController::class, 'store']);
        Route::put('/admin/unit-accounts/{user}', [UnitAccountController::class, 'update']);
        Route::post('/admin/unit-accounts/{user}/reset-password', [UnitAccountController::class, 'resetPassword']);
        Route::delete('/admin/unit-accounts/{user}', [UnitAccountController::class, 'destroy']);
    });
});
