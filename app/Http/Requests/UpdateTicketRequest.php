<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest
extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id')
        ]);
    }

    public function rules(): array
    {
        return [

            'id' =>
                'required|exists:tickets,id',

            'deal_id' =>
                'sometimes|exists:deals,id',

            'team_id' =>
                'sometimes|exists:teams,id',

            'sponsor_id' =>
                'sometimes|exists:sponsors,id',

            'name' =>
                'sometimes|string|max:255',

                'ticket_status' =>
                'sometimes|in:assigned,used,pending',



            'priority' =>
                'sometimes|in:high,medium,low',

            'start_date' =>
                'sometimes|date',

                'number_of_tickets' => 'sometimes|integer|min:1',

            'attachment' =>
                'sometimes|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240'
        ];
    }
}