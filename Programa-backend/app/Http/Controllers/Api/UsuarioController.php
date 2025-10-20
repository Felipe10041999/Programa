<?php

namespace App\Http\Controllers\api;

use App\Models\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class UsuarioController extends Controller
{
    public function Listar(){
        $usuario = Usuario::with(['equipoUsuario', 'Huella'])->get();
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
        $validated = $request->validate([
        'nombres'=> 'required',
        'apellidos' => 'required',
        'cedula' => 'required',
        'telefono' => 'required',
        'cartera' => 'required',
        'numero_equipo' => 'required',
        'equipo_usuario' => 'required|exists:equipo_usuarios,id',
        'huella' => 'required|exists:huellas,id',
        'correo' => 'required|email',
        'usuario_bestvoiper' => 'required',
        'extension' => 'required',
        'no_diadema' => 'required',

        ]);
        
        if (!isset($validated['usuario_bestvoiper'])){
            $validated['usuario_bestvoiper'] = 'Ninguno';
        }
        $usuario = Usuario::create($validated);
        return response()->json([
            'mensaje'=>'Usuario registrado correctamente',
            'estado'=>200,
            'usuario'=>$usuario,
        ],201);
    }
    public function Actualizar(Request $request, $id){
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
            'cedula' => 'required',
            'telefono' => 'required',
            'cartera' => 'required',
            'numero_equipo' => 'required',
            'equipo_usuario' => 'required|exists:equipo_usuarios,id',
            'huella' => 'required|exists:huellas,id',
            'correo' => 'required|email',
            'usuario_bestvoiper' => 'required',
            'extension' => 'required',
            'no_diadema' => 'required',

        ]);
        $usuario->update($validar);
        return response()->json([
            'mensaje'=>'Usuario actualizado correctamente',
            'estado'=>'200',
        ],200);
    }
    public function Eliminar($id){
        $usuario = Usuario::Find($id);
        if (!$usuario){
            return response()->json([
                'mensaje'=>'Usuario no enontrado',
                'estado'=>'404'
            ],404);
            }else{$usuario->delete();
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
                'mensaje' =>'Usuario no encontrado'.$cartera,
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
            'cedula' => 'required',
            'telefono' => 'required',
            'cartera' => 'required',
            'numero_equipo' => 'required',
            'equipo_usuario' => 'required|exists:equipo_usuarios,id',
            'huella' => 'required|exists:huellas,id',
            'correo' => 'required|email',
            'usuario_bestvoiper' => 'required',
            'extension' => 'required',
            'no_diadema' => 'required',

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
