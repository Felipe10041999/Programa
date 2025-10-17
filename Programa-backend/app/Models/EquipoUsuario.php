<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipoUsuario extends Model
{
    protected $fillable = [
        'usuario',
        'clave',
    ];
    public function usuarios()
    {
        return $this->hasMany(Usuario::class,'equipo_usuario');
    }
}
