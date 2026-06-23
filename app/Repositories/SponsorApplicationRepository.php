<?php

namespace App\Repositories;

use App\Models\SponsorApplication;
use App\Interfaces\SponsorApplicationRepositoryInterface;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


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

public function createUser(
    array $data
)
{
    return User::create([

        'name' =>
            $data['name'],

        'full_name' =>
            $data['name'],

        'email' =>
            $data['email'],

        'password' =>
            Hash::make(
                $data['password']
            ),

        'role_id' =>
            2
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


public function createDirectSponsor(array $data)
{
    return Sponsor::create($data);
}

public function deleteSponsor(int $id)
{
    return Sponsor::where('id', $id)->delete();
}




public function updateSponsor(
    int $id,
    array $data
)
{
    $sponsor = Sponsor::find($id);

    $sponsor->update([

        'name' => $data['name'],

        'email' => $data['email'],

        'contact_number' =>
            $data['contact_number'],

        'website_url' =>
            $data['website_url'],

        'industry' =>
            $data['industry'],

        'address' =>
            $data['address'],

        'status' =>
            $data['status']
    ]);

    return $sponsor->fresh();
}

public function getSponsors(
    array $filters
)
{
    $query = Sponsor::query();

    if (
        !empty($filters['search'])
    ) {

        $search =
            $filters['search'];

        $query->where(
            function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'contact_number',
                    'like',
                    "%{$search}%"
                );
            }
        );
    }

    if (
        !empty($filters['status'])
    ) {

        $query->where(
            'status',
            $filters['status']
        );
    }

    $perPage =
        $filters['per_page'] ?? 10;

    return $query
        ->latest('id')
        ->paginate($perPage);
}
}