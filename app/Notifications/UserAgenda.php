<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserAgenda extends Notification
{
    use Queueable;

    protected $agenda;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($agenda)
    {
        $this->agenda = $agenda;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'agenda' => $this->agenda,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'id' => $this->id,
            'read_at' => null,
            'created_at' => $this->agenda->created_at,
            'data' => [
                'agenda' => $this->agenda,
            ]
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
