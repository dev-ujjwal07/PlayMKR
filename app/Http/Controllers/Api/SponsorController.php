<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SponsorApprovalRequest;
use App\Services\SponsorService;
use App\Http\Requests\SponsorStatusRequest;
use App\Http\Requests\AddSponsorRequest;
use App\Http\Requests\DeleteSponsorRequest;
use App\Http\Requests\UpdateSponsorRequest;


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

public function addSponsor(
    AddSponsorRequest $request
)
{
    $sponsor = $this->sponsorService
        ->addSponsor(
            $request->validated()
        );

    return response()->json([

        'status' => true,

        'message' =>
            'Sponsor created successfully',

        'data' => $sponsor

    ], 201);
}


public function deleteSponsor(
    DeleteSponsorRequest $request
)
{
    $this->sponsorService
        ->deleteSponsor(
            $request->validated()['id']
        );

    return response()->json([

        'status' => true,

        'message' =>
            'Sponsor deleted successfully'

    ], 200);
}


public function updateSponsor(
    UpdateSponsorRequest $request
)
{
    $sponsor = $this->sponsorService
        ->updateSponsor(
            $request->validated()
        );

    return response()->json([

        'status' => true,

        'message' =>
            'Sponsor updated successfully',

        'data' => $sponsor

    ], 200);
}
}