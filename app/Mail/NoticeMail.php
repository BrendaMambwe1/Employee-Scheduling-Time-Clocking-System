<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoticeMail extends Mailable
{
    use Queueable, SerializesModels;
    private $message;
    private $usernames;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usernames,$message)
    {
        $this->message = $message;
        $this->usernames = $usernames;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Notice')->view('email_templates.noticeMail',['usernames'=>$this->usernames,'message'=>$this->$message]);
    }
}
