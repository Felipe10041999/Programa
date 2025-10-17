<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Huella as ModelHuella;
use App\Models\Usuario;

class Huella extends Controller{

    public function Listar(){
        $huella = ModelHuella::all();
        if($huella->isEmpty()){
            return response()->json([
                'estado'=> 400,
                'mensaje' => 'Usuarios no encontrados'
            ],400); 
        }
            return response()->json($huella,200);
    }

    public function BuscarId($id){
        $huella = ModelHuella::find($id);
        if(is_null($huella)){
            return response()->json([
                'mensaje' => 'No se encontro ningun usuario con ese id',
                'estado' => 404,                
            ],404);
        }
            return response()->json([
                'usuario' => $huella,
                'estado'=> 200,
            ],200);
    }

    public function Verificar($id) {
        $huella = ModelHuella::find($id);
        if (is_null($huella)) {
            return response()->json([
                'asignado' => false,
                'mensaje' => 'Huella no encontrada'
            ], 404);
        }
        $tieneUsuario = $huella->usuario()->exists();
        return response()->json([
            'asignado' => $tieneUsuario
        ], 200);
    }

    public function Registrar(Request $request){
        $validated =$request->validate([
            'usuario'=>'required',
            'clave'=> 'required',
            'nombre_usuario'=>'required'
        ]);
        $huella = ModelHuella::create($validated);
        return response()->json([
            'mensaje'=>'Usuario huella resgistrado',
            'estado'=> 200,
            'usuario'=> $huella
        ],200);
    }
    public function Actualizar(Request $request, $id){
        $huella = ModelHuella::find($id);
        if (is_null($huella)){
            return response()->json([
                'estado'=> 400,
                'mensaje'=>'El usuario de huella no se encuentra registrado'
            ],200);
        }else{
            $validated = $request->validate([
                'usuario'=> 'required',
                'clave'=>'required',
                'nombre_usuario'=>'required'
            ]);
            $huella->update($validated);
            return response()->json([
                'estado' => 200,
                'mensaje' => 'El usuario de huella fue actualizado',
                'usuario'=> $huella
            ],200);
        }
    }
    public function Eliminar($id){
        $huella = ModelHuella::find($id);
        if(is_null($huella)){
            return response()->json([
                'estado'=>400,
                'mensaje'=>'Usuario con ese id no encontrado'
            ],400);
        }else{
            $huella->delete();
            return response()->json([
                'estado'=>200,
                'mensaje'=>'Usuario eliminado correctamente'
            ],200);
        }
    }
}