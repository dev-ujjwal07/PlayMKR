<?php
namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Constants\DeliverableConstants;
use App\Interfaces\DeliverableRepositoryInterface;
use App\Interfaces\SponsorApplicationRepositoryInterface;

class DeliverableService
{
    protected $deliverableRepository;
    protected $sponsorRepository;

    public function __construct(
        DeliverableRepositoryInterface $deliverableRepository,
        SponsorApplicationRepositoryInterface $sponsorRepository
    )
    {
        $this->deliverableRepository =
            $deliverableRepository;

        $this->sponsorRepository =
            $sponsorRepository;
    }

public function create(
    array $data
)
{
    $assignedSponsor =
        $this->deliverableRepository
            ->findSponsorByName(
                $data['assigned_to']
            );

    if (!$assignedSponsor) {

        throw new Exception(
            DeliverableConstants::SPONSOR_NOT_FOUND
        );
    }

    $team =
        $this->deliverableRepository
            ->findTeamById(
                $data['team_id']
            );

    if (!$team) {

        throw new Exception(
            'Team not found'
        );
    }

    $data['assigned_to'] =
        $assignedSponsor->id;

    $data['status_updated_at'] =
        Carbon::now();

    if (
        isset($data['attachment'])
    ) {

        $data['attachment'] =
            $data['attachment']->store(
                'deliverables',
                'public'
            );
    }

    return $this->deliverableRepository
        ->create($data);
}


public function delete(
    int $id
)
{
    $deliverable =
        $this->deliverableRepository
            ->findById($id);

    if (!$deliverable) {

        throw new Exception(
            DeliverableConstants
                ::DELIVERABLE_NOT_FOUND
        );
    }

    $this->deliverableRepository
        ->delete($id);
}


public function update(
    int $id,
    array $data
)
{
    $deliverable =
        $this->deliverableRepository
            ->findById($id);

    if (!$deliverable) {

        throw new Exception(
            DeliverableConstants
                ::DELIVERABLE_NOT_FOUND
        );
    }

    if (
        isset($data['assigned_to'])
    ) {

        $assignedSponsor =
            $this->deliverableRepository
                ->findSponsorByName(
                    $data['assigned_to']
                );

        if (!$assignedSponsor) {

            throw new Exception(
                DeliverableConstants
                    ::SPONSOR_NOT_FOUND
            );
        }

        $data['assigned_to'] =
            $assignedSponsor->id;
    }

    if (
        isset($data['team_id'])
    ) {

        $team =
            $this->deliverableRepository
                ->findTeamById(
                    $data['team_id']
                );

        if (!$team) {

            throw new Exception(
                'Team not found'
            );
        }
    }

    if (
        isset($data['attachment'])
    ) {

        if (
            $deliverable->attachment
        ) {

            Storage::disk('public')
                ->delete(
                    $deliverable->attachment
                );
        }

        $data['attachment'] =
            $data['attachment']->store(
                'deliverables',
                'public'
            );
    }

    if (
        isset($data['status'])
    ) {

        $data['status_updated_at'] =
            now();
    }

    return $this->deliverableRepository
        ->update(
            $id,
            $data
        );
}


public function getDeliverables(
    array $filters
)
{
    return $this->deliverableRepository
        ->getDeliverables(
            $filters
        );
}





public function getSponsorDeliverables(
    array $filters
)
{
    
   $user = request()->user();

    $sponsor =
        $this->sponsorRepository
        ->findSponsorByEmail(
            $user->email
        );

    if (!$sponsor) {

        throw new Exception(
            'Sponsor not found'
        );
    }

    $deliverables =
        $this->deliverableRepository
        ->getSponsorDeliverables(
            $sponsor->id,
            $filters
        );

    $data =
        collect(
            $deliverables->items()
        )->map(

            function ($item) {

                return [

                    'id' =>
                        $item->id,

                    'deal_title' =>
                        $item->deal?->deal_title,

                    'title' =>
                        $item->title,

                    'due_date' =>
                        $item->due_date,

                    'status' =>
                        $item->status
                ];
            }
        );

    return [

        'data' =>
            $data,

        'pagination' => [

            'current_page' =>
                $deliverables->currentPage(),

            'last_page' =>
                $deliverables->lastPage(),

            'per_page' =>
                $deliverables->perPage(),

            'total' =>
                $deliverables->total()
        ]
    ];
}

}