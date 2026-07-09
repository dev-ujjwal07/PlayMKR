<?php

namespace App\Interfaces;

interface TicketRepositoryInterface
{
    public function create(array $data);

    public function findDealById(
        int $id
    );

    public function findTeamById(
        int $id
    );

    public function findSponsorById(
        int $id
    );

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

public function getTickets(
    ?string $search,
    int $perPage
);

public function getTicketById(
    int $id
);

public function getSponsorTickets(
    int $sponsorId,
    array $filters
);

public function getSponsorTotalTickets(
    int $sponsorId
);



public function updateStatus(
    int $id,
    string $status
);

public function getTicketByIdAndSponsor(
    int $ticketId,
    int $sponsorId
);

public function getInternalTeamTickets(
    int $teamId,
    array $filters
);

public function getInternalTeamTicketById(
    int $ticketId,
    int $teamId
);



public function getTicketStats();
}