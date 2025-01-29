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
        Schema::create('negocios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->float('latitud');
            $table->float('longitud');
            $table->string('descripcion',2000);
            $table->string('redes_sociales',500);
            $table->string('paginaweb');
            $table->string('contacto_telefono')->nullable();
            $table->string('contacto_persona')->nullable();
            $table->string('contacto_correo')->nullable();
            $table->string('contacto_puesto')->nullable();
            $table->integer('id_comercio');
            $table->string('cover_path');
            $table->string('principal_path');

            $table->timestamp('validado')->nullable();
            $table->string('slug')->nullable();

            $table->unsignedBigInteger('id_delegacion');
            $table->foreign('id_delegacion')->references('id')->on('delegaciones');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('negocios_fotos', function (Blueprint $table) {
            $table->id();
            $table->string('path');

            $table->unsignedBigInteger('id_negocio');
            $table->foreign('id_negocio')->references('id')->on('negocios');
            
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('categorias_negocio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug');
            $table->string('icon_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('negocios_categorias', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_negocio');
            $table->foreign('id_negocio')->references('id')->on('negocios');

            $table->unsignedBigInteger('id_categoria_negocio');
            $table->foreign('id_categoria_negocio')->references('id')->on('categorias_negocio');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('negocios_categorias');
        Schema::drop('categorias_negocio');
        Schema::drop('negocios_fotos');
        Schema::drop('negocios');
    }

};
