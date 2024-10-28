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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        #notification-container {
            position: fixed;
            top: 60px; 
            right: 10px;
            width: 300px;
            max-height: 400px; 
            overflow-y: auto;
            background-color: white; 
            border: 1px solid #dee2e6; 
            border-radius: 5px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: none; 
            z-index: 1000;
        }

        .notification {
            padding: 10px; 
            border-bottom: 1px solid #dee2e6; 
        }

        .notification:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">Test</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                        @else
                            @if(Auth::user()->hasRole('Admin'))
                                <li><a class="nav-link" href="{{ route('users.index') }}">Manage Users</a></li>
                                <li><a class="nav-link" href="{{ route('roles.index') }}">Manage Role</a></li>
                                <li><a class="nav-link" href="{{ route('products.index') }}">Manage Product</a></li>
                                <li class="nav-item">
                                <a class="nav-link" href="#" id="notification-btn">
                                    <i class="fa fa-bell"></i>
                                </a>
                                </li>
                            @endif
                            @if(Auth::user()->hasRole('user'))
                            <li><a class="nav-link" href="{{ route('products.index') }}">Manage Product</a></li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
            @yield('content')
            </div>
        </main>
        <div id="notification-container">
            <div id="notification-area">
            </div>
        </div>
    </div>

    <script>
        var previousNotificationCount = 0; 

        function playBellSound() {
            var audio = new Audio('/notification.mp3');
            audio.play().catch(error => console.error("Error playing sound:", error));
        }

        function fetchNotifications() {
            $.ajax({
                url: '/notifications',
                method: 'GET',
                success: function(notifications) {
                    $('#notification-area').empty();
                    let newNotificationCount = 0;

                    notifications.forEach(notification => {
                        $('#notification-area').append(
                            `<div class="notification">
                                ${notification.data.action} - ${notification.data.product.name} by ${notification.data.actor.name}
                            </div>`
                        );
                        newNotificationCount++;
                    });

                    if (newNotificationCount > previousNotificationCount) {
                        playBellSound();    
                    }
                    previousNotificationCount = newNotificationCount; 
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        $(document).ready(function() {
            $('#notification-btn').on('click', function() {
                $('#notification-container').toggle();
            });

            setInterval(fetchNotifications, 5000);
            
        });
    </script>
</body>
</html>
