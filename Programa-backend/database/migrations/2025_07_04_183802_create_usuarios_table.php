<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->integer('cedula');
            $table->string('telefono');
            $table->string('cartera');
            $table->string('numero_equipo');
            $table->string('usuario_equipo');
            $table->string('clave_equipo');
            $table->string('usuario_huella');
            $table->string('clave_huella');
            $table->string('correo');            
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
