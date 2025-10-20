<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Huella extends Model
{
    protected $fillable =[
        'usuario',
        'clave',
        'nombre_usuario',
    ];
    public function usuario(){
        return $this->hasMany(Usuario::class,'huella', 'id');
    }
}
