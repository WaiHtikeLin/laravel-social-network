<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class YourPostIsLiked extends Notification //implements ShouldQueue
{
    use Queueable;
    private $user;
    private $post_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$post_id)
    {
      $this->user=$user;
      $this->post_id=$post_id;
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
          'name' => $this->user->name,
          'id' => $this->user->id,
          'profile_pic'=> $this->user->getProfilePic(),
          'msg' => ' liked your post.',
          'notiroute' => "/show/post/$this->post_id",
          'status' => 'unchecked'
        ];
    }
}
