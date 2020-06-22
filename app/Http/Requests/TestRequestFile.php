<?php

namespace App\Http\Requests;

use Main\Http\RequestValidation;

class TestRequestFile extends RequestValidation
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|image',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => trans('validation.required', ['attribute' => 'file']),
            'file.file' => trans('validation.image', ['attribute' => 'file']),
        ];
    }
}
