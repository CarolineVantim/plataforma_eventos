<?php

use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('eventos', EventoController::class);
});
