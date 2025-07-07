<?php
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\UsuarioController;

// RUTAS PÚBLICAS

Route::apiResource('/usuario', UsuarioController::class);




    
    