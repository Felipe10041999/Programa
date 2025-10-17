<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EquipoUsuario;

class EquipoUsuarios extends Controller{    
    
    public function Listar(){
        $equipoUsuarios = EquipoUsuario::all();
        if ($equipoUsuarios->isEmpty()){
            return response()->json([
                'estado'=> 400,
                'mensaje' => 'No se encontraron usuarios'
            ],400);
        }else{
            return response()->json ($equipoUsuarios,200);
        }
    }

    public function BuscarId($id){
        $equipoUsuario = EquipoUsuario::find($id);
        if (!$equipoUsuario){
            return response()->json([
                'mensaje' => 'Usuario no encontrado',
                'estado'=> 404
            ],404);
        }
            return response()->json([
                'usuario' => $equipoUsuario,
                'estado' => 200
            ],200);
    }

    public function Verificar($id) {
        $equipoUsuario = EquipoUsuario::find($id);
        if (is_null($equipoUsuario)) {
            return response()->json([
                'asignado' => false,
                'mensaje' => 'Equipo usuario no encontrado'
            ], 404);
        }
        $tieneUsuario = $equipoUsuario->usuarios()->exists();
        return response()->json([
            'asignado' => $tieneUsuario
        ], 200);
    }
    
    public function Registrar(Request $request){
        $validated = $request->validate([
            'usuario'=> 'required',
            'clave'=>'required',
        ]);
        $equipoUsuario = EquipoUsuario::create($validated);
        return response()->json([
            'mensaje'=>'Equipo usuario registrado correctamente',
            'estado'=>200,
            'usuario'=>$equipoUsuario,
        ],200);
    }

    public function Actualizar(Request $request, $id){
        $equipoUsuario = EquipoUsuario::find($id);
        if (is_null($equipoUsuario)){
            return response()->json([
                'estado'=>400,
                'mensaje' => 'Equipo usuario no encontrado'
            ],400);
        }else{
            $validated = $request->validate([
                'usuario'=>'required',
                'clave' => 'required',
            ]);
            $equipoUsuario->update($validated);
            return response()->json([
                'mensaje' => 'Equipo usuario actualizado correctamente',
                'estado' => 200,
                'usuario' => $equipoUsuario,
            ],200);
        }
    }
    
    public function Eliminar($id){
       $equipoUsuario = EquipoUsuario::find($id);
       if (is_null ($equipoUsuario)){
        return response()->json ([
            'estado' => 400,
            'mensaje' => 'Equipo usuario no encontrado'        
        ],400);
       }else{
        $equipoUsuario->delete();
        return response()->json ([
            'mensaje' => 'Equipo usuario elimindao correctamente',
            'estado' => 200,
        ],200);
       }
    }
}
