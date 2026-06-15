<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Interfaces\InvoiceRepositoryInterface;

class InvoiceRepository
implements InvoiceRepositoryInterface
{
    public function create(
        array $data
    )
    {
        return Invoice::create(
            $data
        );
    }

    public function getLastInvoice()
    {
        return Invoice::latest()
            ->first();
    }

    public function findById(
    int $id
)
{
    return Invoice::find($id);
}

public function update(
    int $id,
    array $data
)
{
    $invoice =
        Invoice::findOrFail($id);

    $invoice->update($data);

    return $invoice->fresh();
}

public function delete(
    int $id
)
{
    $invoice =
        Invoice::findOrFail($id);

    return $invoice->delete();
}
}