<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ForgotPasswordMail extends Mailable
{
    public $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('Reset Password')
            ->html("
                <h2>Reset Password</h2>
                <p>Click the link below to reset your password:</p>
                <a href='{$this->link}'>Reset Password</a>
            ");
    }
}