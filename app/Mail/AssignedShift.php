<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignedShift extends Mailable
{
    use Queueable, SerializesModels;
    use Queueable, SerializesModels;

    private $usernames;
    private $startdate;
    private $enddate;
    private $date;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usernames,$startdate,$enddate,$date)
    {
        $this->usernames = $usernames;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New shift has been assigned')->view('email_templates.assignedShift',['usernames'=>$this->usernames,'startdate'=>$this->$startdate,'enddate'=>$this->$enddate,'date'=>$this->date]);
    }
}
