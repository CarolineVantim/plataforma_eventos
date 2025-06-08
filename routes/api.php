<?php

use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/eventos', [EventoController::class, 'store']);
    Route::get('/eventos', [EventoController::class, 'index']);
    Route::get('/eventos/{id}', [EventoController::class, 'show']);
    Route::put('/eventos/{id}', [EventoController::class, 'update']);
    Route::post('/eventos/{id}/inscrever', [EventoController::class, 'inscrever']);
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);
});
