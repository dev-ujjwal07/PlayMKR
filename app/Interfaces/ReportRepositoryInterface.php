<?php

namespace App\Interfaces;

interface ReportRepositoryInterface
{
    public function create(
        array $data
    );

    public function findTicketById(
        int $id
    );

    public function findSponsorByEmail(
        string $email
    );

    public function findTicketBySponsor(
    int $ticketId,
    int $sponsorId
);


public function getSponsorReports(
    int $sponsorId,
    array $filters
);

public function getSponsorReportById(
    int $id,
    int $sponsorId
);

public function delete(
    int $id
);

public function getReports(
    array $filters
);

public function getReportById(
    int $id
);

public function update(
    int $id,
    array $data
);


public function getInternalTeamReports(
    int $teamId,
    array $filters
);

public function getInternalTeamReportById(
    int $id,
    int $teamId
);


public function updateInternalTeamReportStatus(
    int $id,
    int $teamId,
    array $data
);

public function findTeamByEmail(
    string $email
);

public function updateInternalTeamTicketReport(
    int $ticketId,
    int $teamId,
    array $data
);

public function findTicketByTeam(
    int $ticketId,
    int $teamId
);
}