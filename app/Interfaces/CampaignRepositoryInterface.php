<?php

namespace App\Interfaces;

interface CampaignRepositoryInterface
{
    public function findDealTypeByName(
        string $name
    );

    public function findDeliverTypeByName(
        string $name
    );

    public function findSponsorById(
        int $id
    );

    public function createDeal(
        array $data
    );

    public function createDeliverable(
        array $data
    );

    public function createAttachment(
        array $data
    );

    public function createMilestone(
        array $data
    );

    public function createInvoice(
        array $data
    );

    

    public function findDealById(
    int $id
);

public function updateDeal(
    int $id,
    array $data
);

public function deleteDeliverables(
    int $dealId
);

public function deleteMilestones(
    int $dealId
);

public function deleteInvoices(
    int $dealId
);

public function getDeliverablesByDealId(
    int $dealId
    
);



public function deleteDeal(
    int $dealId
);
}