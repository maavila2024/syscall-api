<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskStatus::create([
            'name' => 'Aberto',
            'is_default' => 1,
            'status' => 1,
        ]);
        TaskStatus::create([
            'name' => 'Em desenvolvimento',
            'is_default' => 0,
            'status' => 1,
        ]);
        TaskStatus::create([
            'name' => 'Aguardando resposta',
            'is_default' => 0,
            'status' => 1,
        ]);
        TaskStatus::create([
            'name' => 'Enviado para teste',
            'is_default' => 0,
            'status' => 1,
        ]);
        TaskStatus::create([
            'name' => 'Concluído',
            'is_default' => 0,
            'status' => 1,
        ]);
    }
}
