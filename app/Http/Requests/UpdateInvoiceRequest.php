<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
                'required|exists:invoices,id',

            'deal_id' =>
                'sometimes|exists:deals,id',

            'sponsor_id' =>
                'sometimes|exists:sponsors,id',

            'invoice_title' =>
                'sometimes|string|max:255',

            'invoice_amount' =>
                'sometimes|numeric|min:0',

            'payment_type' =>
                'sometimes|in:cash,online',

            'tax' =>
                'sometimes|numeric|min:0',

            'discount' =>
                'sometimes|numeric|min:0',

            'total_amount' =>
                'sometimes|numeric|min:0',

            'currency' =>
                'sometimes|in:USD,EUR,GBP,INR',

            'invoice_date' =>
                'sometimes|date',

            'due_date' =>
                'sometimes|date',

            'payment_status' =>
                'sometimes|in:Pending,Paid,Overdue',

            'billing_address' =>
                'sometimes|string',

            'contact_email' =>
                'sometimes|email'
        ];
    }
}