<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


</head>

<body>

    <div id="app">
        <nav class="navbar fixed-top  navbar-expand-md navbar-dark shadow-sm" style=" background-color: #dc3545;">
            <div class="container">
                <a class="navbar-brand " href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- Add any additional left-side links here -->
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">


                        <!-- Authentication Links -->
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
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}" role="button">
                                <i class="fas fa-shopping-cart"></i>
                                @php
                                // Mendapatkan jumlah item dalam keranjang dari database
                                $cartCount = app(\App\Http\Controllers\CartController::class)->getCartCount();
                                @endphp
                                @if ($cartCount > 0)
                                <span class="badge bg-warning">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </li>


                        <!-- Bell Icon for Notifications (Displayed Only for Authenticated Users) -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('notifications.index') }}" role="button">
                                <i class="fas fa-bell"></i>
                                <!-- Badge for Unread Notifications -->
                                @php
                                $unreadNotifications = Auth::user()->unreadNotifications->count();
                                @endphp
                                @if ($unreadNotifications > 0)
                                <span class="badge bg-warning">{{ $unreadNotifications }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <!-- Add a new menu for 'Successful Orders' -->
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    Your Orders
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    Edit Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('chat.index') }}" class="btn btn-primary">Go to Chat Room</a>


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

        <main class="py-4 mt-5">
            <style>
                #chat {
                    display: flex;
                    flex-direction: column;
                    background-color: #f0f0f0;
                    height: 100vh;
                    padding: 20px;
                }

                .chat__conversation-board {
                    flex-grow: 1;
                    overflow-y: auto;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                }

                .chat__conversation-board__message-container {
                    display: flex;
                    align-items: flex-end;
                    margin-bottom: 15px;
                }

                .chat__conversation-board__message-container.reversed {
                    flex-direction: row-reverse;
                }

                .chat__conversation-board__message__person {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    margin-right: 10px;
                }

                .chat__conversation-board__message__person__avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    overflow: hidden;
                }

                .chat__conversation-board__message__person__avatar img {
                    width: 100%;
                    height: auto;
                }

                .chat__conversation-board__message__person__nickname {
                    font-size: 12px;
                    margin-top: 5px;
                    color: #333;
                }

                .chat__conversation-board__message__context {
                    max-width: 70%;
                }

                .chat__conversation-board__message__bubble {
                    background-color: #e0e0e0;
                    padding: 10px 15px;
                    border-radius: 10px;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                    margin-bottom: 5px;
                }

                .chat__conversation-board__message__time {
                    text-align: right;
                    font-size: 10px;
                    color: #999;
                }

                .chat__conversation-board__message-container.reversed .chat__conversation-board__message__bubble {
                    background-color: #dcf8c6;
                }

                .chat__conversation-panel {
                    display: flex;
                    align-items: center;
                    padding: 10px;
                    background-color: #f5f5f5;
                    border-top: 1px solid #ddd;
                    margin-top: auto;
                    border-radius: 0 0 10px 10px;
                }

                .chat__conversation-panel__container {
                    display: flex;
                    width: 100%;
                }

                .chat__conversation-panel__input {
                    flex-grow: 1;
                    border: none;
                    border-radius: 20px;
                    padding: 10px 15px;
                    margin-right: 10px;
                    background-color: #ffffff;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                }

                .chat__conversation-panel__button {
                    background-color: #128c7e;
                    border: none;
                    border-radius: 50%;
                    padding: 10px;
                    cursor: pointer;
                }

                .chat__conversation-panel__button:hover {
                    background-color: #075e54;
                }

                .chat__conversation-panel__button svg {
                    stroke: #ffffff;
                }
               
    /* Styling untuk card */
    .card {
        min-height: 450px; /* Set minimum height */
        max-height: 450px; /* Set maximum height */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        flex-grow: 1;
    }

    .card-img-top {
        object-fit: cover;
        height: 200px;
    }

    /* Handle long text in description */
    .card-text {
        max-height: 50px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Button styling */
    .btn-primary, .btn-secondary {
        margin-top: 15px;
        width: 100%;
    }

            </style>
            @yield('content')
            <!-- Scripts -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            @yield('scripts')
        </main>
    </div>


</body>

</html>