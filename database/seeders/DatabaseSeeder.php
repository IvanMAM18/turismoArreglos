<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'rommel',
            'email' => 'rommel.rubio@hotmail.com',
            'role' => 'admin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'demo',
            'email' => 'test@lapaz.gob.mx',
            'role' => 'usuario',
        ]);

        \App\Models\Delegacion::create([
            'nombre' => 'DELEGACION 1',
        ]);
        \App\Models\Categoria::create([
            'nombre' => 'CATEGORIA 1',
        ]);
    }
}
