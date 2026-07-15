<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Constants\NotificationConstants;


class NotificationController extends Controller
{

 protected $notificationService;

    public function __construct(
        NotificationService $notificationService
    ) {
        $this->notificationService =
            $notificationService;
    }



public function index(
    Request $request
)
{
    $result =

        $this->notificationService
            ->getNotifications(

                $request->user(),

                $request->per_page ?? 99

            );

    return response()->json([

        'success' => true,

        'statusCode' => 200,

        'message' =>

            NotificationConstants
                ::NOTIFICATIONS_FETCHED,

        'stats' =>

            $result['stats'],

        'data' =>

            $result['data'],

        'pagination' =>

            $result['pagination']

    ]);
}



public function unread(
    Request $request
)
{
    return response()->json([

        'status' => true,

        'data' =>

        $this->notificationService
            ->getUnreadNotifications(

                $request->user()

            )

    ]);
}


public function unreadCount(
    Request $request
)
{
    return response()->json([

        'status' => true,

        'count' =>

        $this->notificationService
            ->getUnreadCount(

                $request->user()

            )

    ]);
}
}
