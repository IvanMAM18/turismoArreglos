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
            $table->string('leyenda')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('principal_path')->nullable();
            $table->string('descripcion',500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delegaciones', function (Blueprint $table) {
            $table->dropColumn('leyenda');
            $table->dropColumn('descripcion');
            $table->dropColumn('cover_path');
            $table->dropColumn('principal_path');
        });
    }
};
