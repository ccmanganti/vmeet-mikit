<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mikit') }}</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="/img/icon.png" type="image/x-icon">


    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/fc1dbdc8e2.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/home.css', 'resources/css/lobby.css', 'resources/css/meeting.css','resources/js/app.js', 'resources/js/meeting.js','resources/js/style.js', 'resources/js/transcribe.js'])

</head>
<body>
    <div class="body">
        <header>
            <div class="navbar">
                <a href="{{ url('/') }}" title="MIKIT Homepage">
                    <img class="navbrand" src="/img/LOGO.png" alt="MIKIT Homepage"></a>
                <nav class="nav">
                    <ul class="navlist">
                        <li class="navitem"><a class="navlink aboutBtn" href="{{ route('about') }}"">About</a></li>
                        @guest
                            @if (Route::has('login'))
                                <li class="navitem">
                                    <a class="navlink loginBtn activeNav" href="{{ url('/') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="navitem">
                                    <a class="navlink registerBtn" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @if (Auth::user()->user_privilege == 'Admin User')
                            <li class="navitem">
                                <a class="navlink" href="/admin" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Admin
                                </a>
                            </li>    
                            @endif
                            <li class="navitem">
                                <a class="navlink logoutBtn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Welcome {{ strtoupper(Auth::user()->name) }}!
                                </a>
                            </li>
                            <li class="navitem">
                                <div>
                                    <a class="logout-btn" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest

                    </ul>   
                    <span class="hamburger">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </span>
                </nav>
            </div>
        </header>
    </div>
    <div id="app">
        <main class="">
            @yield('content')
        </main>
    </div>
    
    {{-- High end script APIs --}}
    <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
    <script src="https://cdn.metered.ca/sdk/video/1.4.5/sdk.min.js"></script>
</body>
</html>
