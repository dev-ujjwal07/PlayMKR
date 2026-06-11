<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeliverTypeService;
use App\Http\Requests\DeliverTypeRequest;
use App\Constants\DeliverTypeConstants;


class DeliverTypeController extends Controller
{
    protected $deliverTypeService;

    public function __construct(
        DeliverTypeService $deliverTypeService
    )
    {
        $this->deliverTypeService =
            $deliverTypeService;
    }

    public function store(
        DeliverTypeRequest $request
    )
    {
        $deliverType = $this->deliverTypeService
            ->create(
                $request->validated()
            );

        return response()->json([

            'status' => true,

            'message' =>
                DeliverTypeConstants::DELIVER_TYPE_CREATED,

            'data' => $deliverType

        ], 201);
    }
}