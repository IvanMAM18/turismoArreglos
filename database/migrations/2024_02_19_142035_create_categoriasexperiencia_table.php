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
        Schema::create('categorias_experiencia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('negocios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_categoria_experiencia')->nullable();
            $table->foreign('id_categoria_experiencia')->references('id')->on('categorias_experiencia');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias_experiencia');
    }
};
