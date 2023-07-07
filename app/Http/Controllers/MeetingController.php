<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Url;
use Illuminate\Support\Facades\Route;
use Session;


class MeetingController extends Controller
{

    public function createMeeting(Request $request) {
        
        $METERED_DOMAIN = env('METERED_DOMAIN');
        $METERED_SECRET_KEY = env('METERED_SECRET_KEY');
    

        // Contain the logic to create a new meeting
        $response = Http::post("https://mikitvmeet.metered.live/api/v1/room?secretKey=TVh5KqzJE2e2WsGg29_qQNe1udDboPpY5vws24t0enDDib2Q", [
            'autoJoin' => true
        ]);
        
        $roomName = $response->json("roomName");
        
        try {
            $urlName = new Url;
            $urlName->url = $roomName;
            $urlName->created_by = auth()->user()->name;
            $urlName->save();

        } catch(Exception $e) {
            return redirect("/home?error=Something Went Wrong");
        }
        return redirect("/meeting/{$roomName}"); // We will update this soon.
    }

    public function validateMeeting(Request $request) {
        $METERED_DOMAIN = env('METERED_DOMAIN');
        $METERED_SECRET_KEY = env('METERED_SECRET_KEY');

        $meetingId = $request->input('meetingId');

        // Contains logic to validate existing meeting
        $response = Http::get("https://mikitvmeet.metered.live/api/v1/room?secretKey=TVh5KqzJE2e2WsGg29_qQNe1udDboPpY5vws24t0enDDib2Q");

        $roomName = $response->json("roomName");
        $roomExist = Url::where('url', '=', $meetingId)->first();

        if ($response->status() === 200 && $roomExist !== null)  {
            return redirect("/meeting/{$meetingId}"); // We will update this soon
        } else {
            return redirect("/home?error=Invalid Meeting ID");
        }
    }
}
