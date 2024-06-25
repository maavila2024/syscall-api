<?php

namespace App\Helpers;

use App\Models\Task;

class TaskCodeGenerator
{
    public static function generateTaskCode($taskType)
    {
        $prefix = $taskType == 1 ? 'I' : 'M';
        $latestTask = Task::where('task_type', $taskType)->orderBy('id', 'desc')->first();

        if ($latestTask) {
            $lastNumber = intval(substr($latestTask->task_code, 1)) + 1;
        } else {
            $lastNumber = 1;
        }

        return $prefix . str_pad($lastNumber, 6, '0', STR_PAD_LEFT);
    }
}
