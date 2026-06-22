<?php

namespace App\Interfaces;

interface SponsorApplicationRepositoryInterface
{
    public function create(array $data);

    public function findApplicationById(int $id);

public function updateApplicationStatus(
    int $id,
    string $status
);

public function createSponsor(array $data);

public function findSponsorByEmail(
    string $email
);

public function createUser(
    array $data
);


public function findSponsorById(int $id);

public function updateSponsorStatus(
    int $id,
    string $status
);

public function createDirectSponsor(array $data);


public function deleteSponsor(int $id);

public function updateSponsor(
    int $id,
    array $data
);
}
