<?php

namespace App\Interfaces;

interface TeamRepositoryInterface
{
    public function findDeliverableById(
        int $id
    );

    public function findByEmail(
        string $email
    );

    public function getLastTeam();

    public function create(
        array $data
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

public function createUser(
    array $data
);


public function findUserByEmail(
    string $email
);

public function updateUser(
    string $oldEmail,
    array $data
);

public function deleteUserByEmail(
    string $email
);

public function updateUserPassword(
    string $email,
    string $password
);


}