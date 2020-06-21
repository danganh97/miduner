<?php

namespace App\Http\Requests;

use Main\Http\BaseRequest;

class TestRequest extends BaseRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'param1' => 'required|max:100'
        ];
    }

    public function messages()
    {
        return [
            'param1.required' => 'Param 1 is required',
        ];
    }
}