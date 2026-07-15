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
    $deliverables =
        $this->deliverableRepository
            ->getDeliverables(
                $filters
            );

    $stats =
        $this->deliverableRepository
            ->getDeliverableStats();

    return [

        'stats' =>

            $stats,

        'deliverables' =>

            $deliverables
    ];
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

        $stats =
    $this->deliverableRepository
        ->getSponsorDeliverableStats(
            $sponsor->id
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

        $campaignProgress = 0;

if (
    $stats &&
    $stats->total_deliverables > 0
) {

 $campaignProgress = ceil(

    (
        $stats->completed
        /
        $stats->total_deliverables
    ) * 100
);
}




    return [


    'stats' => [

        'total_deliverables' =>
            (int) ($stats->total_deliverables ?? 0),

        'pending' =>
            (int) ($stats->pending ?? 0),

        'in_progress' =>
            (int) ($stats->in_progress ?? 0),

        'completed' =>
            (int) ($stats->completed ?? 0),

        'campaign_progress' =>
            $campaignProgress
    ],

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




public function getExposureChart()
{
    $chart =
        $this->deliverableRepository
            ->getExposureChart();

    $months = [

        1  => 'Jan',
        2  => 'Feb',
        3  => 'Mar',
        4  => 'Apr',
        5  => 'May',
        6  => 'Jun',
        7  => 'Jul',
        8  => 'Aug',
        9  => 'Sep',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Dec'
    ];

    $data = [];

    foreach ($months as $month => $label) {

        $item =
            $chart->get($month);

        $data[] = [

            'label' => $label,

            'pending' =>
                $item
                    ? (int) $item->pending
                    : 0,

            'completed' =>
                $item
                    ? (int) $item->completed
                    : 0
        ];
    }

    return $data;
}

}


