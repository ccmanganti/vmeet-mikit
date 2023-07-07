<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Url;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\User;

class ChatMessages implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $message;
    public $urls;
    public $urlList;
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $message, User $user)
    {
        $this->message = $message;
        $this->user = $user;
        // $this->url = $url;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // $currentPath= Route::getFacadeRoot()->current()->uri();
        // $roomExist = Url::where('url', '=', $currentPath)->first();
        // $rooms = Url::all();
        // $allRooms = [];
        // foreach($rooms as $room) { }
        // {`
        //     array_push($allRooms, new PrivateChannel('private.chat.'.$room->id+1));
        // }

        // return $allRooms;
        $urlList = [];
        $urls = Url::all();
        foreach($urls as $url) {
            array_push($urlList, new PresenceChannel('presence.chat.'.$url->id));
        }
        return $urlList;
    }
    public function broadcastAs()
    {
        return "message";
    }
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'user' => $this->user->only(['id','name', 'email'])
        ];
    }
}
