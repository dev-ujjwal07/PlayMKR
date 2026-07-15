<?php

namespace App\Interfaces;
use App\Models\User;

interface NotificationRepositoryInterface
{
public function getNotifications(
    User $user,
    int $perPage
);

public function getUnreadNotifications(
    User $user
);

public function getUnreadCount(
    User $user
);

    public function readNotification(
        int $userId,
        string $notificationId
    );

    public function readAllNotifications(
        int $userId
    );

    public function sendNotification(
    User $user,
    string $title,
    string $message,
    string $type,
    ?int $referenceId = null
);



}