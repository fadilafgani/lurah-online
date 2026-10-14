<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\UnitAccountController;

Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware([
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ]);

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->middleware([
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ]);

Route::post('/complaints', [ComplaintController::class, 'store']);
Route::get('/complaints/{ticket_code}', [ComplaintController::class, 'show']);
Route::get('/statistics', [ComplaintController::class, 'statistics']);

Route::middleware([
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    'admin.auth',
])->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
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
});
