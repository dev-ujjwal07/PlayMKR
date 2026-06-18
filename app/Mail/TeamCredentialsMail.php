<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;

    public function __construct(
        $name,
        $email,
        $password
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject(
            'Team Account Created'
        )->html("

            <h2>Hello {$this->name},</h2>

            <p>
                Your Team Account has been created.
            </p>

            <p>
                <strong>Email:</strong>
                {$this->email}
            </p>

            <p>
                <strong>Password:</strong>
                {$this->password}
            </p>

            <br>

            <p>
                Thanks
            </p>

        ");
    }
}