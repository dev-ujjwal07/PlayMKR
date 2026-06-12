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