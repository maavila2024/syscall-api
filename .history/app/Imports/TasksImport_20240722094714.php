<?php

namespace App\Imports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\ToModel;

class TasksImport implements ToModel
{
    public function model(array $row)
    {
        return new Task([
            'segment' => $row['segment'],
            'task_type' => $row['task_type'],
            'name' => $row['name'],
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
