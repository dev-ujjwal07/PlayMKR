<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSponsorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'id' => 'required|integer|exists:sponsors,id',

            'name' => 'required|string|max:255',

            'email' =>
                'required|email|unique:sponsors,email,' .
                $this->id,

            'contact_number' =>
                'required|string|max:20',

            'website_url' =>
                'required|url',

            'industry' =>
                'required|string|max:255',

            'address' =>
                'required|string',

            'status' =>
                'required|in:active,inactive'
        ];
    }
}