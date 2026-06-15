<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
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

        'deal_id' =>
            'required|exists:deals,id',

        'sponsor_id' =>
            'required|exists:sponsors,id',

        'invoice_title' =>
            'required|string|max:255',

        'invoice_amount' =>
            'required|numeric|min:0',

     

        'tax' =>
            'required|numeric|min:0',

        'discount' =>
            'required|numeric|min:0',

       

        'currency' =>
            'required|in:USD,EUR,GBP,INR',

        'invoice_date' =>
            'required|date',

        'due_date' =>
            'required|date|after_or_equal:invoice_date',

        'payment_status' =>
            'required|in:Pending,Paid,Overdue',

        'billing_address' =>
            'required|string',

        'contact_email' =>
            'required|email'
    ];
}
}
