<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'telefono',
        'cartera',
        'numero_equipo',
        'equipo_usuario',
        'huella', // id de la tabla huellas
        'correo',
        'usuario_bestvoiper',
        'extension',
    ];

    public function equipoUsuario()
    {
        return $this->belongsTo(EquipoUsuario::class,'equipo_usuario');
    }

    public function huellaRelacion()
    {
        return $this->belongsTo(Huella::class, 'huella', 'id');
    }
}
