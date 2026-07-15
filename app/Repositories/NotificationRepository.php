<?php

namespace App\Repositories;

use App\Interfaces\NotificationRepositoryInterface;
use App\Models\User;
use App\Notifications\CommonNotification;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function sendNotification(
        User $user,
        string $title,
        string $message,
        string $type,
        ?int $referenceId = null
    ) {
        $user->notify(

            new CommonNotification(

                $title,

                $message,

                $type,

                $referenceId

            )

        );
    }

   public function getNotifications(
    User $user,
    int $perPage
)
{
    return $user
        ->notifications()
        ->latest()
        ->paginate($perPage);
}

  public function getUnreadNotifications(
    User $user
)
{
    return $user
        ->unreadNotifications()
        ->latest()
        ->get();
}

   public function getUnreadCount(
    User $user
)
{
    return $user
        ->unreadNotifications()
        ->count();
}


    public function readNotification(
        int $userId,
        string $notificationId
    ){}

    public function readAllNotifications(
        int $userId
    ){}
}