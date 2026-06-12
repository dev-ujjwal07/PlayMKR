<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteDeliverableRequest
    extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' =>
                'required|
                 integer|
                 exists:deliverables,id'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id')
        ]);
    }
}