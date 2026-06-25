<?php

namespace App\Services;

use App\Interfaces\InvoiceRepositoryInterface;
use Exception;
use App\Constants\InvoiceConstants;
use App\Exceptions\InvoiceNotFoundException;

class InvoiceService
{
    protected $invoiceRepository;

    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository
    )
    {
        $this->invoiceRepository =
            $invoiceRepository;
    }

    public function create(
        array $data
    )
    {
        $lastInvoice =
            $this->invoiceRepository
                ->getLastInvoice();

        $number = $lastInvoice
            ? $lastInvoice->id + 1
            : 1;

        $data['invoice_id'] =
            'INV-' .
            str_pad(
                $number,
                4,
                '0',
                STR_PAD_LEFT
            );

        return $this->invoiceRepository
            ->create($data);
    }


    public function update(
    int $id,
    array $data
)
{
    $invoice =
        $this->invoiceRepository
            ->findById($id);

    if (!$invoice) {

        throw new Exception(
            InvoiceConstants::INVOICE_NOT_FOUND
        );
    }

    unset($data['invoice_id']);

    return $this->invoiceRepository
        ->update(
            $id,
            $data
        );
}

public function delete(
    int $id
)
{
    $invoice =
        $this->invoiceRepository
            ->findById($id);

    if (!$invoice) {

        throw new Exception(
            InvoiceConstants::INVOICE_NOT_FOUND
        );
    }

    return $this->invoiceRepository
        ->delete($id);
}


public function getInvoices(
    array $filters
)
{
    $invoices =
        $this->invoiceRepository
            ->getInvoices(
                $filters
            );

    $formattedData =
        collect(
            $invoices->items()
        )->map(
            function ($invoice) {

                return [

                    'id' =>
                        $invoice->id,

                    'invoice_id' =>
                        $invoice->invoice_id,

                    'deal_name' =>
                        $invoice->deal?->deal_title,

                    'sponsor_name' =>
                        $invoice->sponsor?->name,

                    'invoice_title' =>
                        $invoice->invoice_title,

                    'invoice_amount' =>
                        $invoice->invoice_amount,

                    'payment_type' =>
                        $invoice->payment_type,

                    'tax' =>
                        $invoice->tax,

                    'discount' =>
                        $invoice->discount,

                    'total_amount' =>
                        $invoice->total_amount,

                    'currency' =>
                        $invoice->currency,

                    'invoice_date' =>
                        $invoice->invoice_date,

                    'due_date' =>
                        $invoice->due_date,

                    'payment_status' =>
                        $invoice->payment_status,

                    'billing_address' =>
                        $invoice->billing_address,

                    'contact_email' =>
                        $invoice->contact_email,

                    'created_at' =>
                        $invoice->created_at,

                    'updated_at' =>
                        $invoice->updated_at
                ];
            }
        );

    return [

        'data' =>
            $formattedData,

        'current_page' =>
            $invoices->currentPage(),

        'last_page' =>
            $invoices->lastPage(),

        'per_page' =>
            $invoices->perPage(),

        'total' =>
            $invoices->total()
    ];
}





public function getInvoiceById(
    int $id
)
{
    $invoice =
        $this->invoiceRepository
            ->findInvoiceById(
                $id
            );

    if (!$invoice) {

      throw new InvoiceNotFoundException(
    'Invoice not found'
);
    }

    return [

        'id' =>
            $invoice->id,

        'invoice_id' =>
            $invoice->invoice_id,

        'deal_name' =>
            $invoice->deal?->deal_title,

        'sponsor_name' =>
            $invoice->sponsor?->name,

        'invoice_title' =>
            $invoice->invoice_title,

        'invoice_amount' =>
            $invoice->invoice_amount,

        'payment_type' =>
            $invoice->payment_type,

        'tax' =>
            $invoice->tax,

        'discount' =>
            $invoice->discount,

        'total_amount' =>
            $invoice->total_amount,

        'currency' =>
            $invoice->currency,

        'invoice_date' =>
            $invoice->invoice_date,

        'due_date' =>
            $invoice->due_date,

        'payment_status' =>
            $invoice->payment_status,

        'billing_address' =>
            $invoice->billing_address,

        'contact_email' =>
            $invoice->contact_email,

        'created_at' =>
            $invoice->created_at,

        'updated_at' =>
            $invoice->updated_at
    ];
}
}