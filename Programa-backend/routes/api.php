<?php
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\ExcelController;
use App\Http\Controllers\Api\IniciarController;
use App\Http\Controllers\Api\Archivologueo;
use App\Http\Controllers\Api\NuevosController;
use App\Http\Controllers\Api\EquipoUsuarios;
use App\Http\Controllers\Api\Huella;


Route::get('/iniciar', [IniciarController::class, 'index']);
Route::post('/registrar', [IniciarController::class, 'registrar']);
Route::post('/login', [IniciarController::class, 'login']);
Route::post('/logout', [IniciarController::class, 'logout']);
Route::post('/verificar', [IniciarController::class, 'verificarAutenticacion']);
Route::get('/historial-inicios', [IniciarController::class, 'historialInicios']);
Route::get('/sesiones-activas', [IniciarController::class, 'sesionesActivas']);

Route::get('/usuarios', [UsuarioController::class,'Listar']);
Route::get('/usuarios/{id}',[UsuarioController::class,'UsuarioPorId']);
Route::get('/usuarios/buscar/{cartera}',[UsuarioController::class,'BuscarPorCartera']);
Route::post('/usuarios',[UsuarioController::class,'Registrar']);
Route::put('/usuarios/{id}',[UsuarioController::class,'Actualizar']);
Route::delete('/usuarios/{id}',[UsuarioController::class,'Eliminar']);
Route::get('/usuarios/cedula/{cedula}', [UsuarioController::class, 'obtenerPorCedula']);
Route::put('/usuarios/cedula/{cedula}', [UsuarioController::class, 'actualizarPorCedula']);

//Equipos CRUD
Route::get('/equipos', [EquipoUsuarios::class,'Listar']);
Route::get('/equipos/{id}',[EquipoUsuarios::class,'BuscarId']);
Route::get('/equipo/{id}', [EquipoUsuarios::class, 'Verificar']);
Route::post('/equipos',[EquipoUsuarios::class,'Registrar']);
Route::put('/equipos/{id}',[EquipoUsuarios::class,'Actualizar']);
Route::delete('/equipos/{id}',[EquipoUsuarios::class,'Eliminar']);
Route::post('/procesar-excel', [ExcelController::class, 'procesar']);

//Huella CRUD
Route::get('/huella',[Huella::class, 'Listar']);
Route::get('/huella/{id}',[Huella::class, 'BuscarId']);
Route::get('/huellas/{id}', [Huella::class, 'Verificar']);
Route::post('/huella',[Huella::class, 'Registrar']);
Route::put('/huella/{id}',[Huella::class, 'Actualizar']);
Route::delete('/huella/{id}',[Huella::class, 'Eliminar']);

Route::post('/archivologueo/subir', [Archivologueo::class, 'subir']);

Route::post('/gestiones-nuevos', [NuevosController::class, 'gestionesNuevos']);





