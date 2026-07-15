<?php

namespace App\Services;

use App\Models\User;
use App\Interfaces\NotificationRepositoryInterface;

class NotificationService
{
    protected $notificationRepository;

    public function __construct(

        NotificationRepositoryInterface $notificationRepository

    ) {

        $this->notificationRepository =
            $notificationRepository;
    }

    public function send(

        User $user,

        string $title,

        string $message,

        string $type,

        ?int $referenceId = null

    ) {

        $this->notificationRepository
            ->sendNotification(

                $user,

                $title,

                $message,

                $type,

                $referenceId

            );
    }



public function getNotifications(
    User $user,
    int $perPage = 99
)
{
    $notifications =
        $this->notificationRepository
            ->getNotifications(
                $user,
                $perPage
            );

    return [

        'stats' => [

            'unread_count' =>

                $user
                    ->unreadNotifications()
                    ->count()
        ],

        'data' =>

            collect(
                $notifications->items()
            )->map(

                function ($notification) {

                    return [

                        'id' =>

                            $notification->id,

                        'type' =>

                            $notification->type,

                        'notifiable_type' =>

                            $notification->notifiable_type,

                        'notifiable_id' =>

                            $notification->notifiable_id,

                        'data' =>

                            $notification->data,

                        'read_at' =>

                            $notification->read_at,

                        'created_at' =>

                            $notification->created_at,

                        'updated_at' =>

                            $notification->updated_at,

                        'time_ago' =>

                            $notification
                                ->created_at
                                ->diffForHumans()
                    ];

                }

            ),

        'pagination' => [

            'page' =>

                $notifications->currentPage(),

            'limit' =>

                $notifications->perPage(),

            'totalCount' =>

                $notifications->total(),

            'totalPages' =>

                $notifications->lastPage(),

            'hasNextPage' =>

                $notifications->hasMorePages(),

            'hasPrevPage' =>

                $notifications->currentPage() > 1
        ]
    ];
}




public function getUnreadNotifications(
    User $user
)
{
    return $this->notificationRepository
        ->getUnreadNotifications(
            $user
        )->map(function ($notification) {

            return [

                'id' =>
                    $notification->id,

                'title' =>
                    $notification->data['title'],

                'message' =>
                    $notification->data['message'],

                'type' =>
                    $notification->data['type'],

                'reference_id' =>
                    $notification->data['reference_id'],

                'created_at' =>
                    $notification->created_at
            ];
        });
}


public function getUnreadCount(
    User $user
)
{
    return $this->notificationRepository
        ->getUnreadCount(
            $user
        );
}

}