<?php

namespace App\Http\Requests\Task;

use App\Enums\Segment;
use App\Enums\Status;
use App\Enums\TaskType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\Rules;
use Carbon\Carbon;

class TaskUpdateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if ($this->has('expected_date')) {
            $this->merge([
                'expected_date' => Carbon::createFromFormat('Y-m-d', $this->expected_date)->format('Y-m-d'),
            ]);
        }

        if ($this->has('finish_date')) {
            $this->merge([
                'finish_date' => Carbon::createFromFormat('Y-m-d', $this->finish_date)->format('Y-m-d'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'segment' => ['required', new Enum(Segment::class)],
            'task_type' => ['required', new Enum(TaskType::class)],
            'name'  => 'string|required',
            'task_code'  => 'string|required',
            'system_screen'  => 'nullable|string',
            'observation'  => 'nullable|string',
            'priority_justification'  => 'nullable|string',
            'complexity_id'  => 'required|exists:complexities,id',
            'complexity_justification'  => 'nullable|string',
            'description'  => 'string|required',
            'owner_id'  => 'required|exists:users,id',
            'responsible_id'  => 'required|exists:users,id',
            'task_status_id' => 'required|exists:tasks_status,id',
            'priority_id'  => 'required|exists:priorities,id',
            'status' => ['required', new Enum(Status::class)],
            'expected_date' => 'nullable|date_format:Y-m-d',
            'finish_date' => 'nullable|date_format:Y-m-d',
        ];
    }
}
