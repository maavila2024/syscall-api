<?php

namespace App\Http\Requests\InteractionFile;    

use Illuminate\Foundation\Http\FormRequest;

class InteractionFileStoreRequest extends FormRequest
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
            'interaction_id' => 'required|exists:interactions,id',
            'files' => 'required|array',
            'files.*' => 'required|file|max:10240' // 10MB max
        ];
    }
}
