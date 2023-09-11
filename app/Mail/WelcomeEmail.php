<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $usernames;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usernames)
    {
        $this->usernames = $usernames;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to the workynet Communiity')->view('email_templates.welcome',['usernames'=>$this->usernames]);
    }
}
