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

}