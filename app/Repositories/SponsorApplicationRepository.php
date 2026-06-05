<?php

namespace App\Repositories;

use App\Models\SponsorApplication;
use App\Interfaces\SponsorApplicationRepositoryInterface;
use App\Models\Sponsor;

class SponsorApplicationRepository
implements SponsorApplicationRepositoryInterface
{
    public function create(array $data)
    {
        return SponsorApplication::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'contact_number' => $data['contact_number'],
            'website_url' => $data['website_url'],
            'industry' => $data['industry'],
            'address' => $data['address'],
            'status' => 'pending'
        ]);
    }

    public function findApplicationById(int $id)
    {
        return SponsorApplication::find($id);
    }

    public function updateApplicationStatus(
        int $id,
        string $status
    )
    {
        return SponsorApplication::where('id', $id)
            ->update([
                'status' => $status
            ]);
    }

    public function createSponsor(array $data)
    {
        return Sponsor::create($data);
    }


    public function findSponsorByEmail(
    string $email
)
{
    return Sponsor::where(
        'email',
        $email
    )->first();
}


public function findSponsorById(int $id)
{
    return Sponsor::find($id);
}

public function updateSponsorStatus(
    int $id,
    string $status
)
{
    return Sponsor::where('id', $id)
        ->update([
            'status' => $status
        ]);
}
}