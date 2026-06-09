<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DealTypeService;
use App\Http\Requests\DealTypeRequest;

class DealTypeController extends Controller
{
    protected $dealTypeService;

    public function __construct(
        DealTypeService $dealTypeService
    )
    {
        $this->dealTypeService =
            $dealTypeService;
    }

    public function store(
        DealTypeRequest $request
    )
    {
        $dealType = $this->dealTypeService
            ->create(
                $request->validated()
            );

        return response()->json([

            'status' => true,

            'message' =>
                'Deal type created successfully',

            'data' => $dealType

        ], 201);
    }
}