<?php
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\ExcelController;
use App\Http\Controllers\Api\IniciarController;
use App\Http\Controllers\Api\Archivologueo;

// RUTAS PÚBLICAS

// Información básica
Route::get('/iniciar', [IniciarController::class, 'index']);

// Registro y login
Route::post('/registrar', [IniciarController::class, 'registrar']);
Route::post('/login', [IniciarController::class, 'login']);

// RUTAS PROTEGIDAS (requieren autenticación)

// Si no usas sanctum, puedes dejar las rutas públicas para pruebas
Route::post('/logout', [IniciarController::class, 'logout']);
Route::post('/verificar', [IniciarController::class, 'verificarAutenticacion']);
Route::get('/historial-inicios', [IniciarController::class, 'historialInicios']);
Route::get('/sesiones-activas', [IniciarController::class, 'sesionesActivas']);

Route::apiResource('/usuario', UsuarioController::class);

Route::post('/procesar-excel', [ExcelController::class, 'procesar']);
Route::get('/usuario/carteras', [UsuarioController::class, 'carteras']);
Route::post('/archivologueo/subir', [Archivologueo::class, 'subir']);





