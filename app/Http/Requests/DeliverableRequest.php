<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\DeliverableConstants;

class DeliverableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected $stopOnFirstFailure = false;

    
    public function rules(): array
    {
        return [

            'deal_id' =>
                'required|exists:deals,id',

            'deliver_type_id' =>
                'required|exists:deliver_types,id',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'quantity' =>
                'required|integer|min:1',

            'attachment' =>
                'nullable|string',

            'sponsor_id' =>
                'required|exists:sponsors,id',

            'assigned_to' => [
    'required',
    'string',
    'exists:sponsors,name'
],

'status' => [
    'required',
    'in:pending,in_progress,completed'
],

            'distribution_date' =>
                'nullable|date',

            'priority' =>
                'required|in:low,medium,high',

            'start_date' =>
                'required|date',

            'due_date' =>
                'required|date|after_or_equal:start_date'
        ];
    }
}