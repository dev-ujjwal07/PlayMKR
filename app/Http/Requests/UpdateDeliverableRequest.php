<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliverableRequest
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
                'required|exists:deliverables,id',

            'deal_id' =>
                'sometimes|exists:deals,id',

            'deliver_type_id' =>
                'sometimes|exists:deliver_types,id',

            'title' =>
                'sometimes|string|max:255',

            'description' =>
                'nullable|string',

            'quantity' =>
                'sometimes|integer|min:1',

            'sponsor_id' =>
                'sometimes|exists:sponsors,id',

            'assigned_to' =>
                'sometimes|string',


'name' =>
    'sometimes|string|max:255',

'team_id' =>
    'sometimes|exists:teams,id',

'attachment' =>
    'sometimes|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',

            'status' =>
                'sometimes|in:pending,in_progress,completed',

            'distribution_date' =>
                'nullable|date',

            'priority' =>
                'sometimes|string|max:50',

            'start_date' =>
                'sometimes|date',

            'due_date' =>
                'sometimes|date|after_or_equal:start_date'


                
        ];
    }
}