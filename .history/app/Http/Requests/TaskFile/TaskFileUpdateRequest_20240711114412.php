<?php

namespace App\Http\Requests\InteractionFile;

use Illuminate\Foundation\Http\FormRequest;

class TaskFileUpdateRequest extends FormRequest
{
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
            'files.*' => 'required|mimes:jpg,jpeg,png,gif,pdf,xlx,csv|max:10240',
            'interaction_id' => 'required',
        ];
    }
}
