<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateInvoiceRequest;
use App\Services\InvoiceService;
use App\Constants\InvoiceConstants;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Requests\DeleteInvoiceRequest;


class InvoiceController extends Controller
{
protected $invoiceService;

public function __construct(
InvoiceService $invoiceService
)
{
$this->invoiceService =
$invoiceService;
}


    public function store(
    CreateInvoiceRequest $request
)
{
    $invoice =
        $this->invoiceService
            ->create(
                $request->validated()
            );

    return response()->json([

        'status' => true,

        'message' =>
            InvoiceConstants
                ::INVOICE_CREATED,

        'data' => $invoice

    ], 201);
}


public function update(
    UpdateInvoiceRequest $request,
    int $id
)
{
    $invoice =
        $this->invoiceService
            ->update(
                $id,
                $request->validated()
            );

    return response()->json([

        'status' => true,

        'message' =>
            InvoiceConstants::INVOICE_UPDATED,

        'data' => $invoice

    ], 200);
}

public function destroy(
    DeleteInvoiceRequest $request,
    int $id
)
{
    $this->invoiceService
        ->delete($id);

    return response()->json([

        'status' => true,

        'message' =>
            InvoiceConstants::INVOICE_DELETED

    ], 200);
}


public function index(
    Request $request
)
{
    $invoices =
        $this->invoiceService
            ->getInvoices([

                'search' =>
                    $request->search,

                'payment_status' =>
                    $request->payment_status,

                'per_page' =>
                    $request->per_page
            ]);

    return response()->json([

        'status' => true,

        'message' =>
            'Invoices fetched successfully',

        'data' =>
            $invoices['data'],

        'current_page' =>
            $invoices['current_page'],

        'last_page' =>
            $invoices['last_page'],

        'per_page' =>
            $invoices['per_page'],

        'total' =>
            $invoices['total']
    ]);
}



public function show(
    int $id
)
{
    $invoice =
        $this->invoiceService
            ->getInvoiceById(
                $id
            );

    return response()->json([

        'status' => true,

        'message' =>
            'Invoice fetched successfully',

        'data' =>
            $invoice
    ]);
}
}
