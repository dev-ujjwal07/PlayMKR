<?php

namespace App\Repositories;

use App\Models\Deal;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\Sponsor;
use App\Interfaces\TicketRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User; 

class TicketRepository
implements TicketRepositoryInterface
{
    public function create(
        array $data
    )
    {
        return Ticket::create($data);
    }

    public function findDealById(
        int $id
    )
    {
        return Deal::find($id);
    }

    public function findTeamById(
        int $id
    )
    {
        return Team::find($id);
    }

    public function findSponsorById(
        int $id
    )
    {
        return Sponsor::find($id);
    }


    public function findById(
    int $id
)
{
    return Ticket::find($id);
}

public function findUserByEmail(
    string $email
)
{
    return User::where(
        'email',
        $email
    )->first();
}



public function update(
    int $id,
    array $data
)
{
    $ticket =
        Ticket::findOrFail($id);

    $ticket->update($data);

    return $ticket->fresh();
}



public function delete(
    int $id
)
{
    return Ticket::destroy($id);
}


public function getTickets(
    ?string $search,
    int $perPage
)
{
    $query = Ticket::query();

    if ($search) {

        $dealIds = Deal::where(
            'deal_title',
            'like',
            "%{$search}%"
        )->pluck('id');

        $teamIds = Team::where(
            'name',
            'like',
            "%{$search}%"
        )->pluck('id');

        $sponsorIds = Sponsor::where(
            'name',
            'like',
            "%{$search}%"
        )->pluck('id');

        $query->where(function ($q)
        use (
            $dealIds,
            $teamIds,
            $sponsorIds
        ) {

            $q->whereIn(
                'deal_id',
                $dealIds
            )
            ->orWhereIn(
                'team_id',
                $teamIds
            )
            ->orWhereIn(
                'sponsor_id',
                $sponsorIds
            );
        });
    }

    return $query
        ->latest('id')
        ->paginate($perPage);
}


public function getTicketStats()
{
    return [

        'total_tickets' =>

            Ticket::count(),

        'assigned_tickets' =>

            Ticket::where(
                'ticket_status',
                'Assigned'
            )->count(),

        'pending_tickets' =>

            Ticket::where(
                'ticket_status',
                'Pending'
            )->count(),

        'used_tickets' =>

            Ticket::where(
                'ticket_status',
                'Used'
            )->count()
    ];
}
              
public function getTicketById(
    int $id
)
{
    return Ticket::find($id);
}



public function getSponsorTickets(
    int $sponsorId,
    array $filters
)
{
    $query = Ticket::where(
        'sponsor_id',
        $sponsorId
    );

    if (!empty($filters['search'])) {

        $search = $filters['search'];

        $query->where(function ($q) use ($search) {

            $q->where(
                'ticket_id',
                'like',
                "%{$search}%"
            )
            ->orWhere(
                'name',
                'like',
                "%{$search}%"
            );
        });
    }

    if (!empty($filters['status'])) {

        $query->where(
            'status',
            $filters['status']
        );
    }

    return $query
        ->latest('id')
        ->paginate(
            $filters['per_page'] ?? 10
        );
}

public function getSponsorTotalTickets(
    int $sponsorId
)
{
    return Ticket::where(
        'sponsor_id',
        $sponsorId
    )->sum('number_of_tickets');
}




public function getTicketByIdAndSponsor(
    int $ticketId,
    int $sponsorId
)
{
    return Ticket::where(

        'id',
        $ticketId

    )->where(

        'sponsor_id',
        $sponsorId

    )->first();
}




public function getSponsorTicketStats(
    int $sponsorId
)
{
    return Ticket::where(
            'sponsor_id',
            $sponsorId
        )
        ->selectRaw("
            SUM(number_of_tickets) as total_tickets,

            SUM(
                CASE
                    WHEN ticket_status = 'Used'
                    THEN number_of_tickets
                    ELSE 0
                END
            ) as used
        ")
        ->first();
}




public function updateStatus(

    int $id,

    string $status

)
{
    $ticket =

        Ticket::findOrFail($id);

    $ticket->status =

        $status;

    $ticket->save();

    return $ticket->fresh();
}


public function getInternalTeamTickets(
    int $teamId,
    array $filters
)
{
    $query = Ticket::with([
        'sponsor',
        'reports'
    ])
    ->where(
        'team_id',
        $teamId
    );

    if (!empty($filters['search'])) {

        $search = $filters['search'];

        $query->where(function ($q) use ($search) {

            $q->where(
                'ticket_id',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'priority',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'status',
                'like',
                "%{$search}%"
            )

            ->orWhereHas(
                'sponsor',
                function ($sponsor) use ($search) {

                    $sponsor->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        });
    }

    if (!empty($filters['status'])) {

        $query->where(
            'status',
            $filters['status']
        );
    }

    return $query
        ->latest()
        ->paginate(
            $filters['per_page'] ?? 10
        );
}


public function getInternalTeamTicketById(
    int $ticketId,
    int $teamId
)
{
    return Ticket::with([

        'deal',

        'sponsor',

        'reports'

    ])

    ->where(
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