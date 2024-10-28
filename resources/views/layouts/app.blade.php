<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+8hb3M23Rj9C1kGQFl5Ff6cA9LxFSrH6eZxG8fC" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
    #notification-area {
    position: fixed; 
    top: 10px;
    right: 10px; 
    z-index: 1000; 
    }

    .notification {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 10px; 
        margin-bottom: 5px;
        border-radius: 5px;
    }
</style>
</head>
<body>
    <div id="app">
    <div id="notification-area"></div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Roles and Permissions
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">

                    </ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                             @if(Auth::user()->hasRole('Admin'))
                            <li><a class="nav-link" href="{{ route('users.index') }}">Manage Users</a></li>
                            <li><a class="nav-link" href="{{ route('roles.index') }}">Manage Role</a></li>
                            @endif
                            <li><a class="nav-link" href="{{ route('products.index') }}">Manage Product</a></li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
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
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
    </div>

    <script>
    function playBellSound() {
        var audio = new Audio('/notification.mp3'); 
        audio.play().catch(function(error) {
            console.error("Error playing sound:", error);
        });
    }

    function fetchNotifications() {
        $.ajax({
            url: '/notifications',
            method: 'GET',
            success: function(notifications) {
                $('#notification-area').empty();
                let newNotificationCount = 0;
                let previousNotificationCount = 0;

                notifications.forEach(function(notification) {
                    $('#notification-area').append(
                        `<div class="notification">
                            ${notification.data.action} - ${notification.data.product.name}
                        </div>`
                    );
                    newNotificationCount++;
                    console.log("New notification count:", newNotificationCount);
                });
                if (newNotificationCount > previousNotificationCount) {
                    playBellSound();
            }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    setInterval(fetchNotifications, 5000);

    $(document).ready(function() {
        fetchNotifications();
    });
</script>
</body>
</html>