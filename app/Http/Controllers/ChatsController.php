<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Events\ChatMessages;


class ChatsController extends Controller
{
    public function sendEvent(Request $request) {
        event(new ChatMessages($request->message, auth()->user(),));
        return null;
    }
}
