<?php

namespace App\Events;

use App\Models\Messege;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MassegeCreated  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var  \App\Models\Messege
     */
    public $messege;

    /**
     * Create a new event instance.
     * @param  \App\Models\Messege   $messege
     */
    public function __construct(Messege $messege)
    {
        $this->messege = $messege;
        Log::info('📢 تم بث الرسالة عبر Pusher:', ['messege' => $messege]);

    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
     $other_user    =  $this->messege->convegration->participents()
     ->where('user_id','!=',$this->messege->user_id)
     ->first();

        return [
            new PresenceChannel('Massenger.'.$other_user->id),
        ];
    }
    public function broadcastWith()
    {
        return [
            'id' => $this->messege->id,
            'user_id' => $this->messege->user_id,
            'body' => $this->messege->body,
            'created_at' => $this->messege->created_at->diffForHumans(),
        ];
    }

    public function broadcastAs()
    {
        return 'MassegeCreated';
    }
}
