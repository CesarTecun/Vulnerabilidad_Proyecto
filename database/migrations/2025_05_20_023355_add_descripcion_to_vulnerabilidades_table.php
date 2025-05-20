<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vulnerabilidades', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('componente_afectado');
        });
    }

    public function down(): void
    {
        Schema::table('vulnerabilidades', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });
    }
};
