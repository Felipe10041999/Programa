<?php

namespace App\Http\Controllers\Api;

use App\Models\Iniciar;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class IniciarController extends Controller
{
    /**
     * Registrar un nuevo usuario
     */
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'required|string|min:3|max:50|unique:iniciars,nombre_usuario',
            'contrasena' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de validación incorrectos',
                'errores' => $validator->errors(),
                'status' => 422
            ], 422);
        }

        try {
            $usuario = new Iniciar();
            $usuario->nombre_usuario = $request->nombre_usuario;
            $usuario->contrasena = Hash::make($request->contrasena);
            $usuario->estado_sesion = 'activa';
            $usuario->token_sesion = null;
            $usuario->ultimo_acceso = now();
            $usuario->save();

            return response()->json([
                'mensaje' => 'Usuario registrado exitosamente',
                'usuario' => [
                    'id' => $usuario->id,
                    'nombre_usuario' => $usuario->nombre_usuario,
                    'estado_sesion' => $usuario->estado_sesion,
                    'ultimo_acceso' => $usuario->ultimo_acceso
                ],
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al registrar usuario',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'required|string|min:3|max:50',
            'contrasena' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de validación incorrectos',
                'errores' => $validator->errors(),
                'status' => 422
            ], 422);
        }

        try {
            // Buscar usuario por nombre de usuario
            $inicioSesion = Iniciar::where('nombre_usuario', $request->nombre_usuario)->first();

            if (!$inicioSesion) {
                return response()->json([
                    'mensaje' => 'Usuario no encontrado',
                    'status' => 401
                ], 401);
            }

            // Verificar contraseña
            if (!Hash::check($request->contrasena, $inicioSesion->contrasena)) {
                return response()->json([
                    'mensaje' => 'Contraseña incorrecta',
                    'status' => 401
                ], 401);
            }



            // Generar nuevo token y actualizar solo el token y último acceso (no cambiar estado_sesion)
            $inicioSesion->update([
                'token_sesion' => Str::random(60),
                'ultimo_acceso' => now()
            ]);

            return response()->json([
                'mensaje' => 'Inicio de sesión exitoso',
                'sesion' => [
                    'id' => $inicioSesion->id,
                    'nombre_usuario' => $inicioSesion->nombre_usuario,
                    'token_sesion' => $inicioSesion->token_sesion,
                    'ultimo_acceso' => $inicioSesion->ultimo_acceso,
                    'estado_sesion' => $inicioSesion->estado_sesion
                ],
                'status' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error en el servidor',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Cerrar sesión de usuario
     */
    public function logout(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token_sesion' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'mensaje' => 'Token de sesión requerido',
                    'status' => 422
                ], 422);
            }

            // Buscar y cerrar la sesión por token
            $inicioSesion = Iniciar::where('token_sesion', $request->token_sesion)
                                  ->first();

            if (!$inicioSesion) {
                return response()->json([
                    'mensaje' => 'Sesión no encontrada o ya cerrada',
                    'status' => 404
                ], 404);
            }

            // Marcar sesión como cerrada
            $inicioSesion->update([
                'estado_sesion' => 'activa',
                'token_sesion' => null
            ]);

            return response()->json([
                'mensaje' => 'Sesión cerrada exitosamente',
                'status' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al cerrar sesión',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Verificar autenticación por token de sesión
     */
    public function verificarAutenticacion(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token_sesion' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'mensaje' => 'Token de sesión requerido',
                    'status' => 422
                ], 422);
            }

            // Verificar si la sesión está activa
            $inicioSesion = Iniciar::where('token_sesion', $request->token_sesion)
                                  ->where('estado_sesion', 'activa')
                                  ->first();

            if (!$inicioSesion) {
                return response()->json([
                    'mensaje' => 'Sesión no válida o expirada',
                    'status' => 401
                ], 401);
            }

            // Actualizar último acceso
            $inicioSesion->update(['ultimo_acceso' => now()]);

            return response()->json([
                'mensaje' => 'Sesión válida',
                'sesion' => [
                    'id' => $inicioSesion->id,
                    'nombre_usuario' => $inicioSesion->nombre_usuario,
                    'ultimo_acceso' => $inicioSesion->ultimo_acceso,
                    'estado_sesion' => $inicioSesion->estado_sesion
                ],
                'status' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al verificar autenticación',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Obtener historial de inicios de sesión
     */
    public function historialInicios(Request $request)
    {
        try {
            $inicios = Iniciar::select('id', 'nombre_usuario', 'ultimo_acceso', 'estado_sesion', 'created_at')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            if ($inicios->isEmpty()) {
                return response()->json([
                    'mensaje' => 'No se encontraron registros de inicio de sesión',
                    'status' => 200
                ], 200);
            }

            return response()->json([
                'inicios' => $inicios,
                'status' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener historial',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Obtener sesiones activas
     */
    public function sesionesActivas(Request $request)
    {
        try {
            $sesionesActivas = Iniciar::select('id', 'nombre_usuario', 'ultimo_acceso', 'created_at')
                ->where('estado_sesion', 'activa')
                ->orderBy('ultimo_acceso', 'desc')
                ->get();

            return response()->json([
                'sesiones_activas' => $sesionesActivas,
                'total_sesiones' => $sesionesActivas->count(),
                'status' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener sesiones activas',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Método index para mostrar información básica
     */
    public function index(Request $request)
    {
        return response()->json([
            'mensaje' => 'API de Autenticación Independiente',
            'descripcion' => 'Sistema de autenticación que no requiere usuarios registrados',
            'endpoints_disponibles' => [
                'POST /login' => 'Iniciar sesión (solo nombre de usuario)',
                'POST /logout' => 'Cerrar sesión (requiere token_sesion)',
                'POST /verificar' => 'Verificar autenticación (requiere token_sesion)',
                'GET /historial' => 'Historial de inicios de sesión',
                'GET /sesiones-activas' => 'Obtener sesiones activas'
            ],
            'status' => 200
        ], 200);
    }
}
