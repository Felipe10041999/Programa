<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios_best as Best;
use App\Models\Usuario; 

class Usuarios_best extends Controller
{

    public function Listar(){
        $best = Best::all();
        if($best->isEmpty()){
            return response()->json([
                'estado'=>400,
                'mensaje'=> 'Usuarios no encontrados'
            ],400);
        }
        return response()->json($best,200);
    }
    
    public function BuscarId($id){
        $best = Best::find($id);
        if(is_null($best)){
            return response()->json([
                'mensaje' => 'Usuario con el id ingresado no encontrado',
                'estado' => 404,
            ],404);
        }
        return response()->json([
            'usuario' => $best,
            'estado' => 200
        ],200);
    }
        
    public function Verificar($id){
        $best =Best::find($id);
        if (is_null($best)){
            return response()->json([
                'asignado' => false,
                'mensaje' => 'Usuario no encontrado'
            ], 404);
        }
        $tieneusuario = $best->usuario()->exists();
        return response()->json ([
            'asignado' => $tieneusuario
        ], 200);
    }

    public function Registrar(Request $request){
        $validated = $request->validate([
            'nombre_usuario' => 'required',
            'extension' => 'required',
            'usuario' => 'required',
            'clave' => 'required'
        ]);
        $best = Best::create($validated);
        return response()->json([
            'mensaje' => 'Usuario de bestVoIper registrado correctamente',
            'estado' => 200,
            'usuario' => $best
        ],200);
    }
    public function Actualizar(Request $request, $id){
        $best = Best::find($id);
        if(is_null($best)){
            return response()->json([
                'estado'=>400,
                'mensaje' => 'El usuario bestvoiper no se encunentra en los registros'
            ],404);
        }else{
            $validated = $request->validate([
                'nombre_usuario' => 'required',
                'extension' => 'required',
                'usuario' => 'required',
                'clave' => 'required'
            ]);
            $best->update($validated);
            return response()->json([
                'estado' => 200,
                'mensaje' => 'El usuario bestvoiper fue actualizado correctamente',
                'usuario' => $best
            ], 200);
        }
    }
    public function Eliminar($id){
        $best = Best::find($id);
        if(is_null($best)){
            return response()->json([
                'estado'=> 400,
                'mensaje'=>'usuario con el id ingresado no encontrado'
            ],400);
        }else{
            $best->delete();
            return response()->json([
                'estado' =>200,
                'mensaje' => 'Usuario elimindado correctamente'
            ],200);
        }
    }
}
