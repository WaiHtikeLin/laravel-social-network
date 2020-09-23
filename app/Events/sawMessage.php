<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class sawMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room_id;
    private $sender_id;

    public $queue = 'message';


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room_id,$sender_id)
    {
      $this->room_id=$room_id;
      $this->sender_id=$sender_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illumi\nate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.'.$this->sender_id);
    }
}
