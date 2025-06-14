<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\GraficoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/eventos', [EventoController::class, 'store']);
    Route::get('/eventos', [EventoController::class, 'index']);
    Route::get('/eventos/{id}', [EventoController::class, 'show']);
    Route::put('/eventos/{id}', [EventoController::class, 'update']);
    Route::post('/eventos/{id}/inscrever', [EventoController::class, 'inscrever']);
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);

    Route::get('/relatorios/eventos-por-local', [GraficoController::class, 'eventosPorLocal']);
    Route::get('/relatorios/eventos-por-tema', [GraficoController::class, 'eventosPorTema']);
    Route::get('/relatorios/eventos-por-mes', [GraficoController::class, 'eventosPorMes']);
    Route::get('/relatorios/eventos-por-promotor', [GraficoController::class, 'eventosPorPromotor']);
    Route::get('/relatorios/eventos-com-vagas', [GraficoController::class, 'eventosComVagas']);

});
