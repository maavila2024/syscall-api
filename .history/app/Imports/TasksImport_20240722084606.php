<?php

namespace App\Imports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\ToModel;

class TasksImport implements ToModel
{
    public function model(array $row)
    {
        return new Task([
            // Mapeie as colunas do Excel para os campos do modelo Task
            'name' => $row[0],
            'description' => $row[1],
            // adicione outros campos conforme necessário
        ]);
    }
}
