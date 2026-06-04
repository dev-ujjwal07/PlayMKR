<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SponsorApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => 'required|string|max:100',

            'email' => 'required|email|max:255',

            'contact_number' =>
                'required|string|min:10|max:15',

            'website_url' =>
                'required|url|max:255',

            'industry' =>
                'required|string|max:100',

            'address' =>
                'required|string|max:500',
        ];
    }
}