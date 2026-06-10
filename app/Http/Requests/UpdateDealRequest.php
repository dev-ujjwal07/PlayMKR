<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
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

            'id' => 'required|integer|exists:deals,id',

            'sponsor_id' => 'required|integer|exists:sponsors,id',

            'deal_title' => 'required|string|max:255',

            'deal_description' => 'required|string',

            'status' => 'required|in:active,pending,completed',

            'deal_type_name' => 'required|string|exists:deal_types,name'
        ];
    }
}