<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class newMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $message;
    public $sender;
    public $profile_pic;
    public $count;
    private $id;

    public $queue = 'message';
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$sender,$profile_pic,$count,$id)
    {
      $this->message=$message;
      $this->sender=$sender;
      $this->profile_pic=$profile_pic;
      $this->count=$count;
      $this->id=$id;
      Log::debug('New message event triggered', [
          'message' => $this->message,
          'sender' => $this->sender,
          'profile_pic' => $this->profile_pic,
          'count' => $this->count,
          'id' => $this->id
      ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.'.$this->id);
    }
}
