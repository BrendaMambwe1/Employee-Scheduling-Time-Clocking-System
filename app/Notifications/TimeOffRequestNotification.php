<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;
use App\Channels\Messages\WhatsAppMessage;

class TimeOffRequestNotification extends Notification
{
    use Queueable;

    public $user;
    public $start_date;
    public $end_date;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$start_date,$end_date)
    {
        $this->user = $user;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
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

        return (new WhatsAppMessage)
        ->content("Hi {$firstName} {$lastName} you have applied for a time off starting from {$this->start_date} to {$this->end_date} it awaits approval  ");
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
