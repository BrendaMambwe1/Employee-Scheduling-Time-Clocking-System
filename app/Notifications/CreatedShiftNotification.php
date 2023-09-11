<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class CreatedShiftNotification extends Notification
{
    use Queueable;

    public $user;
    public $shift;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$shift)
    {
        $this->user = $user;
        $this->shift = $shift;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toWhatsApp($notifiable)
    {
        $firstName = $this->user->first_name;
        $lastName = $this->user->last_name;
        $date = $this->shift->date;
        $startTime = Carbon::parse($this->scheduled_start_time);
        $endTime = Carbon::parse($this->scheduled_end_time);

        return (new WhatsAppMessage)
            ->content("Hi {$firstName} {$lastName} a new shift has been assigned to you for date {$date}, from {$startTime} to {$endTime} kindly open the app to get more details ");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
