<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:topics,key'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
