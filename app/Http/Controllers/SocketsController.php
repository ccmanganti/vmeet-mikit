<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Broadcasting\Broadcaster\PusherBroadcaster;

class SocketsController extends Controller
{
    public function connect(Request $request){

        $broadcaster = new PusherBroadcaster(
            new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                []
            )
        );
        return $broadcaster->validAuthenticationResponse($request, []);
    }
}
