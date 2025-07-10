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
        'usuario_equipo',
        'clave_equipo',
        'usuario_huella',
        'clave_huella',
        'correo',
        'nombre_usuario_huella',
    ];
}
