<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        Usuario::create([
            'nombres' => 'Felipe',
            'apellidos' => 'Mosquera',
            'cedula' => '123456789',
            'telefono' => '30012345',
            'cartera' => 'Cartera Norte',
            'numero_equipo' => 'EQ123',
            'usuario_equipo' => 'fmosquera',
            'clave_equipo' => 'clave123',
            'usuario_huella' => 'fmosq',
            'clave_huella' => '123456',
            'correo' => 'felipe@ejemplo.com',
        ]);
    }
}
