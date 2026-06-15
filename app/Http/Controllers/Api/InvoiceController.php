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
}
