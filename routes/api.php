<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ComplaintController;

Route::post('/admin/login', [AuthController::class, 'login']);

Route::post('/complaints', [ComplaintController::class, 'store']);
Route::get('/complaints/{ticket_code}', [ComplaintController::class, 'show']);
Route::get('/statistics', [ComplaintController::class, 'statistics']);
