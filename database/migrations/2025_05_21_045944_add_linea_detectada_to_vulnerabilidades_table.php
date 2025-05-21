<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vulnerabilidades', function (Blueprint $table) {
            $table->integer('linea_detectada')->nullable();
            $table->text('fragmento_detectado')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('vulnerabilidades', function (Blueprint $table) {
            $table->dropColumn(['linea_detectada', 'fragmento_detectado']);
        });
    }
    
};
