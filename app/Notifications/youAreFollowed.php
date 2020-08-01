<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class youAreFollowed extends Notification implements ShouldQueue
{
    use Queueable;
    private $follower;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($follower)
    {
      $this->follower=$follower;
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
          'name' => $this->follower->name,
          'msg' => 'followed you.',
          'notiroute' => '/followers',
          'nameroute' => "/user/profile/{$this->follower->id}",
          'status' => 'unchecked'
        ];
    }

    public function toBroadcast($notifiable)
    {
      return new BroadcastMessage([
        'name' => $this->follower->name,
        'msg' => 'followed you.',
        'notiroute' => '/followers',
        'nameroute' => "/user/profile/{$this->follower->id}",
        'status' => 'unchecked'
      ]);
    }
}
