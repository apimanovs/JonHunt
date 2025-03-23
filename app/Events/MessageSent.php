<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender.avatar', 'receiver.avatar');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('order.' . $this->message->order_id);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return ['message' => $this->message];
    }
    
}
