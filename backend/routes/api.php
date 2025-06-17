<?php

use App\Http\Controllers\CallLogController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/{id}', [ContactController::class, 'show']);
    Route::put('/contacts/{id}', [ContactController::class, 'update']);
    Route::get('roles', [ContactController::class, 'roles']);
});

Route::group([], function () {
    Route::get('/call-logs', [CallLogController::class, 'index']);
    Route::get('/call-logs/{id}', [CallLogController::class, 'show']);
    Route::post('/call-logs', [CallLogController::class, 'store']);
});