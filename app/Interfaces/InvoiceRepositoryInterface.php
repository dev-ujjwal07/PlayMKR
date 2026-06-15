<?php

namespace App\Interfaces;

interface InvoiceRepositoryInterface
{
    public function create(
        array $data
    );

    public function getLastInvoice();

    public function findById(
    int $id
);

public function update(
    int $id,
    array $data
);

public function delete(
    int $id
);

}