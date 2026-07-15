<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Interfaces\InvoiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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



public function getInvoiceStats()
{
    return [

        'total_invoice' =>

            Invoice::count(),

        'paid' =>

            Invoice::where(
                'payment_status',
                'paid'
            )->count(),

        'pending' =>

            Invoice::where(
                'payment_status',
                'pending'
            )->count(),

        'overdue' =>

            Invoice::where(
                'payment_status',
                'overdue'
            )->count()
    ];
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


public function getRevenueChart()
{
    return Invoice::select(

            DB::raw('MONTH(updated_at) as month'),

            DB::raw('SUM(total_amount) as revenue'),

          DB::raw('SUM(invoice_amount) as payments')
        )
        ->where(
            'payment_status',
            'paid'
        )
        ->groupBy(
            DB::raw('MONTH(updated_at)')
        )
        ->get()
        ->keyBy('month');
}


public function getWeeklyRevenueChart()
{
    return Invoice::select(

            DB::raw('DAYOFWEEK(updated_at) as day'),

            DB::raw('SUM(total_amount) as revenue'),

            DB::raw('SUM(invoice_amount) as payments')
        )
        ->where(
            'payment_status',
            'paid'
        )
        ->whereBetween(
            'updated_at',
            [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]
        )
        ->groupBy(
            DB::raw('DAYOFWEEK(updated_at)')
        )
        ->get()
        ->keyBy('day');
}



public function getYearlyRevenueChart()
{
    return Invoice::select(

            DB::raw('YEAR(updated_at) as year'),

            DB::raw('SUM(total_amount) as revenue'),

            DB::raw('SUM(invoice_amount) as payments')
        )
        ->where(
            'payment_status',
            'paid'
        )
        ->groupBy(
            DB::raw('YEAR(updated_at)')
        )
        ->get()
        ->keyBy('year');
}
}