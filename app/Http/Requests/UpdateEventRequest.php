<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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

        'event_name' =>
            'sometimes|required|string|max:255',

        'start_date' =>
            'sometimes|required|date',

        'end_date' =>
            'sometimes|required|date|after_or_equal:start_date',

        'start_time' =>
            'sometimes|required|date_format:H:i',

        'end_time' =>
            'sometimes|required|date_format:H:i',

        'event_description' =>
            'nullable|string',

        'event_image' =>
            'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];
}
}
