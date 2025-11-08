<?php

namespace Database\Seeders;

use App\Models\Complexity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplexitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Complexity::create([
            'name' => 'Baixa',
            'is_default' => 1,
            'status' => '1',
        ]);

        Complexity::create([
            'name' => 'Média',
            'is_default' => 0,
            'status' => '1',
        ]);

        Complexity::create([
            'name' => 'Alta',
            'is_default' => 0,
            'status' => '1',
        ]);

        Complexity::create([
            'name' => 'Aguardando Análise',
            'is_default' => 0,
            'status' => '1',
        ]);
    }
}

