<?php

use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'acceso.sistema'])->group(function () {
    Route::get('/sistemas/{sistema}/reportes', [ReporteController::class, 'index']);
    Route::get('/sistemas/{sistema}/reportes/{reporte}/exportar', [ReporteController::class, 'exportar']);
});
