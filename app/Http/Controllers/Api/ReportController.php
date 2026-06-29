<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Services\ReportService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Constants\ReportConstants;
use App\Http\Requests\DeleteReportRequest;
use App\Http\Requests\ReportStatusRequest;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(
        ReportService $reportService
    )
    {
        $this->reportService =
            $reportService;
    }

    public function store(
        ReportRequest $request
    )
    {
        try {

            $report =
                $this->reportService
                    ->create(
                        $request->validated()
                    );

            return response()->json([

                'status' => true,

                'message' =>
                    ReportConstants::REPORT_CREATED,

                'data' =>
                    $report

            ], 201);

        } catch (Exception $e) {

            return response()->json([

                'status' => false,

                'message' =>
                    $e->getMessage()

            ], 400);
        }
    }



    public function sponsorIndex(
    Request $request
)
{
    return response()->json([

        'status' => true,

        'message' =>
            'Reports fetched successfully',

        'data' =>
            $this->reportService
                ->getSponsorReports(
                    $request->all()
                )
    ]);
}


public function sponsorShow(
    int $id
)
{
    return response()->json([

        'status' => true,

        'message' =>
            'Report fetched successfully',

        'data' =>
            $this->reportService
                ->getSponsorReportById(
                    $id
                )
    ]);
}

public function index(
    Request $request
)
{
    return response()->json([

        'status' => true,

        'message' =>
            'Reports fetched successfully',

        'data' =>
            $this->reportService
                ->getReports(
                    $request->all()
                )
    ]);
}

public function show(
    int $id
)
{
    return response()->json([

        'status' => true,

        'message' =>
            'Report fetched successfully',

        'data' =>
            $this->reportService
                ->getReportById(
                    $id
                )
    ]);
}

public function updateStatus(
    int $id
)
{
    return response()->json([

        'status' => true,

        'message' =>
            'Report resolved successfully',

        'data' =>
            $this->reportService
                ->updateStatus($id)
    ]);
}

public function sponsorDelete(
    int $id
)
{
    $this->reportService
        ->sponsorDelete($id);

    return response()->json([

        'status' => true,

        'message' =>
            'Report deleted successfully'
    ]);
}

public function delete(
    int $id
)
{
    $this->reportService
        ->delete($id);

    return response()->json([

        'status' => true,

        'message' =>
            'Report deleted successfully'
    ]);
}
}