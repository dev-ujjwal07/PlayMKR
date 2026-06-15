<?php

namespace App\Services;

use App\Interfaces\InvoiceRepositoryInterface;
use Exception;
use App\Constants\InvoiceConstants;


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
}