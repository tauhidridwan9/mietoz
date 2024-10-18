<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
     
<script>
    Pusher.logToConsole = true;

    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    const channel = pusher.subscribe('dashboard');
    channel.bind('OrderUpdated', function(data) {
        // Update your dashboard with the new data
        document.querySelector('.order-count').innerText = data.data.orderCount + ' pesanan';
        document.querySelector('.count-processing').innerText = data.data.countProcessing + ' pesanan';
        document.querySelector('.count-cooking').innerText = data.data.countCooking + ' pesanan sedang dimasak';
        document.querySelector('.count-diambil').innerText = data.data.countDiambil + ' Siap Diambil';
        document.querySelector('.customer-count').innerText = data.data.customerCount + ' customer';
    });
</script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


</head>

<body>

    <div id="app">
        <nav class="navbar fixed-top navbar-expand-md navbar-dark shadow-sm" style=" background-color: #dc3545;">
            <div class="container">
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

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
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>




                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.chat.index') }}" class="btn btn-primary">Go to Chat Room</a>
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

                .unread-badge {
    background-color: #ffc107; /* Warna kuning */
    color: white;
    font-size: 0.8em;
    padding: 5px 10px;
    border-radius: 50%;
    position: absolute;
    top: 10px;
    right: 10px;
}

                

                /* Container utama */
                a {
                    text-decoration: none;
                    color: inherit;
                    /* Menggunakan warna teks bawaan */
                }

                a:hover {
                    color: #075e54;
                    /* Menyesuaikan warna link saat hover */
                }

                .chat-container {
                    display: flex;
                    height: 100vh;
                    border: 1px solid #dcdcdc;
                }

                /* Sidebar Kontak */
                .chat-sidebar {
                    background-color: #f0f0f0;
                    padding: 0;
                    border-right: 1px solid #ddd;
                    height: 100%;
                    overflow-y: auto;
                    text-decoration: none;
                }

                .chat-sidebar-header {
                    background-color: #dc3545;
                    color: #fff;
                    padding: 15px;
                    text-align: center;
                    border-bottom: 1px solid #ddd;
                }

                .chat-contact-list {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                }

                .chat-contact-item {
                    display: flex;
                    align-items: center;
                    padding: 10px;
                    cursor: pointer;
                    border-bottom: 1px solid #ddd;
                    transition: background-color 0.3s;
                }

                .chat-contact-item:hover,
                .chat-contact-item.active {
                    background-color: #e2e2e2;
                }

                .contact-avatar img {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    margin-right: 10px;
                }

                .contact-info {
                    flex-grow: 1;
                    text-decoration: none;
                }

                .contact-name {
                    font-weight: bold;
                    margin-bottom: 5px;
                    color: #333;
                    text-decoration: none;
                }

                .contact-preview {
                    font-size: 14px;
                    color: #888;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    text-decoration: none;
                }

                /* Wadah Percakapan */
                .chat-conversation {
                    flex-grow: 1;
                    display: flex;
                    flex-direction: column;
                    background-color: #e5ddd5;
                    height: 100%;
                }

                .chat-conversation-header {
                    background-color: #dc3545;
                    color: #fff;
                    padding: 15px;
                    text-align: center;
                    border-bottom: 1px solid #ddd;
                }

                .chat-conversation-body {
                    flex-grow: 1;
                    padding: 20px;
                    overflow-y: auto;
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }

                .chat-message {
                    display: flex;
                    align-items: flex-start;
                    max-width: 70%;
                    padding: 10px;
                    background-color: #fff;
                    border-radius: 10px;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
                }

                .chat-message.admin-message {
                    background-color: #dcf8c6;
                    align-self: flex-end;
                }

                .chat-message-avatar img {
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    margin-right: 10px;
                }

                .chat-message-content {
                    max-width: 100%;
                    word-wrap: break-word;
                    font-size: 14px;
                }

                /* Bagian Input Pesan */
                .chat-conversation-footer {

                    padding: 15px;
                    display: flex;
                    border-top: 1px solid #ddd;
                    align-items: center;
                }

                .chat-conversation-form {
                    display: flex;
                    align-items: center;
                    width: 100%;
                }

                .chat-input {
                    flex-grow: 1;
                    border: 1px solid #ddd;
                    border-radius: 20px;
                    padding: 10px;
                    outline: none;
                    font-size: 14px;
                }

                .chat-send-button {
                    background-color: #dc3545;
                    border: none;
                    border-radius: 50%;
                    padding: 10px;
                    color: #fff;
                    margin-left: 10px;
                    cursor: pointer;
                    transition: background-color 0.3s;
                }

                .chat-send-button:hover {
                    background-color: #dc3590;
                }

                /* Tampilan jika tidak ada pilihan kontak */
                .chat-no-selection {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                    color: #888;
                    font-size: 16px;
                }

                 .chat-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            z-index: 1000; /* Make sure it's on top */
        }

        /* Badge for new messages */
        .chat-button .badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #ffc107;
            color: black;
            border-radius: 50%;
            padding: 5px 10px;
        }
            </style>
             @auth
             <div class="chat-button" onclick="window.location.href='{{ route('admin.chat.index') }}'">
        <i class="fas fa-comments"></i>
      
    @php
        // Menghitung total chat yang belum dibaca (is_read = false)
        $totalUnreadChats = \App\Models\Chat::where('is_read', false)->count();
    @endphp

    <!-- Jika ada chat belum dibaca, tampilkan badge -->
    @if ($totalUnreadChats > 0)
        <span class="badge">{{ $totalUnreadChats }}</span>
    @endif
@endauth
 </div>

            @yield('content')
            <!-- Scripts -->

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
           
            @yield('scripts')
            

        </main>
    </div>

</body>

</html>