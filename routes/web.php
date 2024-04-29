<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemandaController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/demandas', [DemandaController::class, 'index'])->name('demandas.index');
    Route::get('/listado', [DemandaController::class, 'listado'])->name('demandas.listado');
    Route::get('/demanda/{demanda}', [DemandaController::class, 'demanda'])->name('demandas.show');
    Route::get('/demanda/{demanda}', [DemandaController::class, 'demanda'])->name('listado.show');
    
    
    Route::get('/listado/TiposImporte', [DemandaController::class, 'TiposImporte'])->name('demanda.tiposImporte');
    Route::get('/listado/TiposPagos', [DemandaController::class, 'TiposPagos'])->name('demanda.tiposPagos');
    Route::get('/listado/Bancos', [DemandaController::class, 'Bancos'])->name('demanda.bancos');
    Route::get('/listado/Trabajadores', [DemandaController::class, 'Trabajadores'])->name('demanda.Trabajadores');
    
    Route::post('/demanda/create', [DemandaController::class, 'store'])->name('demanda.create');
    Route::post('/demanda/update', [DemandaController::class, 'updateDemanda'])->name('demanda.update');
    Route::delete('/demanda/{id}/delete', [DemandaController::class, 'delete'])->name('demanda.delete');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');