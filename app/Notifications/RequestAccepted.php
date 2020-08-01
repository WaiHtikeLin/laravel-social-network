<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
class RequestAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    private $requester;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($requester)
    {
        $this->requester=$requester;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
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

    public function toBroadcast($notifiable)
    {
      return new BroadcastMessage([
        'name' => $this->requester->name,
        'msg' => 'accepted your friend request.',
        'nameroute' => "/user/profile/{$this->requester->id}",
        'notiroute' => "/user/profile/{$this->requester->id}",
        'status' => 'unchecked'
      ]);
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
            'name' => $this->requester->name,
            'id' => $this->requester->id
        ];
    }
}
