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
        Schema::table('atractivos', function (Blueprint $table) {
            $table->string('descripcion',3000)->nullable()->change();
            $table->string('recomendaciones',3000)->nullable()->change();
            $table->string('tipo_acceso',3000)->nullable()->change();
        });
        Schema::table('delegaciones', function (Blueprint $table) {
            $table->integer('orden')->nullable();
        });
        Schema::table('categorias_turismo', function (Blueprint $table) {
            $table->integer('orden')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
