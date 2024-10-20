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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <style>
        body {
            background-color: #f3f3f3
        }
    </style>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color: #dc3545;">
            <div class="container">
                <a class="navbar-brand" href="{{ route('owner.dashboard') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- Additional links can be added here -->
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
                            <a class="nav-link" href="{{ route('notifications.owners.index') }}" role="button">
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
                                <a class="dropdown-item" href="{{ route('owner.users.index') }}">
                                    Customer Behavior
                                </a>
                                <a class="dropdown-item" href="{{ route('owner.admins.index') }}">
                                    Manage Cashier
                                </a>
                                <a class="dropdown-item" href="{{ route('owner.reports') }}">
                                    View Reports
                                </a>
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
                @yield('content')
            </div>
        </main>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

        @yield('scripts')
        
        <script>
       $(document).ready(function() {
    // Inisialisasi tabel
    var reportTable = $('#reportTable').DataTable({
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();

            // Fungsi untuk menghapus format Rupiah dan konversi ke float
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\Rp,]/g, '') * 1 :
                    typeof i === 'number' ? i : 0;
            };

            // Total omzet keseluruhan data (sebelum filter)
            var totalOmzetAll = api
                .column(4) // Kolom omzet, sesuaikan dengan index kolom
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total omzet data yang terlihat (setelah filter)
            var totalOmzetFiltered = api
                .column(4, { filter: 'applied' }) // Hanya data yang difilter
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update total omzet di tampilan footer
            $('#totalOmzet').html('Rp ' + totalOmzetFiltered.toLocaleString('id-ID', { minimumFractionDigits: 2 }));

            // Debugging untuk melihat total omzet keseluruhan dan yang difilter
            console.log('Total Omzet All:', totalOmzetAll, 'Total Omzet Filtered:', totalOmzetFiltered);
        }
    });

    // Fungsi filter rentang waktu untuk reportTable
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'reportTable') {
                return true; // Biarkan tabel lain tidak terpengaruh
            }

            var min = $('#start-date').val();
            var max = $('#end-date').val();
            var date = data[2]; // Kolom tanggal (Index ke-2 sesuai dengan table header)

            // Konversi string date ke objek Date
            var dateObj = new Date(date);

            // Debugging untuk memastikan tanggal yang diambil
            console.log('Date from table:', date, 'Parsed Date:', dateObj);

            if (
                (min === "" && max === "") || // Jika tidak ada input, tampilkan semua
                (min === "" && dateObj <= new Date(max)) || // Jika hanya end-date
                (max === "" && dateObj >= new Date(min)) || // Jika hanya start-date
                (dateObj >= new Date(min) && dateObj <= new Date(max)) // Jika kedua tanggal diinput
            ) {
                return true;
            }
            return false;
        }
    );

    // Event listener untuk input tanggal pada reportTable
    $('#start-date, #end-date').change(function() {
        // Debugging untuk melihat nilai min dan max
        console.log('Start Date:', $('#start-date').val(), 'End Date:', $('#end-date').val());
        reportTable.draw(); // Hanya menggambar ulang reportTable
    });
});


        </script>
    </div>
</body>

</html>
