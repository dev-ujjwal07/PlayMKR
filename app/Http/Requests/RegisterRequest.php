<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'full_name' => 'required|min:3|max:50',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|min:6|max:20'

        ];
    }
}