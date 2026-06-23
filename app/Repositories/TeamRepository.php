<?php

namespace App\Repositories;

use App\Models\Team;
use App\Models\Deliverable;
use App\Interfaces\TeamRepositoryInterface;

use App\Models\User;
use Illuminate\Support\Facades\Hash;



class TeamRepository
    implements TeamRepositoryInterface
{
    public function findDeliverableById(
        int $id
    )
    {
        return Deliverable::find($id);
    }

    public function findByEmail(
        string $email
    )
    {
        return Team::where(
            'email',
            $email
        )->first();
    }

    public function getLastTeam()
    {
        return Team::latest('id')
            ->first();
    }

    public function create(
        array $data
    )
    {
        return Team::create($data);
    }



    public function findById(
    int $id
)
{
    return Team::find($id);
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
            3
    ]);
}

public function update(
    int $id,
    array $data
)
{
    $team =
        Team::findOrFail($id);

    $team->update($data);

    return $team->fresh();
}

public function delete(
    int $id
)
{
    return Team::destroy($id);
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

public function updateUser(
    string $oldEmail,
    array $data
)
{
    return User::where(
        'email',
        $oldEmail
    )->update([

        'name' =>
            $data['name'],

        'full_name' =>
            $data['name'],

        'email' =>
            $data['email']
    ]);
}

public function deleteUserByEmail(
    string $email
)
{
    return User::where(
        'email',
        $email
    )->delete();
}


public function updateUserPassword(
    string $email,
    string $password
)
{
    return User::where(
        'email',
        $email
    )->update([

        'password' =>
            Hash::make(
                $password
            )
    ]);
}





public function getTeams(
    array $filters
)
{
    $query = Team::query()
        ->with([
            'deliverable:id,name'
        ]);

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
                );
            }
        );
    }

    return $query
        ->latest('id')
        ->paginate(
            $filters['per_page'] ?? 10
        );
}


}