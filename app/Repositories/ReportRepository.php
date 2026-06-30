<?php

namespace App\Repositories;

use App\Models\Report;
use App\Models\Ticket;
use App\Models\Sponsor;
use App\Interfaces\ReportRepositoryInterface;
use App\Models\Team;


class ReportRepository
implements ReportRepositoryInterface
{
    public function create(
        array $data
    )
    {
        return Report::create($data);
    }

   public function findTicketById(
    int $id
)
{
    return Ticket::findOrFail($id);
}

    public function findSponsorByEmail(
        string $email
    )
    {
        return Sponsor::where(
            'email',
            $email
        )->first();
    }

public function findTicketBySponsor(
    int $ticketId,
    int $sponsorId
)
{
    return Ticket::where(
        'id',
        $ticketId
    )
    ->where(
        'sponsor_id',
        $sponsorId
    )
  ->firstOrFail();
}


// Sponsor Reports

public function getSponsorReports(
    int $sponsorId,
    array $filters
)
{
    $query = Report::with([
        'ticket',
        'ticket.deal',
        'ticket.team',
        'ticket.sponsor'
    ])
    ->where(
        'sponsor_id',
        $sponsorId
    );

    if (
        !empty($filters['search'])
    ) {

        $search =
            $filters['search'];

        $query->where(function ($q)
        use ($search) {

            $q->whereHas(
                'ticket',
                function ($ticket)
                use ($search) {

                    $ticket->where(
                        'ticket_id',
                        'like',
                        "%{$search}%"
                    );
                }
            )

            ->orWhere(
                'title',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'status',
                'like',
                "%{$search}%"
            );
        });
    }

    return $query
        ->latest()
        ->paginate(
            $filters['per_page']
                ?? 10
        );
}

//Sponsor Single Report

public function getSponsorReportById(
    int $id,
    int $sponsorId
)
{
    return Report::with([
        'ticket',
        'ticket.deal',
        'ticket.team',
        'ticket.sponsor'
    ])

    ->where(
        'id',
        $id
    )

    ->where(
        'sponsor_id',
        $sponsorId
    )

  ->findOrFail($id);
}

//Admin Reports
public function getReports(
    array $filters
)
{
    $query = Report::with([
        'ticket',
        'ticket.deal',
        'ticket.team',
        'ticket.sponsor'
    ]);

    if (
        !empty($filters['search'])
    ) {

        $search =
            $filters['search'];

        $query->where(function ($q)
        use ($search) {

            $q->where(
                'title',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'status',
                'like',
                "%{$search}%"
            )

            ->orWhereHas(
                'ticket',
                function ($ticket)
                use ($search) {

                    $ticket->where(
                        'ticket_id',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        });
    }

    return $query
        ->latest()
        ->paginate(
            $filters['per_page']
                ?? 10
        );
}

// Admin Single Report
public function getReportById(
    int $id
)
{
    return Report::with([
        'ticket',
        'ticket.deal',
        'ticket.team',
        'ticket.sponsor'
    ])
    ->find($id);
}

//Update
public function update(
    int $id,
    array $data
)
{
    $report =
        Report::findOrFail($id);

    $report->update(
        $data
    );

    return $report->fresh();
}

//Delete
public function delete(
    int $id
)
{
    return Report::destroy(
        $id
    );
}



public function getInternalTeamReports(
    int $teamId,
    array $filters
)
{
    $query = Report::with([
        'ticket',
        'ticket.sponsor'
    ])
    ->where(
        'team_id',
        $teamId
    );

    if (!empty($filters['search'])) {

        $search =
            $filters['search'];

        $query->where(function ($q)
        use ($search) {

            $q->where(
                'status',
                'like',
                "%{$search}%"
            )

            ->orWhereHas(
                'ticket',
                function ($ticket)
                use ($search) {

                    $ticket->where(
                        'ticket_id',
                        'like',
                        "%{$search}%"
                    );
                }
            )

            ->orWhereHas(
                'ticket.sponsor',
                function ($sponsor)
                use ($search) {

                    $sponsor->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        });
    }

    return $query
        ->latest()
        ->paginate(
            $filters['per_page']
                ?? 10
        );
}


public function getInternalTeamReportById(
    int $id,
    int $teamId
)
{
    return Report::with([
        'ticket',
        'ticket.sponsor'
    ])
    ->where(
        'team_id',
        $teamId
    )
    ->where(
        'id',
        $id
    )
    ->first();
}




public function updateInternalTeamReportStatus(
    int $id,
    int $teamId,
    array $data
)
{
    $report = Report::where(
        'id',
        $id
    )
    ->where(
        'team_id',
        $teamId
    )
    ->first();

    if (!$report) {
        return null;
    }

    $report->update($data);

    return $report->fresh();
}


public function findTeamByEmail(
    string $email
)
{
    return Team::where(
        'email',
        $email
    )->first();
}



public function updateInternalTeamTicketReport(
    int $ticketId,
    int $teamId,
    array $data
)
{
    $ticket = Ticket::where(
            'id',
            $ticketId
        )
        ->where(
            'team_id',
            $teamId
        )
        ->firstOrFail();

    $ticket->update($data);

    return $ticket->fresh();
}


public function findTicketByTeam(
    int $ticketId,
    int $teamId
)
{
    return Ticket::where(
        'id',
        $ticketId
    )
    ->where(
        'team_id',
        $teamId
    )
    ->first();
}


}