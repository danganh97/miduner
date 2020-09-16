<?php

namespace App\Http\Requests;

use Midun\Http\FormRequest;

class TestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|number|min:2|max:5',
            'title' => 'required|min:2|max:5|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => trans('validation.required', ['attribute' => 'id']),
            'id.number' => trans('validation.number', ['attribute' => 'id']),
        ];
    }
}
