<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Constants\TeamConstants;
use App\Interfaces\TeamRepositoryInterface;
use App\Mail\TeamCredentialsMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class TeamService
{
    protected $teamRepository;

    public function __construct(
        TeamRepositoryInterface $teamRepository
    ) {
        $this->teamRepository =
            $teamRepository;
    }

    public function create(
        array $data
    ) {
 Log::info('Create method started');

        $deliverable =
            $this->teamRepository
            ->findDeliverableById(
                $data['deliverable_id']
            );

        if (!$deliverable) {

            throw new Exception(
                TeamConstants::DELIVERABLE_NOT_FOUND
            );
        }

        $existingEmail =
            $this->teamRepository
            ->findByEmail(
                $data['email']
            );

        if ($existingEmail) {

            throw new Exception(
                TeamConstants::EMAIL_ALREADY_EXISTS
            );
        }

        $lastTeam =
            $this->teamRepository
            ->getLastTeam();

        $number =
            $lastTeam
            ? $lastTeam->id + 1
            : 1;

        $teamId =
            '#D' .
            str_pad(
                $number,
                5,
                '0',
                STR_PAD_LEFT
            );

        $plainPassword =
            Str::random(8);

        $team =
            $this->teamRepository
            ->create([

                'team_id' =>
                $teamId,

                'deliverable_id' =>
                $data['deliverable_id'],

                'name' =>
                $data['name'],

                'email' =>
                $data['email'],

                'password' =>
                Hash::make(
                    $plainPassword
                )
            ]);


$this->teamRepository
    ->createUser([

        'name' =>
            $team->name,

        'email' =>
            $team->email,

        'password' =>
            $plainPassword
    ]); 
\Illuminate\Support\Facades\Log::info('Before Mail');



        Mail::to(
            $team->email
        )->send(
            new TeamCredentialsMail(
                $team->name,
                $team->email,
                $plainPassword
            )
        );


        \Illuminate\Support\Facades\Log::info('After Mail');

 Log::info('After Mail');
 
        return $team;



    }

















 public function update(
    int $id,
    array $data
)
{
    $team =
        $this->teamRepository
            ->findById($id);

    if (!$team) {

        throw new Exception(
            TeamConstants::TEAM_NOT_FOUND
        );
    }

    $deliverable =
        $this->teamRepository
            ->findDeliverableById(
                $data['deliverable_id']
            );

    if (!$deliverable) {

        throw new Exception(
            TeamConstants::DELIVERABLE_NOT_FOUND
        );
    }

    $oldEmail = $team->email;

    $updatedTeam =
        $this->teamRepository
            ->update(
                $id,
                [

                    'deliverable_id' =>
                        $data['deliverable_id'],

                    'name' =>
                        $data['name'],

                    'email' =>
                        $data['email']
                ]
            );

    $this->teamRepository
        ->updateUser(
            $oldEmail,
            [

                'name' =>
                    $data['name'],

                'email' =>
                    $data['email']
            ]
        );

    if (
        $oldEmail !==
        $data['email']
    ) {

        $newPassword =
            Str::random(8);

        $updatedTeam =
            $this->teamRepository
                ->update(
                    $id,
                    [
                        'password' =>
                            Hash::make(
                                $newPassword
                            )
                    ]
                );

        $this->teamRepository
            ->updateUserPassword(
                $data['email'],
                $newPassword
            );

        Mail::to(
            $updatedTeam->email
        )->send(
            new TeamCredentialsMail(
                $updatedTeam->name,
                $updatedTeam->email,
                $newPassword
            )
        );
    }

    return $updatedTeam;
}


  public function delete(
    int $id
)
{
    $team =
        $this->teamRepository
            ->findById($id);

    if (!$team) {

        throw new Exception(
            TeamConstants::TEAM_NOT_FOUND
        );
    }

    $this->teamRepository
        ->deleteUserByEmail(
            $team->email
        );

    return $this->teamRepository
        ->delete($id);
}





public function getTeams(
    array $filters
)
{
    $teams =
        $this->teamRepository
            ->getTeams(
                $filters
            );

    $formattedData =
        collect(
            $teams->items()
        )->map(
            function ($team) {

                return [

                    'id' =>
                        $team->id,

                    'team_id' =>
                        $team->team_id,

                    'deliverable_name' =>
                        $team->deliverable?->name,

                    'name' =>
                        $team->name,

                    'email' =>
                        $team->email,

                    'created_at' =>
                        $team->created_at,

                    'updated_at' =>
                        $team->updated_at
                ];
            }
        );

    return [

        'data' =>
            $formattedData,

        'current_page' =>
            $teams->currentPage(),

        'last_page' =>
            $teams->lastPage(),

        'per_page' =>
            $teams->perPage(),

        'total' =>
            $teams->total()
    ];
}



public function updateInternalTeamProfile(
    array $data
)
{
    return DB::transaction(
        function () use ($data) {

            $user =
                request()->user();

            $team =
                $this->teamRepository
                    ->getInternalTeamByEmail(
                        $user->email
                    );

            if (!$team) {

                throw new Exception(
                    'Team not found'
                );
            }

            $loggedInUser =
                User::find(
                    $user->id
                );

            if (!$loggedInUser) {

                throw new Exception(
                    'User not found'
                );
            }

            $oldEmail =
                $loggedInUser->email;

            $profilePath =
                $loggedInUser->profile;

            if (
                isset($data['profile'])
            ) {

                if (
                    $loggedInUser->profile &&
                    Storage::disk('public')->exists(
                        $loggedInUser->profile
                    )
                ) {

                    Storage::disk('public')
                        ->delete(
                            $loggedInUser->profile
                        );
                }

                $file =
                    $data['profile'];

                $fileName =
                    time() .
                    '_' .
                    $file->getClientOriginalName();

                $profilePath =
                    $file->storeAs(
                        'profiles',
                        $fileName,
                        'public'
                    );
            }

            $userData = [];

            if (
                isset($data['name'])
            ) {

                $userData['name'] =
                    $data['name'];

                $userData['full_name'] =
                    $data['name'];
            }

            if (
                isset($data['email'])
            ) {

                $userData['email'] =
                    $data['email'];
            }

            if (
                isset($data['number'])
            ) {

                $userData['number'] =
                    $data['number'];
            }

            if (
                $profilePath
            ) {

                $userData['profile'] =
                    $profilePath;
            }

            if (!empty($userData)) {

                $this->teamRepository
                    ->updateUserById(

                        $loggedInUser->id,

                        $userData
                    );
            }

            $teamData = [];

            if (
                isset($data['name'])
            ) {

                $teamData['name'] =
                    $data['name'];
            }

            if (
                isset($data['email'])
            ) {

                $teamData['email'] =
                    $data['email'];
            }

            if (!empty($teamData)) {

                $this->teamRepository
                    ->updateInternalTeam(

                        $team->id,

                        $teamData
                    );
            }

                        if (
                isset($data['current_password']) ||
                isset($data['new_password']) ||
                isset($data['confirm_password'])
            ) {

                if (
                    !Hash::check(
                        $data['current_password'],
                        $loggedInUser->password
                    )
                ) {

                    throw new Exception(
                        'Current password is incorrect.'
                    );
                }

                $password =
                    Hash::make(
                        $data['new_password']
                    );

                $this->teamRepository
                    ->updateUserById(

                        $loggedInUser->id,

                        [
                            'password' => $password
                        ]
                    );

                $this->teamRepository
                    ->updateInternalTeam(

                        $team->id,

                        [
                            'password' => $password
                        ]
                    );
            }

            return [

                'status' => true,

                'message' =>
                    'Profile updated successfully.'
            ];
        }
    );
}




public function getInternalTeamProfile()
{
    $authUser =
        request()->user();

    $team =
        $this->teamRepository
            ->getInternalTeamByEmail(
                $authUser->email
            );

    if (!$team) {

        throw new Exception(
            'Team not found'
        );
    }

    $user =
        $this->teamRepository
            ->getUserById(
                $authUser->id
            );

    if (!$user) {

        throw new Exception(
            'User not found'
        );
    }

    return [

        'status' => true,

        'message' =>
            'Profile fetched successfully.',

        'data' => [

            'id' =>
                $user->id,

            'team_id' =>
                $team->team_id,

            'name' =>
                $user->name,

            'email' =>
                $user->email,

            'number' =>
                $user->number,

            'profile' =>
                $user->profile
                ? asset(
                    'storage/' .
                    $user->profile
                )
                : null,

            'created_at' =>
                $user->created_at
        ]
    ];
}


}
