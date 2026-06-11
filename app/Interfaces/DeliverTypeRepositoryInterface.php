<?php

namespace App\Interfaces;

interface DeliverTypeRepositoryInterface
{
    public function create(array $data);

    public function findByName(string $name);
}