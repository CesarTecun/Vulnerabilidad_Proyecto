<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patrones_vulnerabilidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('regex');
            $table->enum('criticidad', ['Alta', 'Media', 'Baja']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrones_vulnerabilidad');
    }
};
