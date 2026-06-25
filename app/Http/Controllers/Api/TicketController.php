<?php

namespace App\Http\Controllers\Api;

use App\Services\TicketService;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Constants\TicketConstants;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\DeleteTicketRequest;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(
        TicketService $ticketService
    )
    {
        $this->ticketService =
            $ticketService;
    }

    public function store(
        TicketRequest $request
    )
    {
        $ticket =
            $this->ticketService
            ->create(
                $request->validated()
            );

        return response()->json([

            'status' => true,

            'message' =>
                TicketConstants::TICKET_CREATED,

            'data' => $ticket

        ], 201);
    }




    public function update(
    UpdateTicketRequest $request
)
{
    $ticket =
        $this->ticketService
            ->update(
                $request->id,
                $request->validated()
            );

    return response()->json([

        'status' => true,

        'message' =>
            TicketConstants
                ::TICKET_UPDATED,

        'data' => $ticket

    ], 200);
}





public function delete(
    DeleteTicketRequest $request
)
{
    $this->ticketService
        ->delete(
            $request->id
        );

    return response()->json([

        'status' => true,

        'message' =>
            TicketConstants
                ::TICKET_DELETED

    ], 200);
}





public function index(
    Request $request
)
{
    $result =
        $this->ticketService
            ->getTickets(
                $request->all()
            );

    return response()->json([

        'status' => true,

        'message' =>
            'Tickets fetched successfully',

        'data' =>
            $result['data'],

        'pagination' =>
            $result['pagination']

    ]);
}


public function show(
    int $id
)
{
    return response()->json([

        'status' => true,

        'message' =>
            'Ticket fetched successfully',

        'data' =>
            $this->ticketService
                ->getTicketById(
                    $id
                )
    ]);
}


}