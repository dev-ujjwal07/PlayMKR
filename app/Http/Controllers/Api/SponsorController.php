<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SponsorApprovalRequest;
use App\Services\SponsorService;
use App\Http\Requests\SponsorStatusRequest;

class SponsorController extends Controller
{
    protected $sponsorService;

    public function __construct(
        SponsorService $sponsorService
    )
    {
        $this->sponsorService = $sponsorService;
    }

    public function approveSponsor(
        SponsorApprovalRequest $request
    )
    {
        $sponsor = $this->sponsorService
            ->approveSponsor(
                $request->validated()
            );

        return response()->json([

            'status' => true,

            'message' =>
                'Sponsor approved successfully',

            'data' => $sponsor

        ], 200);
    }


    public function sponsorStatus(
    SponsorStatusRequest $request
)
{
    $sponsor = $this->sponsorService
        ->updateSponsorStatus(
            $request->validated()
        );

    return response()->json([

        'status' => true,

        'message' =>
            'Sponsor status updated successfully',

        'data' => $sponsor

    ], 200);
}
}