<?php

namespace App\Http\Controllers\api;

use App\Models\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class UsuarioController extends Controller
{
    public function Listar(){
        
        $usuario = Usuario::with(['equipoUsuario', 'huella', 'best'])->get();
        return response()->json($usuario);
    }
    
    public function UsuarioPorId($id){
        $usuario = Usuario::find($id);

        if (!$usuario){
            return response()->json([
                'estado' => 404,
                'mensaje' => 'Usuario no encontrado'
            ],404);
        }else{
            return response()->json([
                'estado' => 200,
                'usuario'=>$usuario,
            ],200);
        }
    }
    
    public function Registrar(Request $request){
        try {
            $validated = $request->validate([
                'nombres'=> 'required',
                'apellidos' => 'required',
                'cedula' => 'required|unique:usuarios,cedula', 
                'telefono' => 'required',
                'cartera' => 'required',
                'numero_equipo' => 'required',
                'equipo_usuario' => 'nullable|exists:equipo_usuarios,id',
                'huella' => 'nullable|exists:huellas,id',
                'best' => 'nullable|exists:usuarios_bests,id', 
                'correo' => 'required|email|unique:usuarios,correo',
                'no_diadema'=> 'nullable',
                'almuerzo' => 'nullable'
            ]);
            
            $usuario = Usuario::create($validated);
            return response()->json([
                'mensaje'=>'Usuario registrado correctamente',
                'estado'=>201, 
                'usuario'=>$usuario,
            ],201);

        } catch (ValidationException $e) {
            return response()->json([
                'mensaje' => 'Error de validación',
                'errores' => $e->errors()
            ], 422); 
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error interno del servidor al registrar: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function Actualizar(Request $request, $id){
        try {
            $usuario = Usuario::find($id);
            
            if (!$usuario){
                return response()->json([
                    'estado' => 404,
                    'mensaje' => 'Usuario no encontrado'
                ],404);
            }
            
            $validar = $request->validate([
                'nombres'=> 'required',
                'apellidos' => 'required',
                'cedula' => 'required|unique:usuarios,cedula,' . $usuario->id, 
                'telefono' => 'required',
                'cartera' => 'required',
                'numero_equipo' => 'required',
                'equipo_usuario' => 'nullable|exists:equipo_usuarios,id',
                'huella' => 'nullable|exists:huellas,id',
                'best' => 'nullable|exists:usuarios_bests,id',
                'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id, 
                'no_diadema' => 'nullable',
                'almuerzo' => 'nullable',
            ]);
            
            $usuario->update($validar);
            
            
            return response()->json([
                'mensaje'=>'Usuario actualizado correctamente',
                'estado'=>200,
                'usuario' => $usuario,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'mensaje' => 'Error de validación',
                'errores' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error interno del servidor al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function Eliminar($id){
        $usuario = Usuario::Find($id);
        if (!$usuario){
            return response()->json([
                'mensaje'=>'Usuario no enontrado',
                'estado'=>'404'
            ],404);
        }else{
            $usuario->delete();
            return response()->json([
                'mensaje'=>'Usuario eliminado correctamente',
                'estado'=>'200'
            ],200);
        }
    }

    public function BuscarPorCartera($cartera){
        $usuario = Usuario::where('cartera',$cartera)->get();
        if ($usuario->isEmpty()){
            return response()->json([
                'mensaje' =>'Usuario no encontrado para la cartera: '.$cartera,
                'estado' => '404',
            ],404);
        }else{
            return response()->json([
                'usuario' => $usuario,
                'estado' => '200',
            ],200);
        }
    }

    public function obtenerPorCedula($cedula){
        $usuario = Usuario::where('cedula', $cedula)->first();

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no encontrado con esa cédula',
                'status' => 404
            ], 404);
        }

        return response()->json(['usuario' => $usuario], 200);
    }

    public function actualizarPorCedula(Request $request, $cedula){
        try {
            $usuario = Usuario::where('cedula', $cedula)->first();

            if (!$usuario) {
                return response()->json(['mensaje' => 'Usuario no encontrado con esa cédula'], 404);
            }
            
            $validated = $request->validate([
                'nombres'=> 'required',
                'apellidos' => 'required',
                'cedula' => 'required|unique:usuarios,cedula,' . $usuario->id,
                'telefono' => 'required',
                'cartera' => 'required',
                'numero_equipo' => 'required',
                'equipo_usuario' => 'nullable|exists:equipo_usuarios,id',
                'huella' => 'nullable|exists:huellas,id',
                'best' => 'nullable|exists:usuarios_bests,id',
                'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
                'no_diadema'=> 'nullable',
                'almuerzo' => 'nullable'
            ]);

            $usuario->update($validated);

            return response()->json([
                'mensaje' => 'Usuario actualizado correctamente',
                'usuario' => $usuario
            ], 200);
            
        } catch (ValidationException $e) {
            return response()->json([
                'mensaje' => 'Error de validación',
                'errores' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'mensaje' => 'Error interno del servidor'], 500);
        }
    }
    
}