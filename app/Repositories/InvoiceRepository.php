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

public function getInvoices(
    array $filters
)
{
    $query = Invoice::query()
        ->with([
            'deal:id,deal_title',
            'sponsor:id,name'
        ]);

    if (!empty($filters['search'])) {

        $search = $filters['search'];

        $query->where(function ($q) use ($search) {

            $q->where(
                'invoice_id',
                'like',
                "%{$search}%"
            )

            ->orWhereHas(
                'sponsor',
                function ($sponsor) use ($search) {

                    $sponsor->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        });
    }

    if (!empty($filters['payment_status'])) {

        $query->where(
            'payment_status',
            $filters['payment_status']
        );
    }

    return $query
        ->latest('id')
        ->paginate(
            $filters['per_page'] ?? 10
        );
}

public function findInvoiceById(
    int $id
)
{
    return Invoice::with([
        'deal:id,deal_title',
        'sponsor:id,name'
    ])->find($id);
}
}