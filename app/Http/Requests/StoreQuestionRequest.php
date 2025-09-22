<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'choices' => ['required', 'array', 'size:4'],
            'choices.*' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'integer', 'min:0', 'max:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'choices.size' => 'Exactly 4 choices are required.',
        ];
    }
}
