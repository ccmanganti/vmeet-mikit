@extends('layouts.app')

@section('content')
<div id="waitingArea" class="container-waiting">
    <div class="meeting-lobby-details">
        <div class="showcase-lobby">
            <video class="showcase-vid-4" src="/img/showcase_lobby_1.mp4" autoplay loop muted></video>
        </div>
        <div class="py-4">
            <h2 class="text-2xl">Meeting Lobby</h2>
        </div>
        <div class="meeting-lobby-settings">
            <input type="text" disabled hidden class="lobbyName" id="username" value="{{ Auth::user()->name }}"/>
            <div class="meetingCopy">
                <input type="text" disabled class="lobbyDetails" id="lobbyMikitDetails" value=" {{ Request::url() }} "/>
                <button class="lobbyDetailsCopy" id="copyDetails"><i class="lobbyIconDetails fa fa-clipboard" aria-hidden="true"></i></button>
            </div>

            <label class="lobby-select">
                Camera:
                <select id='cameraSelectBox'>
                </select>
            </label>

            <label class="microphone-layout lobby-select">
                Microphone:
                <select id='microphoneSelectBox'>
                </select>
            </label>

            <button id='joinMeetingBtn'>
                Join Meeting
            </button>
        </div>
    </div>
    <div class="waiting-area-video-container">
        <div class="waiting-area-video">
            <video id='waitingAreaLocalVideo' class="w-full" autoplay muted></video>
        </div>
        <div class="vid-control-container">
            <button id='waitingAreaToggleMicrophone' class="vid-control-mic">
                <i class="fa fa-microphone" aria-hidden="true"></i>
            </button>

            <button id='waitingAreaToggleCamera' class="vid-control-vid">
                <i class="fa fa-video-camera" aria-hidden="true"></i>
            </button>
        </div> 
    </div>

</div>
<div id='meetingView' class="hidden">
    <div class="meetingView">
        <div class="meetingViewMain">
            <div class="chatContainer" id="chatContainer">
                
                <p id="chatMessagesTitle">In-call messages:</p>
                <ul id="thread">
                    <li class="chatMessageNotice">Messages can only be seen by people in the call and are deleted when the call ends.</li>
                </ul>
                <form id="form" class="chatMessageForm" method="post" action="\chat-message" onsubmit="return false">
                    @csrf
                    <input type="text" id="new-chat-input" name="message">
                    <button type="submit" id="new-chat-submit">Submit</button>
                </form>
                <div class="textarea">
                    <textarea id="convert_text" disabled></textarea>
                </div>

            </div>
            <div id="activeSpeakerContainer" class="">
                <video id="activeSpeakerVideo" src="" autoplay class=""></video>
                <div id="activeSpeakerUsername" class="hidden">

                </div>
            </div>  

            <div id="remoteParticipantContainer" class="">
                <div id="localParticipantContainer" class="">
                    <div class="localVideoContainer">
                        <video id="localVideoTag" src="" autoplay class="localVideoContain"></video>
                    </div>
                    <div id="localUsername" class="localUsername">
                        Me
                    </div>
                </div>
            </div>
        </div>
        
        <div class="meetBtns" title="Toggle MIC">
            <div class="meetBtns1">
                <button id='chatContainerBtn' class="meetBtn chatBtn" title="Open Chat">
                    <i class="fas fa-message"></i>
                </button>

                <div class="meetingCopy" title="Copy Meeting Details">
                    <input type="text" hidden class="lobbyDetails" id="lobbyMikitDetails" value=" {{ Request::url() }} "/>
                    <button class="lobbyDetailsCopy lobbyDetailsCopyMeet" id="copyNewDetails"><i class="lobbyIconDetails fa fa-clipboard" aria-hidden="true"></i></button>
                </div>

                <button id='toggleScreen' class="meetBtn" title="Share Screen">
                    <i class="fas fa-desktop"></i>
                </button>

                <button id='toggleMicrophone' class="meetBtn">
                    <i class="fa fa-microphone" aria-hidden="true"></i>
                </button>

                <button id='toggleCamera' class="meetBtn" title="Toggle CAM">
                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                </button>
            </div>
            <div class="meetBtns2">
                <button id='leaveMeeting' class="meetBtn meetLeave" title="Leave Meeting">
                    <i class="fa-solid fa-phone"></i><i>End Call</i>
                </button>
            </div>

        </div>
        <div class="otherButtons">
            <button class="meetBtn" id="speechToText" class="speechToText" title="Toggle Speech to Text">
                <i class="fa-solid fa-language activeAudio"></i>
            </button>
            <div class="recordButtonWrap">         
                <div class="start-screen-recording recording-style-black"><div><div class="rec-dot"></div><span>Start Recording</span></div></div><script src="https://api.apowersoft.com/screen-recorder?lang=en" defer></script>
            </div>
        </div>
    </div>
</div>

<div id="leaveMeetingView" class="hidden">
    <h1 class="leftMeeting">
        You have left the meeting 
    </h1>
</div>

    <script>
        window.METERED_DOMAIN = "{{ $METERED_DOMAIN }}";
        window.MEETING_ID = "{{ $MEETING_ID }}";
        window.CHANNEL_ID = "{{ $CHANNEL_ID }}";
    </script>

@endsection
