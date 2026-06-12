<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [

        'deliverable_id' =>

            'required|
             exists:deliverables,id',

        'attachments' =>

            'required|array',

        'attachments.*' =>

            'file|
             mimes:jpg,jpeg,png,pdf,
             mp4,mov,avi|
             max:10240'
    ];
}
}
