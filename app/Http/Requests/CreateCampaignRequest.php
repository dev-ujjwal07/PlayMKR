<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('campaign_data')) {

            $this->merge(
                json_decode(
                    $this->campaign_data,
                    true
                )
            );
        }
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Deal
            |--------------------------------------------------------------------------
            */

            'deal.deal_title' =>
                'required|string|max:255',

            'deal.deal_description' =>
                'required|string',

            'deal.deal_type_name' =>
                'required|string',

            'deal.status' =>
                'required|in:active,pending,completed',

            'deal.total_deal_value' =>
                'required|numeric|min:0',

            'deal.sponsor_id' =>
                'required|exists:sponsors,id',

            /*
            |--------------------------------------------------------------------------
            | Deliverables
            |--------------------------------------------------------------------------
            */

            'deliverables' =>
                'required|array|min:1',

            'deliverables.*.deliver_type_name' =>
                'required|string',

            'deliverables.*.title' =>
                'required|string|max:255',

            'deliverables.*.description' =>
                'nullable|string',

            'deliverables.*.quantity' =>
                'required|integer|min:1',

            'deliverables.*.start_date' =>
                'required|date',

                'deliverables.*.priority' =>
    'required|in:high,medium,low',

            'deliverables.*.due_date' =>
                'required|date|after_or_equal:deliverables.*.start_date',

            /*
            |--------------------------------------------------------------------------
            | Milestones
            |--------------------------------------------------------------------------
            */

            'milestones' =>
                'required|array|min:1',

            'milestones.*.name' =>
                'required|string|max:255',

            'milestones.*.due_date' =>
                'required|date',

            'milestones.*.status' =>
                'required|in:pending,in_progress,completed',





'deliverable_attachments' =>
    'required|array|min:1',

'deliverable_attachments.*' =>
    'required|array|min:1',

'deliverable_attachments.*.*' =>
    'required|file|mimes:jpg,jpeg,png,pdf,mp4,mov,avi|max:10240',

            /*
            |--------------------------------------------------------------------------
            | Invoices
            |--------------------------------------------------------------------------
            */

            'invoices' =>
                'required|array|min:1',

            'invoices.*.payment_type' =>
                'required|in:cash,online',

            'invoices.*.invoice_amount' =>
                'required|numeric|min:0',

            'invoices.*.invoice_date' =>
                'required|date',

            'invoices.*.due_date' =>
                'required|date',

            'invoices.*.payment_status' =>
                'required|in:Pending,Paid,Overdue'
        ];
    }
}