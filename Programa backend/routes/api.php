<?php
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\ExcelController;
// RUTAS PÚBLICAS


Route::apiResource('/usuario', UsuarioController::class);

Route::post('/procesar-excel', [ExcelController::class, 'procesar']);




    
    