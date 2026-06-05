<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SponsorStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'id' => [
                'required',
                'integer',
                'exists:sponsors,id'
            ],

            'status' => [
                'required',
                'in:active,inactive'
            ]

        ];
    }
}