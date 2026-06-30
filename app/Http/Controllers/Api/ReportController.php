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

public function internalReports(
    Request $request
)
{
    $result =
        $this->reportService
            ->getInternalTeamReports(
                $request->all()
            );

    return response()->json([

        'status' => true,

        'message' =>
            'Internal Team Reports fetched successfully',

        'data' =>
            $result['data'],

        'pagination' =>
            $result['pagination']

    ]);
}

public function internalReport(
    int $id
)
{
    return response()->json([

        'status' => true,

        'message' =>
            'Internal Team Report fetched successfully',

        'data' =>
            $this->reportService
                ->getInternalTeamReportById(
                    $id
                )

    ]);
}


public function resolveInternalTeamReport(
    int $id
)
{
    $report =
        $this->reportService
            ->updateInternalTeamReportStatus(
                $id
            );

    return response()->json([

        'status' => true,

        'message' =>
            'Report resolved successfully',

        'data' =>
            $report

    ]);
}



public function updateInternalTeamTicketReport(
    Request $request,
    int $id
)
{
    $validated =
        $request->validate([

            'internal_team_description' =>
                'required|string',

            'attachment' =>
                'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120'
        ]);

    $response =
        $this->reportService
            ->updateInternalTeamTicketReport(

                $id,

                $validated
            );

    return response()->json(
        $response,
        200
    );
}

}