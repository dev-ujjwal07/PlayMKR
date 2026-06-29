<?php

namespace App\Services;

use Exception;
use App\Constants\ReportConstants;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\ReportRepositoryInterface;

class ReportService
{
    protected $reportRepository;

    public function __construct(
        ReportRepositoryInterface $reportRepository
    )
    {
        $this->reportRepository =
            $reportRepository;
    }

    public function create(
        array $data
    )
    {
        $user =
            request()->user();

        if (!$user) {

            throw new Exception(
                'Unauthenticated.'
            );
        }

        $sponsor =
            $this->reportRepository
                ->findSponsorByEmail(
                    $user->email
                );

        if (!$sponsor) {

            throw new Exception(
                ReportConstants
                    ::SPONSOR_NOT_FOUND
            );
        }

        $ticket =
            $this->reportRepository
                ->findTicketBySponsor(

                    $data['ticket_id'],

                    $sponsor->id
                );

        if (!$ticket) {

            throw new Exception(

                ReportConstants
                    ::UNAUTHORIZED_TICKET

            );
        }

        if (
            isset($data['attachment'])
        ) {

            $file =
                $data['attachment'];

            $fileName =
                time() .
                '_' .
                $file->getClientOriginalName();

            $data['attachment'] =
                $file->storeAs(

                    'reports',

                    $fileName,

                    'public'
                );
        }

        $data['sponsor_id'] =
            $sponsor->id;

        $data['team_id'] =
            $ticket->team_id;

        $data['status'] =
            'pending';

        return $this->reportRepository
            ->create($data);
    }


    // Sponsor Reports List

    public function getSponsorReports(
    array $filters
)
{
    $user =
        request()->user();

    $sponsor =
        $this->reportRepository
            ->findSponsorByEmail(
                $user->email
            );

    if (!$sponsor) {

        throw new Exception(
            ReportConstants::SPONSOR_NOT_FOUND
        );
    }

    $reports =
        $this->reportRepository
            ->getSponsorReports(
                $sponsor->id,
                $filters
            );

    $data =
        collect(
            $reports->items()
        )->map(function ($report) {

            return [

                'id' =>
                    $report->id,

                'ticket_id' =>
                    optional(
                        $report->ticket
                    )->ticket_id,

                'report_title' =>
                    $report->title,

                'status' =>
                    $report->status
            ];
        });

    return [

        'data' =>
            $data,

        'pagination' => [

            'current_page' =>
                $reports->currentPage(),

            'last_page' =>
                $reports->lastPage(),

            'per_page' =>
                $reports->perPage(),

            'total' =>
                $reports->total()
        ]
    ];
}


//Sponsor Single Report

public function getSponsorReportById(
    int $id
)
{
    $user =
        request()->user();

    $sponsor =
        $this->reportRepository
            ->findSponsorByEmail(
                $user->email
            );

    if (!$sponsor) {

        throw new Exception(
            ReportConstants::SPONSOR_NOT_FOUND
        );
    }

    $report =
        $this->reportRepository
            ->getSponsorReportById(

                $id,

                $sponsor->id
            );

    if (!$report) {

        throw new Exception(
            ReportConstants::REPORT_NOT_FOUND
        );
    }

    return [

        'id' =>
            $report->id,

        'ticket_id' =>
            optional(
                $report->ticket
            )->ticket_id,

        'report_title' =>
            $report->title,

        'description' =>
            $report->description,

        'attachment' =>
            $report->attachment,

        'status' =>
            $report->status,

        'created_at' =>
            $report->created_at
    ];
}

//Admin Reports List

public function getReports(
    array $filters
)
{
    $reports =
        $this->reportRepository
            ->getReports(
                $filters
            );

    $data =
        collect(
            $reports->items()
        )->map(function ($report) {

            return [

                'report_id' =>
                    $report->id,

                'ticket_id' =>
                    optional(
                        $report->ticket
                    )->ticket_id,

                'deal_title' =>
                    optional(
                        optional(
                            $report->ticket
                        )->deal
                    )->deal_title,

                'team_name' =>
                    optional(
                        optional(
                            $report->ticket
                        )->team
                    )->name,

                'sponsor_name' =>
                    optional(
                        optional(
                            $report->ticket
                        )->sponsor
                    )->name,

                'report_title' =>
                    $report->title,

                'description' =>
                    $report->description,

                'status' =>
                    $report->status,

                'created_at' =>
                    $report->created_at,

                'ticket' => [

                    'id' =>
                        $report->ticket?->id,

                    'ticket_id' =>
                        $report->ticket?->ticket_id,

                    'name' =>
                        $report->ticket?->name,

                    'number_of_tickets' =>
                        $report->ticket?->number_of_tickets,

                    'priority' =>
                        $report->ticket?->priority,

                    'status' =>
                        $report->ticket?->status,

                    'start_date' =>
                        $report->ticket?->start_date,

                    'attachment' =>
                        $report->ticket?->attachment,

                    'internal_team_description' =>
                        $report->ticket?->internal_team_description
                ]
            ];
        });

    return [

        'data' =>
            $data,

        'pagination' => [

            'current_page' =>
                $reports->currentPage(),

            'last_page' =>
                $reports->lastPage(),

            'per_page' =>
                $reports->perPage(),

            'total' =>
                $reports->total()
        ]
    ];
}

//Admin Single Report

public function getReportById(
    int $id
)
{
    $report =
        $this->reportRepository
            ->getReportById(
                $id
            );

    if (!$report) {

        throw new Exception(
            ReportConstants::REPORT_NOT_FOUND
        );
    }

    return [

        'report_id' =>
            $report->id,

        'ticket_id' =>
            optional(
                $report->ticket
            )->ticket_id,

        'deal_title' =>
            optional(
                optional(
                    $report->ticket
                )->deal
            )->deal_title,

        'team_name' =>
            optional(
                optional(
                    $report->ticket
                )->team
            )->name,

        'sponsor_name' =>
            optional(
                optional(
                    $report->ticket
                )->sponsor
            )->name,

        'report_title' =>
            $report->title,

        'description' =>
            $report->description,

        'attachment' =>
            $report->attachment,

        'status' =>
            $report->status,

        'created_at' =>
            $report->created_at,

        'ticket' => $report->ticket
    ];
}

// Status Update 

public function updateStatus(
    int $id
)
{
    $report =
        $this->reportRepository
            ->getReportById(
                $id
            );

    if (!$report) {

        throw new Exception(
            ReportConstants::REPORT_NOT_FOUND
        );
    }

    if (
        $report->status
        === 'resolved'
    ) {

        throw new Exception(
            'Report already resolved.'
        );
    }

    return $this->reportRepository
        ->update(

            $id,

            [

                'status' =>
                    'resolved'
            ]
        );
}


// Sponsor Delete

public function sponsorDelete(
    int $id
)
{
    $user =
        request()->user();

    $sponsor =
        $this->reportRepository
            ->findSponsorByEmail(
                $user->email
            );

    if (!$sponsor) {

        throw new Exception(
            ReportConstants::SPONSOR_NOT_FOUND
        );
    }

    $report =
        $this->reportRepository
            ->getSponsorReportById(
                $id,
                $sponsor->id
            );

    if (!$report) {

        throw new Exception(
            ReportConstants::REPORT_NOT_FOUND
        );
    }

    if ($report->attachment) {

        Storage::disk('public')
            ->delete(
                $report->attachment
            );
    }

    return $this->reportRepository
        ->delete($id);
}

//Admin Delete
public function delete(
    int $id
)
{
    $report =
        $this->reportRepository
            ->getReportById($id);

    if (!$report) {

        throw new Exception(
            ReportConstants::REPORT_NOT_FOUND
        );
    }

    if ($report->attachment) {

        Storage::disk('public')
            ->delete(
                $report->attachment
            );
    }

    return $this->reportRepository
        ->delete($id);
}

}