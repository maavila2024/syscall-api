<?php

namespace App\Imports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Helpers\TaskCodeGenerator;
use Illuminate\Support\Facades\Log;

class TaskImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Verificar se todas as chaves existem no array
        $requiredKeys = [
            'segment', 'task_type', 'name', 'created_at', 'system_screen',
            'responsible_id', 'owner_id', 'description', 'priority_id',
            'task_status_id', 'observation', 'finish_date', 'expected_date'
        ];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $row)) {
                throw new \Exception("Missing required field: $key");
            }
        }

        // Gerar o task_code
        $taskCode = TaskCodeGenerator::generateTaskCode($row['task_type']);

        return new Task([
            'segment' => $row['segment'],
            'task_type' => $row['task_type'],
            'name' => $row['name'],
            'task_code' => $taskCode, // Adiciona o task_code gerado
            'created_at' => \Carbon\Carbon::parse($row['created_at']),
            'system_screen' => $row['system_screen'],
            'responsible_id' => $row['responsible_id'],
            'owner_id' => $row['owner_id'],
            'description' => $row['description'],
            'priority_id' => $row['priority_id'],
            'task_status_id' => $row['task_status_id'],
            'observation' => $row['observation'],
            'finish_date' => \Carbon\Carbon::parse($row['finish_date']),
            'expected_date' => \Carbon\Carbon::parse($row['expected_date']),
        ]);
    }
}
