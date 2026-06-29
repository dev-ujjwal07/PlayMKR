<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'ticket_id' =>

                'required|integer',

            'title' =>

                'required|string|max:255',

            'description' =>

                'required|string',

            'attachment' =>

                'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120'

        ];
    }
}