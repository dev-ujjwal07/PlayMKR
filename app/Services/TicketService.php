<?php

namespace App\Services;

use Exception;
use App\Constants\TicketConstants;
use App\Interfaces\TicketRepositoryInterface;
use Illuminate\Support\Facades\Storage;


class TicketService
{
    protected $ticketRepository;

    public function __construct(
        TicketRepositoryInterface $ticketRepository
    )
    {
        $this->ticketRepository =
            $ticketRepository;
    }

    public function create(
        array $data
    )
    {
        $deal =
            $this->ticketRepository
            ->findDealById(
                $data['deal_id']
            );

        if (!$deal) {

            throw new Exception(
                TicketConstants::DEAL_NOT_FOUND
            );
        }

        $team =
            $this->ticketRepository
            ->findTeamById(
                $data['team_id']
            );

        if (!$team) {

            throw new Exception(
                TicketConstants::TEAM_NOT_FOUND
            );
        }

        $sponsor =
            $this->ticketRepository
            ->findSponsorById(
                $data['sponsor_id']
            );

        if (!$sponsor) {

            throw new Exception(
                TicketConstants::SPONSOR_NOT_FOUND
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

            $path =
                $file->storeAs(
                    'tickets',
                    $fileName,
                    'public'
                );

            $data['attachment'] =
                $path;
        }

        return $this->ticketRepository
            ->create($data);
    }

    public function update(
    int $id,
    array $data
)
{
    $ticket =
        $this->ticketRepository
            ->findById($id);

    if (!$ticket) {

        throw new Exception(
            TicketConstants::TICKET_NOT_FOUND
        );
    }

    if (
        isset($data['deal_id'])
    ) {

        $deal =
            $this->ticketRepository
                ->findDealById(
                    $data['deal_id']
                );

        if (!$deal) {

            throw new Exception(
                TicketConstants::DEAL_NOT_FOUND
            );
        }
    }

    if (
        isset($data['team_id'])
    ) {

        $team =
            $this->ticketRepository
                ->findTeamById(
                    $data['team_id']
                );

        if (!$team) {

            throw new Exception(
                TicketConstants::TEAM_NOT_FOUND
            );
        }
    }

    if (
        isset($data['sponsor_id'])
    ) {

        $sponsor =
            $this->ticketRepository
                ->findSponsorById(
                    $data['sponsor_id']
                );

        if (!$sponsor) {

            throw new Exception(
                TicketConstants::SPONSOR_NOT_FOUND
            );
        }
    }

    if (
        isset($data['attachment'])
    ) {

        if (
            $ticket->attachment
        ) {

            Storage::disk(
                'public'
            )->delete(
                $ticket->attachment
            );
        }

        $file =
            $data['attachment'];

        $fileName =
            time() . '_' .
            $file->getClientOriginalName();

        $path =
            $file->storeAs(
                'tickets',
                $fileName,
                'public'
            );

        $data['attachment'] =
            $path;
    }

    return $this->ticketRepository
        ->update(
            $id,
            $data
        );
}



public function delete(
    int $id
)
{
    $ticket =
        $this->ticketRepository
            ->findById($id);

    if (!$ticket) {

        throw new Exception(
            TicketConstants::TICKET_NOT_FOUND
        );
    }

    if (
        $ticket->attachment
    ) {

        Storage::disk(
            'public'
        )->delete(
            $ticket->attachment
        );
    }

    return $this->ticketRepository
        ->delete($id);
}




public function getTickets(
    array $data
)
{
    $tickets =
        $this->ticketRepository
            ->getTickets(

                $data['search']
                    ?? null,

                $data['per_page']
                    ?? 10
            );

    $tickets->getCollection()
        ->transform(function ($ticket) {

            return [

                'id' =>
                    $ticket->id,

                'deal_title' =>
                    optional(
                        $this->ticketRepository
                            ->findDealById(
                                $ticket->deal_id
                            )
                    )->deal_title,

                'team_name' =>
                    optional(
                        $this->ticketRepository
                            ->findTeamById(
                                $ticket->team_id
                            )
                    )->name,

                'sponsor_name' =>
                    optional(
                        $this->ticketRepository
                            ->findSponsorById(
                                $ticket->sponsor_id
                            )
                    )->name,

                'name' =>
                    $ticket->name,

                'priority' =>
                    $ticket->priority,

                'start_date' =>
                    $ticket->start_date,

                'attachment' =>
                    $ticket->attachment,

                'created_at' =>
                    $ticket->created_at
            ];
        });

    return [

        'data' =>
            $tickets->items(),

        'pagination' => [

            'current_page' =>
                $tickets->currentPage(),

            'last_page' =>
                $tickets->lastPage(),

            'per_page' =>
                $tickets->perPage(),

            'total' =>
                $tickets->total()
        ]
    ];
}


public function getTicketById(
    int $id
)
{
    $ticket =
        $this->ticketRepository
            ->getTicketById($id);

    if (!$ticket) {

        throw new Exception(
            'Ticket not found'
        );
    }

    return [

        'id' =>
            $ticket->id,

        'deal_title' =>
            optional(
                $this->ticketRepository
                    ->findDealById(
                        $ticket->deal_id
                    )
            )->deal_title,

        'team_name' =>
            optional(
                $this->ticketRepository
                    ->findTeamById(
                        $ticket->team_id
                    )
            )->name,

        'sponsor_name' =>
            optional(
                $this->ticketRepository
                    ->findSponsorById(
                        $ticket->sponsor_id
                    )
            )->name,

        'name' =>
            $ticket->name,

        'priority' =>
            $ticket->priority,

        'start_date' =>
            $ticket->start_date,

        'attachment' =>
            $ticket->attachment,

        'created_at' =>
            $ticket->created_at
    ];
}
}