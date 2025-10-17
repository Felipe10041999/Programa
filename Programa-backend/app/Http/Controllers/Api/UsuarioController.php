<?php

namespace App\Http\Controllers\Api;

use App\Models\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Usuario::paginate(100);
        if ($usuario->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron usuarios',
                'status' => 200
            ], 200); 
        }
        return response()->json($usuario, 200);
    }  
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'required',
            'telefono' => 'required',
            'cartera' => 'required',
            'numero_equipo' => 'required',
            'usuario_equipo' => 'required',
            'clave_equipo' => 'required',
            'usuario_huella' => 'required',
            'clave_huella' => 'required',
            'correo' => 'required|email',
            'nombre_usuario_huella' => 'required',
            'usuario_bestvoiper' => 'nullable',
            'extension' => 'required|string|min:2',
        ]);
        
        
        if (!isset($validated['usuario_bestvoiper'])) {
            $validated['usuario_bestvoiper'] = 'ninguno';
        }
        $usuario = Usuario::create($validated);
        return response()->json(['mensaje'=>'Usuario creado correctamente', 'usuario'=> $usuario],201);
    }
    public function show($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no encontrado',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'usuario' => $usuario,
            'status' => 200
        ], 200);
    }
    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuario::find($id);

            if (!$usuario) {
                return response()->json(['mensaje' => 'Usuario no encontrado'], 404);
            }

            $validated = $request->validate([
                'nombres' => 'required',
                'apellidos' => 'required',
                'cedula' => 'required',
                'telefono' => 'required',
                'cartera' => 'required',
                'numero_equipo' => 'required',
                'usuario_equipo' => 'required',
                'clave_equipo' => 'required',
                'usuario_huella' => 'required',
                'clave_huella' => 'required',
                'correo' => 'required|email',
                'nombre_usuario_huella' => 'required',
                'usuario_bestvoiper' => 'nullable',
                'extension' => 'required|string|min:2',
            ]);

            $usuario->update($validated);

            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
                $usuario->save();
            }

            return response()->json([
                'mensaje' => 'Usuario actualizado correctamente',
                'usuario' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no encontrado',
                'status' => 404
            ], 404);
        }
        $usuario->delete();

        return response()->json([
            'mensaje' => 'Usuario eliminado correctamente',
            'status' => 200
        ]);
    }
    public function carteras()
    {
        $carteras = Usuario::query()->whereNotNull('cartera')->where('cartera', '!=', '')->distinct()->pluck('cartera');
        return response()->json(['carteras' => $carteras], 200);
    }
    public function obtenerPorCedula($cedula)
{
    $usuario = Usuario::where('cedula', $cedula)->first();

    if (!$usuario) {
        return response()->json([
            'mensaje' => 'Usuario no encontrado con esa cédula',
            'status' => 404
        ], 404);
    }

    return response()->json(['usuario' => $usuario], 200);
}

// Actualizar usuario por cédula
public function actualizarPorCedula(Request $request, $cedula)
{
    try {
        $usuario = Usuario::where('cedula', $cedula)->first();

        if (!$usuario) {
            return response()->json(['mensaje' => 'Usuario no encontrado con esa cédula'], 404);
        }

        $validated = $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'required',
            'telefono' => 'required',
            'cartera' => 'required',
            'numero_equipo' => 'required',
            'usuario_equipo' => 'required',
            'clave_equipo' => 'required',
            'usuario_huella' => 'required',
            'clave_huella' => 'required',
            'correo' => 'required|email',
            'nombre_usuario_huella' => 'required',
            'usuario_bestvoiper' => 'nullable',
            'extension' => 'required|string|min:2',
        ]);

        $usuario->update($validated);

        return response()->json([
            'mensaje' => 'Usuario actualizado correctamente',
            'usuario' => $usuario
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
