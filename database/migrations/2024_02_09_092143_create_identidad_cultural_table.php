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
        Schema::create('identidad_cultural', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion',3000);
            $table->string('recomendaciones',3000)->nullable();
            $table->string('historia',5000)->nullable();
            $table->string('leyenda',5000)->nullable();
            $table->string('cover_path');
            $table->string('principal_path');

            $table->unsignedBigInteger('id_delegacion');
            $table->foreign('id_delegacion')->references('id')->on('delegaciones');

            $table->timestamp('validado')->nullable();
            $table->string('slug')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identidad_cultural');
    }
};
