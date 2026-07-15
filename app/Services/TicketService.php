<?php

namespace App\Services;

use Exception;
use App\Constants\TicketConstants;
use App\Interfaces\TicketRepositoryInterface;
use App\Interfaces\EventRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\SponsorApplicationRepositoryInterface;
use App\Notifications\CommonNotification;

class TicketService
{
  protected $ticketRepository;
protected $sponsorRepository;
protected $eventRepository;

public function __construct(
    TicketRepositoryInterface $ticketRepository,
    SponsorApplicationRepositoryInterface $sponsorRepository,
    EventRepositoryInterface $eventRepository
)
{
    $this->ticketRepository = $ticketRepository;
    $this->sponsorRepository = $sponsorRepository;
    $this->eventRepository =$eventRepository;
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

$user = request()->user();

$data['admin_id'] = $user->id;



        $ticket = $this->ticketRepository
    ->create($data);

$ticket->ticket_id =
    '#TK' . str_pad(
        $ticket->id,
        3,
        '0',
        STR_PAD_LEFT
    );

$ticket->save();


/*
|--------------------------------------------------------------------------
| Notify Sponsor
|--------------------------------------------------------------------------
*/

$sponsorUser =
    $this->ticketRepository
        ->findUserByEmail(
            $sponsor->email
        );

if ($sponsorUser) {

    $sponsorUser->notify(

        new CommonNotification(

            'New Ticket Created',

            'A new ticket (' .
            $ticket->ticket_id .
            ') has been created for you.',

            'ticket_created',

            $ticket->id,

            '/sponsor/tickets'

        )

    );
}



/*
|--------------------------------------------------------------------------
| Notify Assigned Team
|--------------------------------------------------------------------------
*/

$teamUser =
    $this->ticketRepository
        ->findUserByEmail(
            $team->email
        );

if ($teamUser) {

    $teamUser->notify(

        new CommonNotification(

            'New Ticket Assigned',

            'Ticket ' .
            $ticket->ticket_id .
            ' has been assigned to you.',

            'ticket_assigned',

            $ticket->id,

            '/internal-team/tickets'

        )

    );
}

return $ticket;
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

$stats =
    $this->ticketRepository
        ->getTicketStats();
             

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

                    'ticket_status' =>
                    $ticket->ticket_status,

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

        'stats' =>

        $stats,

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






public function getSponsorTickets(
    array $filters
)
{
    $user = request()->user();

    $sponsor = $this->sponsorRepository
        ->findSponsorByEmail(
            $user->email
        );

    if (!$sponsor) {

        throw new Exception(
            'Sponsor not found'
        );
    }

    $tickets = $this->ticketRepository
        ->getSponsorTickets(
            $sponsor->id,
            $filters
        );

        $ticketStats =
    $this->ticketRepository
        ->getSponsorTicketStats(
            $sponsor->id
        );

$upcomingEvents =
    $this->eventRepository
        ->getUpcomingEventsCount();

$totalTickets =
    (int) (
        $ticketStats->total_tickets ?? 0
    );

$used =
    (int) (
        $ticketStats->used ?? 0
    );

$remaining =
    $totalTickets - $used;

        

    $data = collect(
        $tickets->items()
    )->map(function ($ticket) {

        return [

            'id' => $ticket->id,

            'ticket_id' =>
                $ticket->ticket_id,

            'deal_id' =>
                $ticket->deal_id,

            'team_id' =>
                $ticket->team_id,

            'sponsor_id' =>
                $ticket->sponsor_id,

            'name' =>
                $ticket->name,

            'number_of_tickets' =>
                $ticket->number_of_tickets,

            'priority' =>
                $ticket->priority,

            'status' =>
                $ticket->status,

            'start_date' =>
                $ticket->start_date,

            'attachment' =>
                $ticket->attachment
                    ? asset(
                        'storage/'.$ticket->attachment
                    )
                    : null,
        ];

    });

    return [

       'stats' => [

    'total_tickets' =>
        $totalTickets,

    'used' =>
        $used,

    'remaining' =>
        $remaining,

    'upcoming_events' =>
        $upcomingEvents
],

        'data' =>
            $data,

        'pagination' => [

            'current_page' =>
                $tickets->currentPage(),

            'last_page' =>
                $tickets->lastPage(),

            'per_page' =>
                $tickets->perPage(),

            'total' =>
                $tickets->total(),
        ]
    ];
}



public function updateSponsorTicketStatus(
    int $ticketId,
    array $data
)
{
    $user = request()->user();

    if (!$user) {

        throw new Exception(
            'Unauthenticated.'
        );
    }

    $sponsor = $this->sponsorRepository
        ->findSponsorByEmail(
            $user->email
        );

    if (!$sponsor) {

        throw new Exception(
            TicketConstants::SPONSOR_NOT_FOUND
        );
    }

    $ticket = $this->ticketRepository
        ->getTicketByIdAndSponsor(
            $ticketId,
            $sponsor->id
        );

    if (!$ticket) {

        throw new Exception(
            'You are not authorized to update this ticket'
        );
    }

    return $this->ticketRepository
        ->updateStatus(
            $ticket->id,
            $data['status']
        );
}
}