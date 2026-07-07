<?php

namespace App\Repositories;

use App\Models\SponsorApplication;
use App\Interfaces\SponsorApplicationRepositoryInterface;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Models\Deal;
use App\Models\Deliverable;


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



public function findUserByEmail(
    string $email
)
{
    return User::where(
        'email',
        $email
    )->first();
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
   $query =
    Sponsor::query()
        ->withCount(
            'deals'
        );

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


public function getSponsorByEmail(
    string $email
)
{
    return Sponsor::where(
        'email',
        $email
    )->first();
}
public function getUserById(
    int $id
)
{
    return User::find(
        $id
    );
}

public function updateUserById(
    int $id,
    array $data
)
{
    $user =
        User::find(
            $id
        );

    if (!$user) {

        return null;
    }

    $user->update(
        $data
    );

    return $user->fresh();
}



public function updateSponsorProfile(
    int $id,
    array $data
)
{
    $sponsor =
        Sponsor::find(
            $id
        );

    if (!$sponsor) {

        return null;
    }

    $sponsor->update(
        $data
    );

    return $sponsor->fresh();
}




public function getSponsorStats()
{
    return [

        'total_sponsor' =>

            Sponsor::count(),

        'active_deals' =>

            Deal::where(
                'status',
                'active'
            )->count(),

        'pending_deliverables' =>

            Deliverable::where(
                'status',
                'pending'
            )->count()
    ];
}


}