<?php

namespace Main\Http;

class BaseRequest extends Request
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function authorize()
    {
        return true;
    }

    protected function rules()
    {
        return [];
    }

    protected function messages()
    {
        return [];
    }
}