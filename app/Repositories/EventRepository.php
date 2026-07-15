<?php

namespace App\Repositories;

use App\Models\Event;
use App\Interfaces\EventRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{
    public function create(
        array $data
    )
    {
        return Event::create($data);
    }


    public function findById(
    int $id
)
{
    return Event::find($id);
}

public function update(
    int $id,
    array $data
)
{
    $event = Event::findOrFail($id);

    $event->update($data);

    return $event->fresh();
}


public function delete(
    int $id
)
{
    return Event::destroy(
        $id
    );
}


public function getEvents(
    array $filters
)
{
    $query =
        Event::query();

    if (
        !empty($filters['search'])
    ) {

        $query->where(

            'event_name',

            'like',

            '%' . $filters['search'] . '%'
        );
    }

    if (
        !empty($filters['start_date'])
    ) {

        $query->whereDate(

            'start_date',

            '>=',

            $filters['start_date']
        );
    }

    if (
        !empty($filters['end_date'])
    ) {

        $query->whereDate(

            'end_date',

            '<=',

            $filters['end_date']
        );
    }

    $today = now()->toDateString();

    if (
        !empty($filters['filter'])
    ) {

        switch (
            $filters['filter']
        ) {

            case 'today':

                $query->whereDate(
                    'start_date',
                    '<=',
                    $today
                )->whereDate(
                    'end_date',
                    '>=',
                    $today
                );

                break;

            case 'upcoming':

                $query->whereDate(
                    'start_date',
                    '>',
                    $today
                );

                break;

            case 'past':

                $query->whereDate(
                    'end_date',
                    '<',
                    $today
                );

                break;

            case 'all':

            default:

                break;
        }
    }

    return $query
        ->latest('id')
        ->paginate(
            $filters['per_page'] ?? 10
        );
}

public function getUpcomingEventsCount()
{
    return Event::whereDate(
            'start_date',
            '>=',
            now()->toDateString()
        )
        ->count();
}


public function getSponsorUpcomingEvents()
{
    return Event::whereDate(
            'start_date',
            '>',
            now()->toDateString()
        )
        ->latest('start_date')
        ->paginate(100);
}


public function getSponsorEventStats()
{
    $today = now()->toDateString();

    return Event::selectRaw("
        COUNT(*) as total_events,

        SUM(
            CASE
                WHEN start_date > ?
                THEN 1
                ELSE 0
            END
        ) as upcoming_events,

        SUM(
            CASE
                WHEN end_date < ?
                THEN 1
                ELSE 0
            END
        ) as past_events,

        SUM(
            CASE
                WHEN start_date <= ?
                AND end_date >= ?
                THEN 1
                ELSE 0
            END
        ) as today_events
    ", [

        $today,
        $today,
        $today,
        $today

    ])->first();
}
}