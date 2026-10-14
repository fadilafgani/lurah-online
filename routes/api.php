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

        // Manajemen Data Warga
        Route::get('/admin/residents', [\App\Http\Controllers\Admin\ResidentController::class, 'index']);
        Route::post('/admin/residents', [\App\Http\Controllers\Admin\ResidentController::class, 'store']);
        Route::get('/admin/residents/{resident}', [\App\Http\Controllers\Admin\ResidentController::class, 'show']);
        Route::put('/admin/residents/{resident}', [\App\Http\Controllers\Admin\ResidentController::class, 'update']);
        Route::put('/admin/residents/{resident}/status', [\App\Http\Controllers\Admin\ResidentController::class, 'toggleStatus']);
        Route::post('/admin/residents/{resident}/reset-password', [\App\Http\Controllers\Admin\ResidentController::class, 'resetPassword']);
        Route::post('/admin/residents/preview-import', [\App\Http\Controllers\Admin\ResidentController::class, 'previewImport']);
        Route::post('/admin/residents/confirm-import', [\App\Http\Controllers\Admin\ResidentController::class, 'confirmImport']);
    });
});
