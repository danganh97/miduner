<?php

namespace App\Http\Requests;

use Midun\Http\FormRequest;

class TestRequestFile extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email;danganh.dev@gmail.com',
            'file' => 'required|customRule|image|min:0.5',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => trans('validation.required', ['attribute' => 'file']),
            'file.image' => trans('validation.image', ['attribute' => 'file']),
            'file.min' => trans('validation.min.file', ['attribute' => 'file', 'min' => 0.5]),
        ];
    }
}
