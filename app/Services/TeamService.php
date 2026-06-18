<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Constants\TeamConstants;
use App\Interfaces\TeamRepositoryInterface;
use App\Mail\TeamCredentialsMail;

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
    ) {
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

        $oldEmail =
            $team->email;

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

        if (
            $oldEmail !==
            $data['email']
        ) {

            $newPassword =
                \Illuminate\Support\Str::random(
                    8
                );

            $updatedTeam =
                $this->teamRepository
                ->update(
                    $id,
                    [

                        'password' =>
                        \Illuminate\Support\Facades\Hash::make(
                            $newPassword
                        )
                    ]
                );

            \Illuminate\Support\Facades\Mail::to(
                $updatedTeam->email
            )->send(
                new \App\Mail\TeamCredentialsMail(
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
    ) {
        $team =
            $this->teamRepository
            ->findById($id);

        if (!$team) {

            throw new Exception(
                TeamConstants::TEAM_NOT_FOUND
            );
        }

        return $this->teamRepository
            ->delete($id);
    }
}
