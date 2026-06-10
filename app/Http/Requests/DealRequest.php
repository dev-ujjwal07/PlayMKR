<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DealRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
   public function authorize(): bool
{
    return true;
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
public function rules(): array
{
    return [

        'sponsor_id' =>
            'required|integer|exists:sponsors,id',

        'deal_title' =>
            'required|string|max:255',

        'deal_description' =>
            'required|string',

        'status' =>
            'required|in:active,pending,completed',

        'deal_type_name' =>
            'required|string'
    ];
}
}
