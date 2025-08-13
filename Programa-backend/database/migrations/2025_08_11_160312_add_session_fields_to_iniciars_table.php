<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('iniciars', function (Blueprint $table) {
            // Hacer usuario_id nullable para permitir sesiones independientes
            $table->foreignId('usuario_id')->nullable()->change();
            
            // Agregar nuevos campos para gestión de sesiones
            $table->string('token_sesion', 100)->nullable()->unique()->after('usuario_id');
            $table->timestamp('ultimo_acceso')->nullable()->after('token_sesion');
            $table->enum('estado_sesion', ['activa', 'cerrada', 'expirada'])->default('activa')->after('ultimo_acceso');
            
            // Agregar índices para mejor rendimiento
            $table->index(['token_sesion', 'estado_sesion']);
            $table->index('ultimo_acceso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iniciars', function (Blueprint $table) {
            // Revertir cambios
            $table->foreignId('usuario_id')->nullable(false)->change();
            $table->dropColumn(['token_sesion', 'ultimo_acceso', 'estado_sesion']);
            $table->dropIndex(['token_sesion', 'estado_sesion']);
            $table->dropIndex(['ultimo_acceso']);
        });
    }
};
