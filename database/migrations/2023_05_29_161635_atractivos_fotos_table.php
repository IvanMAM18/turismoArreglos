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
        Schema::create('atractivos_fotos', function (Blueprint $table) {
            $table->id();
            $table->string('path');

            $table->unsignedBigInteger('id_atractivo');
            $table->foreign('id_atractivo')->references('id')->on('atractivos');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('atractivos_fotos');
    }
};
