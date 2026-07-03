<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInternalTeamProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' =>

                'sometimes|string|max:255',

            'email' =>

                'sometimes|email|max:255',

            'number' =>

                'sometimes|string|max:20',

            'profile' =>

                'sometimes|image|mimes:jpg,jpeg,png|max:2048',

            'current_password' =>

                'required_with:new_password,confirm_password',

            'new_password' =>

                'required_with:current_password,confirm_password|min:8',

            'confirm_password' =>

                'required_with:current_password,new_password|same:new_password'
        ];
    }
}