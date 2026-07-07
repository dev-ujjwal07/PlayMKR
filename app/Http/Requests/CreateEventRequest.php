<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'event_name' =>
                'required|string|max:255',

            'start_date' =>
                'required|date',

            'end_date' =>
                'required|date|after_or_equal:start_date',

            'start_time' =>
                'required|date_format:H:i',

            'end_time' =>
                'required|date_format:H:i',

            'event_description' =>
                'nullable|string',

            'event_image' =>
                'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [

            'event_name.required' =>
                'Event name is required.',

            'start_date.required' =>
                'Start date is required.',

            'end_date.required' =>
                'End date is required.',

            'end_date.after_or_equal' =>
                'End date must be greater than or equal to start date.',

            'start_time.required' =>
                'Start time is required.',

            'start_time.date_format' =>
                'Start time must be in 24-hour format (HH:MM).',

            'end_time.required' =>
                'End time is required.',

            'end_time.date_format' =>
                'End time must be in 24-hour format (HH:MM).',

            'event_image.image' =>
                'Event image must be an image.',

            'event_image.mimes' =>
                'Event image must be a JPG, JPEG or PNG file.',

            'event_image.max' =>
                'Event image size must not exceed 2 MB.',
        ];
    }
}