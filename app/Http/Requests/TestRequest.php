<?php

namespace App\Http\Requests;

use Main\Http\FormRequest;

class TestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|number|min:2|max:5',
            'title' => 'required|min:2|max:5|string',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => trans('validation.required', ['attribute' => 'id']),
            'id.number' => trans('validation.number', ['attribute' => 'id']),
        ];
    }
}
