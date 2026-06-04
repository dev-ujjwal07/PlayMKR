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
}