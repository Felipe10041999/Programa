<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuarios_best extends Model
{
    protected $fillable = [
        'nombre_usuario',
        'extension',
        'usuario',
        'clave'
    ];
    
    public function usuario(){
        return $this->hasMany(Usuario::class, 'best');
    }
    public function usuariosMostrar(){
        return $this->hasMany(Usuario::class, 'best', 'id');
    }
}
