<?php

namespace App\Repositories;

use App\Models\Deal;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\Sponsor;
use App\Interfaces\TicketRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


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

public function getTicketById(
    int $id
)
{
    return Ticket::find($id);
}
}