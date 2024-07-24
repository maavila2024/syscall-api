<?php

namespace App\Http\Requests\Task;

use App\Enums\Segment;
use App\Enums\Status;
use App\Enums\TaskType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\Rules;

class TaskUpdateRequest extends FormRequest
{
    use Rules;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
            'expected_date' => 'nullable',
            'finish_date' => 'nullable',
            'sequence' => 'nullable|numeric',
        ];
    }
}
