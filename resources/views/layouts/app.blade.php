<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li><a class="nav-link" href="{{ route('illness.all') }}">{{ __('messages.illnesses_title') }}</a></li>
                        <li><a class="nav-link" href="{{ route('symptom.all') }}">{{ __('messages.symptoms') }}</a></li>
                        <li><a class="nav-link" href="{{ route('therapist.all') }}">{{ __('messages.therapists') }}</a></li>
                        @if(Auth::check() && Auth::user()->isPatient() && Auth::user()->patient && Auth::user()->patient->is_active == true)
                            <li><a class="nav-link" href="{{ route('patient.therapists') }}">{{ __('messages.my_therapists') }}</a></li>
                            <li><a class="nav-link" href="{{ route('patient.requests') }}">{{ __('messages.requests') }}</a></li>
                            <li><a class="nav-link" href="{{ route('diary', Auth::user()->patient->id) }}">{{ __('messages.diary') }}</a></li>
                        @endif
                        @if(Auth::check() && Auth::user()->isTherapist())
                            <li><a class="nav-link" href="{{ route('therapist.patients') }}">{{ __('messages.my_patients') }}</a></li>
                            <li><a class="nav-link" href="{{ route('therapist.requests') }}">{{ __('messages.requests') }}</a></li>
                        @endif
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <li><a class="nav-link" href="/moderators">{{ __('messages.moderators') }}</a></li>
                        @endif
                        @if(Auth::check() && Auth::user()->isModerator())
                            <li><a class="nav-link" href="/system/users">{{ __('messages.system_users') }}</a></li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ __('messages.language') }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <!-- Language Switch Links -->
                                <a class="dropdown-item" href="/lang/lv">LV</a>
                                <a class="dropdown-item" href="/lang/en">EN</a>
                            </div>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        {{ __('auth.profile') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('auth.logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
