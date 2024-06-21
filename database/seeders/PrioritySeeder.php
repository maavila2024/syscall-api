<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Priority::create([
            'name' => 'Baixa',
            'is_default' => 1,
            'justify' => 0,
            'status' => 1,
        ]);
        Priority::create([
            'name' => 'Média',
            'is_default' => 0,
            'justify' => 0,
            'status' => 1,
        ]);
        Priority::create([
            'name' => 'Alta',
            'is_default' => 0,
            'justify' => 0,
            'status' => 1,
        ]);
        Priority::create([
            'name' => 'Urgente',
            'is_default' => 0,
            'justify' => 1,
            'status' => 1,
        ]);
    }
}
