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
        Schema::create('iniciars', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_usuario')->unique();
            $table->string('contrasena');
            $table->unsignedBigInteger('usuario_id')->nullable(); // Sin clave foránea, nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iniciars');
    }
};
