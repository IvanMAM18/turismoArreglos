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
        Schema::create('bazar', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('nombre');
            $table->string('nombre_contacto');
            $table->string('puesto_contacto');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('estado');
            $table->string('pais');
            $table->string('cp');
            $table->string('telefono_contacto');
            $table->string('correo');
            $table->string('sitio_web');
            $table->string('tipo_participante');
            $table->string('giro_empresa')->nullable();
            $table->string('tipo_exposicion')->nullable();
            $table->string('expo_nombre')->nullable();
            $table->string('expo_correo')->nullable();
            $table->string('expo_puesto')->nullable();
            $table->string('expo_materiales')->nullable();
            $table->string('negocio_descripcion',500)->nullable();
            $table->string('negocio_tipo_venta')->nullable();
            $table->string('negocio_tipo_empresa')->nullable();
            $table->string('negocio_servicios')->nullable();
            $table->string('negocio_asociacion',500)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bazar');
    }
};
