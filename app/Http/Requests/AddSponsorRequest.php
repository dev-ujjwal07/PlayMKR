<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddSponsorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
public function authorize(): bool
{
    return true;
}

public function rules(): array
{
    return [

        'name' => 'required|string|max:255',

        'email' => 'required|email|unique:sponsors,email',

        'contact_number' => 'required|string|max:20',

        'website_url' => 'required|url',

        'industry' => 'required|string|max:255',

        'address' => 'required|string',

        'status' => 'required|in:active,inactive',
    ];
}
}
