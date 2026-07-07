<?php

namespace App\Http\Controllers\Api;

use App\Services\EventService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(
        EventService $eventService
    )
    {
        $this->eventService =
            $eventService;
    }

    public function createEvent(
    CreateEventRequest $request
)
{
    return response()->json(
        $this->eventService->createEvent(
            $request->validated()
        )
    );
}


public function updateEvent(
    UpdateEventRequest $request,
    int $id
)
{
    return response()->json(

        $this->eventService
            ->updateEvent(

                $id,

                $request->validated()
            )
    );
}


public function deleteEvent(
    int $id
)
{
    return response()->json(

        $this->eventService
            ->deleteEvent(
                $id
            )

    );
}


public function getEvents(
    Request $request
)
{
    return response()->json(

        $this->eventService
            ->getEvents(

                $request->all()

            )

    );
}

}