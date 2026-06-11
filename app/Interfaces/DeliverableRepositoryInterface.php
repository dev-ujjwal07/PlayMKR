<?php

namespace App\Interfaces;

interface DeliverableRepositoryInterface
{
    public function create(array $data);
    public function findSponsorByName(
    string $name
);
}
