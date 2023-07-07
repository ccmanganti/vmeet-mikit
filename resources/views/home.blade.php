@extends('layouts.app')

@section('content')

<div class="home-main-container">
    <div class="home-container">
        <div class="home-title">
            <h2 class="home-main-title">Mikit Video Conferencing App</h2>
            <p class="home-subtitle">Mikit is a video conferencing application made for exclusive <br> use of Don Honorio Ventura State University. <br> <br> "Mikit: Making each discussions extraordinary." </p>
        </div>
    
        <div class="name-container">
            <label for="name" class="username-label">Name</label>
            <div class="mt-1">
                <input value="{{ Auth::user()->name }}" disabled type="text" name="name" id="name" class="userName">
            </div>
        </div>
        <div class="join-meeting-container">
            <form method="post" action="{{ route('validateMeeting') }}">
                {{ csrf_field() }}
                <input type="text" name="meetingId" id="meetingId" class="meetingId" placeholder="Meeting ID">
                <button type="submit" class="join-meeting">
                    <span>Join Meeting</span>
                </button>
            </form>
            <div class="create-meeting-container">
                <span class="text-xs uppercase font-bold text-gray-400 px-1">OR</span>
                <form method="post" action="{{ route('createMeeting') }}">
                    {{ csrf_field() }}
                    <button type="submit" class="create-meeting">Create New Meeting</button>
                </form>
            </div>   
        </div>
    </div>
    <div class="showcase-home">
        <video class="showcase-vid-3" src="./img/showcase_home.mp4" autoplay loop muted></video>
    </div>
</div>

@endsection
