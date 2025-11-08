<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Priority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Garantir que existe pelo menos um usuário
        $owner = User::first();
        if (!$owner) {
            $owner = User::factory()->create();
        }

        // Garantir que existe pelo menos uma prioridade
        $priority = Priority::first();
        if (!$priority) {
            $priority = Priority::create([
                'name' => 'Baixa',
                'is_default' => 1,
                'justify' => 0,
                'status' => 1,
            ]);
        }

        // Obter o último task_code ou começar do 1000001
        $lastTaskCode = Task::max('task_code');
        $startCode = $lastTaskCode ? (int) $lastTaskCode + 1 : 1000001;
        $currentCode = $startCode;

        // Gerar 5 tasks com status "Aberto" (id = 1)
        for ($i = 0; $i < 5; $i++) {
            Task::factory()
                ->open()
                ->create([
                    'segment' => '1',
                    'task_type' => '1',
                    'task_code' => (string) $currentCode++,
                    'owner_id' => $owner->id,
                    'priority_id' => $priority->id,
                ]);
        }

        // Gerar 5 tasks com status "Em desenvolvimento" (id = 2)
        for ($i = 0; $i < 5; $i++) {
            Task::factory()
                ->inDevelopment()
                ->create([
                    'segment' => '1',
                    'task_type' => '1',
                    'task_code' => (string) $currentCode++,
                    'owner_id' => $owner->id,
                    'priority_id' => $priority->id,
                ]);
        }

        // Gerar 5 tasks com status "Concluído" (id = 5) com finish_date preenchido
        for ($i = 0; $i < 5; $i++) {
            Task::factory()
                ->completed()
                ->create([
                    'segment' => '1',
                    'task_type' => '1',
                    'task_code' => (string) $currentCode++,
                    'owner_id' => $owner->id,
                    'priority_id' => $priority->id,
                    'finish_date' => now()->format('Y-m-d'),
                ]);
        }
    }
}

