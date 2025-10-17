<?php
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\ExcelController;
use App\Http\Controllers\Api\IniciarController;
use App\Http\Controllers\Api\Archivologueo;
use App\Http\Controllers\Api\NuevosController;


Route::get('/iniciar', [IniciarController::class, 'index']);
Route::post('/registrar', [IniciarController::class, 'registrar']);
Route::post('/login', [IniciarController::class, 'login']);
Route::post('/logout', [IniciarController::class, 'logout']);
Route::post('/verificar', [IniciarController::class, 'verificarAutenticacion']);
Route::get('/historial-inicios', [IniciarController::class, 'historialInicios']);
Route::get('/sesiones-activas', [IniciarController::class, 'sesionesActivas']);

Route::apiResource('/usuarios', UsuarioController::class);
Route::get('/usuarios/cedula/{cedula}', [UsuarioController::class, 'obtenerPorCedula']);
Route::put('/usuarios/cedula/{cedula}', [UsuarioController::class, 'actualizarPorCedula']);
Route::get('/usuario/carteras', [UsuarioController::class, 'carteras']);

Route::post('/procesar-excel', [ExcelController::class, 'procesar']);

Route::post('/archivologueo/subir', [Archivologueo::class, 'subir']);

Route::post('/gestiones-nuevos', [NuevosController::class, 'gestionesNuevos']);





