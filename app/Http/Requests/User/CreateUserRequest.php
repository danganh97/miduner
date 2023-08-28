<?php

namespace App\Http\Requests\User;

use Midun\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "vui long nhap ten",
            'email.required' => 'vui long nhap email',
            'password.required' => 'vui long nhap password'
        ];
    }
}
