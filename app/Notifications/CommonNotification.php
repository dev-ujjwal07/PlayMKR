<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommonNotification extends Notification
{
    use Queueable;

    protected $title;

    protected $message;

    protected $type;

    protected $referenceId;
    protected $redirectUrl;


  public function __construct(
    string $title,
    string $message,
    string $type,
    ?int $referenceId = null,
    ?string $redirectUrl = null
) {

        $this->title = $title;

    $this->message = $message;

    $this->type = $type;

    $this->referenceId = $referenceId;

    $this->redirectUrl = $redirectUrl;
    }

    public function via(
        object $notifiable
    ): array {

        return [

            'database'

        ];
    }

    public function toDatabase(
        object $notifiable
    ): array {

     return [

    'title' => $this->title,

    'message' => $this->message,

    'type' => $this->type,

    'related_id' => $this->referenceId,

    'redirect_url' => $this->redirectUrl
];
    }
}