<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iniciar extends Model
{
    protected $fillable = [
        'nombre_usuario',
        'contrasena',
        'usuario_id',
        'token_sesion',
        'ultimo_acceso',
        'estado_sesion'
    ];

    protected $casts = [
        'ultimo_acceso' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function isActive()
    {
        return $this->estado_sesion === 'activa' && $this->token_sesion !== null;
    }

    public function isExpired()
    {
        if (!$this->ultimo_acceso) {
            return true;
        }
        
        return $this->ultimo_acceso->diffInHours(now()) > 24;
    }

    public function markAsExpired()
    {
        $this->update([
            'estado_sesion' => 'expirada',
            'token_sesion' => null
        ]);
    }
}
