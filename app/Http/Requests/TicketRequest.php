<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'deal_id' =>
                'required|exists:deals,id',

            'team_id' =>
                'required|exists:teams,id',

            'sponsor_id' =>
                'required|exists:sponsors,id',

            'name' =>
                'required|string|max:255',

            'priority' =>
                'required|in:high,medium,low',

            'start_date' =>
                'required|date',

            'attachment' =>
                'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240'
        ];
    }
}