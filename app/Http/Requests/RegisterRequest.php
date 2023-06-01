<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'string|required|max:100',
            'email' => 'string|required|max:100|unique:App\Models\User,email|email',
            'password' => 'confirmed|required|string|max:100|min:5',
            'password_confirmation' => 'required|string',
        ];
    }
}
