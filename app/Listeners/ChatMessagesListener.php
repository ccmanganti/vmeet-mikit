<?php

namespace App\Listeners;

use App\Events\ChatMessages;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChatMessagesListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ChatMessages  $event
     * @return void
     */
    public function handle(ChatMessages $event)
    {
        //
    }
}
