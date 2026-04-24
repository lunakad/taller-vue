<?php

use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/sistemas/{sistema}/reportes', [ReporteController::class, 'index'])
        ->middleware('acceso.sistema')
        ->name('reportes.index');

    Route::get('/sistemas/{sistema}/reportes/{reporte}/exportar', [ReporteController::class, 'exportar'])
        ->middleware('acceso.sistema')
        ->name('reportes.exportar');
});
