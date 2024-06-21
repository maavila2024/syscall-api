<?php

namespace App\Http\Requests\Task;

use App\Enums\Segment;
use App\Enums\Status;
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
            'name'  => 'string|required',
            'description'  => 'string|required',
            'owner_id'  => 'required',
            'responsible_id'  => 'required',
            'task_status_id' => 'required',
            'system_screen'  => 'string|required',
            'observation'  => 'string|required',
            'priority_id'  => 'required',
            'priority_justification'  => ['required_if:priority_id,4'],
            'review_justification'  => ['required_if:status_id,3'],
            'expected_date' => [self::REQUIRED, self::DATE, self::DATE_FORMAT_DB, self::DATE_AFTER_1900],
            'finish_date' => [self::REQUIRED, self::DATE, self::DATE_FORMAT_DB, self::DATE_AFTER_1900],
            'status' => ['required', new Enum(Status::class)],
        ];
    }
}
