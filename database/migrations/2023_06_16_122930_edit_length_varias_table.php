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
        Schema::table('delegaciones', function (Blueprint $table) {
            $table->string('descripcion',2000)->nullable()->change();
        });

        Schema::table('atractivos', function (Blueprint $table) {
            $table->string('descripcion',2000)->change();
            $table->string('historia',2500)->change();
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
