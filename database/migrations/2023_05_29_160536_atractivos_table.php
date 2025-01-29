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
        Schema::create('atractivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->float('latitud');
            $table->float('longitud');
            $table->string('descripcion',500);
            $table->string('horarios');
            $table->string('tipo_acceso');
            $table->string('recomendaciones',1000);
            $table->string('historia',1000);
            $table->string('leyenda');
            $table->string('cover_path');
            $table->string('principal_path');

            $table->unsignedBigInteger('id_delegacion');
            $table->foreign('id_delegacion')->references('id')->on('delegaciones');
            $table->unsignedBigInteger('id_categoria');
            $table->foreign('id_categoria')->references('id')->on('categorias_turismo');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('atractivos');
    }
};
