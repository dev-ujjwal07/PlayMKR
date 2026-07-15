<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\EventRepositoryInterface;
use App\Constants\EventConstants;

class EventService
{
    protected $eventRepository;

    public function __construct(
        EventRepositoryInterface $eventRepository
    )
    {
        $this->eventRepository = $eventRepository;
    }

    public function createEvent(
        array $data
    )
    {

        return DB::transaction(function () use ($data) {

            if (isset($data['event_image'])) {

                $file = $data['event_image'];

                $fileName =
                    time().'_'.$file->getClientOriginalName();

                $imagePath =
                    $file->storeAs(
                        'events',
                        $fileName,
                        'public'
                    );

                $data['event_image'] =
                    $imagePath;
            }

            $event =
                $this->eventRepository
                    ->create($data);

            return [

                'status' => true,

'message' =>
    EventConstants::EVENT_CREATED,

                'data' => [

                    'id' => $event->id,

                    'event_name' =>
                        $event->event_name,

                    'start_date' =>
                        $event->start_date,

                    'end_date' =>
                        $event->end_date,

                    'start_time' =>
                        $event->start_time,

                    'end_time' =>
                        $event->end_time,

                    'event_description' =>
                        $event->event_description,

                    'event_image' =>
                        $event->event_image
                        ? asset('storage/'.$event->event_image)
                        : null,

                    'created_at' =>
                        $event->created_at
                ]
            ];
        });
    }





    public function updateEvent(
    int $id,
    array $data
)
{
    return DB::transaction(function () use ($id, $data) {

        $event =
            $this->eventRepository
                ->findById($id);

        if (!$event) {

        throw new Exception(
    EventConstants::EVENT_NOT_FOUND
);
        }

        if (isset($data['event_image'])) {

            if (
                $event->event_image &&
                Storage::disk('public')->exists($event->event_image)
            ) {
                Storage::disk('public')
                    ->delete($event->event_image);
            }

            $file = $data['event_image'];

            $fileName =
                time().'_'.$file->getClientOriginalName();

            $imagePath =
                $file->storeAs(
                    'events',
                    $fileName,
                    'public'
                );

            $data['event_image'] =
                $imagePath;
        }

        $event =
            $this->eventRepository
                ->update($id, $data);

        return [

            'status' => true,

        'message' =>
         EventConstants::EVENT_UPDATED,

            'data' => [

                'id' => $event->id,

                'event_name' => $event->event_name,

                'start_date' => $event->start_date,

                'end_date' => $event->end_date,

                'start_time' => $event->start_time,

                'end_time' => $event->end_time,

                'event_description' =>
                    $event->event_description,

                'event_image' =>
                    $event->event_image
                        ? asset('storage/'.$event->event_image)
                        : null,

                'created_at' =>
                    $event->created_at,

                'updated_at' =>
                    $event->updated_at
            ]
        ];
    });
}



public function deleteEvent(
    int $id
)
{
    return DB::transaction(

        function () use ($id) {

            $event =
                $this->eventRepository
                    ->findById($id);

            if (!$event) {

                throw new Exception(
                    EventConstants::EVENT_NOT_FOUND
                );
            }

            if (

                $event->event_image &&

                Storage::disk('public')->exists(

                    $event->event_image

                )

            ) {

                Storage::disk('public')
                    ->delete(

                        $event->event_image

                    );
            }

            $this->eventRepository
                ->delete($id);

            return [

                'status' => true,

                'message' =>
                    EventConstants::EVENT_DELETED
            ];
        }
    );
}



public function getEvents(
    array $filters
)
{
    $events =
        $this->eventRepository
            ->getEvents(
                $filters
            );

    $formattedData =
        collect(
            $events->items()
        )->map(

            function ($event) {

                return [

                    'id' =>
                        $event->id,

                    'event_name' =>
                        $event->event_name,

                    'start_date' =>
                        $event->start_date,

                    'end_date' =>
                        $event->end_date,

                    'start_time' =>
                        $event->start_time,

                    'end_time' =>
                        $event->end_time,

                    'event_description' =>
                        $event->event_description,

                    'event_image' =>
                        $event->event_image
                        ? asset(
                            'storage/' .
                            $event->event_image
                        )
                        : null,

                    'created_at' =>
                        $event->created_at,

                    'updated_at' =>
                        $event->updated_at
                ];
            }

        );

    return [

        'status' => true,

        'message' =>
            EventConstants::EVENTS_FETCHED,

        'data' =>
            $formattedData,

        'current_page' =>
            $events->currentPage(),

        'last_page' =>
            $events->lastPage(),

        'per_page' =>
            $events->perPage(),

        'total' =>
            $events->total()
    ];
}



public function getSponsorUpcomingEvents(){

$events =
    $this->eventRepository
        ->getSponsorUpcomingEvents();

$stats =
    $this->eventRepository
        ->getSponsorEventStats();

        $data =
    collect(
        $events->items()
    )->map(function ($event) {

        return [

            'id' =>
                $event->id,

            'event_name' =>
                $event->event_name,

            'start_date' =>
                $event->start_date,

            'end_date' =>
                $event->end_date,

            'start_time' =>
                $event->start_time,

            'end_time' =>
                $event->end_time,

            'event_description' =>
                $event->event_description,

            'event_image' =>
                $event->event_image
                    ? asset(
                        'storage/'.$event->event_image
                    )
                    : null
        ];
    });

    return [

    'status' => true,

    'message' =>
        'Upcoming events fetched successfully',

    'stats' => [

        'total_events' =>
            (int) $stats->total_events,

        'upcoming_events' =>
            (int) $stats->upcoming_events,

        'past_events' =>
            (int) $stats->past_events,

        'today_events' =>
            (int) $stats->today_events,
    ],

    'data' =>
        $data,

    'pagination' => [

        'current_page' =>
            $events->currentPage(),

        'last_page' =>
            $events->lastPage(),

        'per_page' =>
            $events->perPage(),

        'total' =>
            $events->total()
    ]
];

}




















}