<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InternalTeamTicketReportRequest
extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'internal_team_description' =>

                'required|string|max:5000',

            'attachment' =>

                'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ];
    }

    public function messages(): array
    {
        return [

            'internal_team_description.required' =>

                'Description is required.',

            'attachment.mimes' =>

                'Attachment must be jpg, jpeg, png, pdf, doc or docx.',

            'attachment.max' =>

                'Attachment size must not exceed 5MB.',
        ];
    }
}