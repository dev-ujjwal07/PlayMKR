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



        Mail::to(
            $team->email
        )->send(
            new TeamCredentialsMail(
                $team->name,
                $team->email,
                $plainPassword
            )
        );

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
}
