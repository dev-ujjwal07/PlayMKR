<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCampaignRequest;
use App\Services\CampaignService;
use App\Constants\CampaignConstants;
use App\Http\Requests\UpdateCampaignRequest;


class CampaignController extends Controller
{
    protected $campaignService;

    public function __construct(
        CampaignService $campaignService
    ) {
        $this->campaignService =
            $campaignService;
    }

    public function store(
        CreateCampaignRequest $request
    ) {
        try {

            $campaign =
                $this->campaignService
                ->create(
                    $request->validated(),
                    $request->file(
                        'deliverable_attachments',
                        []
                    )
                );

            return response()->json([

                'status' => true,

                'message' =>
                CampaignConstants
                ::CAMPAIGN_CREATED,

                'data' => $campaign

            ], 201);
        } catch (Exception $e) {

            return response()->json([

                'status' => false,

                'message' =>
                $e->getMessage()

            ], 422);
        }
    }



    public function update(
        UpdateCampaignRequest $request,
        int $dealId
    ) {
        try {

            $campaign =
                $this->campaignService
                ->update(
                    $dealId,
                    $request->validated(),
                    $request->file(
                        'deliverable_attachments',
                        []
                    )
                );

            return response()->json([

                'status' => true,

                'message' =>
                CampaignConstants
                ::CAMPAIGN_UPDATED,

                'data' => $campaign

            ], 200);
        } catch (Exception $e) {

            $statusCode = 422;

            if (
                $e->getMessage() ===
                CampaignConstants::CAMPAIGN_NOT_FOUND
            ) {
                $statusCode = 404;
            }

            return response()->json([

                'status' => false,

                'message' =>
                $e->getMessage()

            ], $statusCode);
        }
    }




    public function delete(
        int $dealId
    ) {
        try {

            $this->campaignService
                ->delete(
                    $dealId
                );

            return response()->json([

                'status' => true,

                'message' =>
                CampaignConstants
                ::CAMPAIGN_DELETED

            ], 200);
        } catch (Exception $e) {

            $statusCode = 422;

            if (
                $e->getMessage() ===
                CampaignConstants::CAMPAIGN_NOT_FOUND
            ) {
                $statusCode = 404;
            }

            return response()->json([

                'status' => false,

                'message' =>
                $e->getMessage()

            ], $statusCode);
        }
    }
}
