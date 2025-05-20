<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vulnerabilidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('componente_afectado');
            $table->enum('criticidad', ['Alta', 'Media', 'Baja']);
            $table->enum('estado', ['Detectada', 'En evaluación', 'Corregida', 'En cuarentena', 'Falso positivo'])->default('Detectada');
            $table->date('fecha_deteccion')->nullable();
            $table->float('cvss')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vulnerabilidades');
    }
};
