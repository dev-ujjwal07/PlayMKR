<?php

namespace App\Interfaces;

interface DeliverableRepositoryInterface
{
    public function create(array $data);
    public function findSponsorByName(
    string $name

);

public function findById(
    int $id
);

public function delete(
    int $id
);

public function update(
    int $id,
    array $data
);

public function findTeamById(
    int $id
);
public function getDeliverables(
    array $filters
);

public function getSponsorDeliverables(
    int $sponsorId,
    array $filters
);

public function getDeliverableStats();

public function getExposureChart();

public function getSponsorDeliverableStats(
    int $sponsorId
);

}
