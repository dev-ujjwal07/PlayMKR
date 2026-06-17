<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SponsorApplicationService;
use App\Http\Requests\SponsorApplicationRequest;

class SponsorApplicationController
extends Controller
{
    protected $service;

    public function __construct(
        SponsorApplicationService $service
    ) {
        $this->service = $service;
    }

    public function store(
        SponsorApplicationRequest $request
    ) {

        $application = $this->service
            ->create(
                $request->validated()
            );

        return response()->json([

            'status' => true,

            'message' =>
            'Sponsor application submitted successfully',

            'data' => $application

        ], 201);
    }
}
