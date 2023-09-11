<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppliedTimeOff extends Mailable
{
    use Queueable, SerializesModels;
    private $usernames;
    private $startdate;
    private $enddate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usernames,$startdate,$enddate)
    {
        $this->usernames = $usernames;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Time Off Request Application')->view('email_templates.appliedTimeOff',['usernames'=>$this->usernames,'startdate'=>$this->startdate,'enddate'=>$this->enddate]);
    }
}
