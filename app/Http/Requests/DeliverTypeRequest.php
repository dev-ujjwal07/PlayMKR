<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliverTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' =>
                'required|string|max:255|unique:deliver_types,name',

            'slug' =>
                'required|string|max:255|unique:deliver_types,slug'
        ];
    }
}