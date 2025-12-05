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
        'huella', 
        'best',
        'correo',
        'no_diadema',
        'almuerzo'
    ];

    public function equipoUsuario()
    {
        return $this->belongsTo(EquipoUsuario::class,'equipo_usuario');
    }
    public function huella()
    {
        return $this->belongsTo(Huella::class,'huella');
    }

    public function huellaRelacion()
    {
        return $this->belongsTo(Huella::class, 'huella', 'id');
    }
    public function best()
    {
        return $this->belongsTo(Usuarios_best::class, 'best');
    }
    public function bestRelacion()
    {
        return $this->belongsTo(Usuarios_best::class, 'best', 'id');
    }
    public function getExtensionAttribute()
    {
        return $this->bestRelacion->extension ?? null;
    }
}