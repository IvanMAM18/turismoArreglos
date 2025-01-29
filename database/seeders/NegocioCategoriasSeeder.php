<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NegocioCategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\CategoriaNegocio::create([
            'nombre' => 'HOTELES',
            'slug' => 'hoteles',
        ]);
        \App\Models\CategoriaNegocio::create([
            'nombre' => 'RESTAURANTES',
            'slug' => 'restaurantes',
        ]);
        \App\Models\CategoriaNegocio::create([
            'nombre' => 'EXPERIENCIAS',
            'slug' => 'experiencias',
        ]);
        \App\Models\CategoriaNegocio::create([
            'nombre' => 'ARTESANIAS',
            'slug' => 'artesanias',
        ]);
    }
}
