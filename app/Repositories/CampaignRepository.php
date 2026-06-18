<?php

namespace App\Repositories;

use App\Models\Deal;
use App\Models\Sponsor;
use App\Models\Invoice;
use App\Models\Milestone;
use App\Models\Attachment;
use App\Models\DeliverType;
use App\Models\Deliverable;
use App\Models\DealType;
use App\Interfaces\CampaignRepositoryInterface;

class CampaignRepository
    implements CampaignRepositoryInterface
{
    public function findDealTypeByName(
        string $name
    )
    {
        return DealType::where(
            'name',
            $name
        )->first();
    }

    public function findDeliverTypeByName(
        string $name
    )
    {
        return DeliverType::where(
            'name',
            $name
        )->first();
    }

    public function findSponsorById(
        int $id
    )
    {
        return Sponsor::find($id);
    }

    public function createDeal(
        array $data
    )
    {
        return Deal::create($data);
    }

    public function createDeliverable(
        array $data
    )
    {
        return Deliverable::create($data);
    }

    public function createAttachment(
        array $data
    )
    {
        return Attachment::create($data);
    }

    public function createMilestone(
        array $data
    )
    {
        return Milestone::create($data);
    }

    public function createInvoice(
        array $data
    )
    {
        return Invoice::create($data);
    }



    public function findDealById(
    int $id
)
{
    return Deal::find($id);
}

public function updateDeal(
    int $id,
    array $data
)
{
    $deal =
        Deal::findOrFail($id);

    $deal->update($data);

    return $deal;
}

public function getDeliverablesByDealId(
    int $dealId
)
{
    return Deliverable::where(
        'deal_id',
        $dealId
    )->get();
}

public function deleteDeliverables(
    int $dealId
)
{
    Deliverable::where(
        'deal_id',
        $dealId
    )->delete();
}

public function deleteMilestones(
    int $dealId
)
{
    Milestone::where(
        'deal_id',
        $dealId
    )->delete();
}

public function deleteInvoices(
    int $dealId
)
{
    Invoice::where(
        'deal_id',
        $dealId
    )->delete();
}



public function deleteDeal(
    int $dealId
)
{
    return Deal::destroy($dealId);
}
}