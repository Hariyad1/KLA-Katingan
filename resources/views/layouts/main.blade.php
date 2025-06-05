<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="email" content="hariyadi231223@gmail.com">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (session()->has('api_token'))
    <meta name="api-token" content="{{ session('api_token') }}">
    @endif

    <title>{{ config('app.name', 'Kabupaten Layak Anak') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/logo_katingan.png') }}">
    <link rel="icon" href="{{ asset('images/logo_katingan.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo_katingan.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Other CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Slick Carousel after Alpine -->
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex flex-col">
        @include('layouts.main-navigation')

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        @include('layouts.footer')
    </div>

    <script>
        function recordVisitorStatistics() {

            let sessionId = localStorage.getItem('visitor_session_id');
            
            if (!sessionId) {
                axios.post('/api/statistic', {}, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.data.session_id) {
                        localStorage.setItem('visitor_session_id', response.data.session_id);
                        sessionId = response.data.session_id;
                        
                        setupActivityUpdates(sessionId);
                    }
                })
                .catch(error => {
                    console.error('❌ Error mencatat statistik:', error);
                });
            } else {
                setupActivityUpdates(sessionId);
            }
        }
        
        function setupActivityUpdates(sessionId) {
            updateActivity(sessionId);
            
            setInterval(() => {
                updateActivity(sessionId);
            }, 120000);
        }
        
        function updateActivity(sessionId) {
            axios.post('/api/statistic/update-activity', {
                session_id: sessionId
            }, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).catch(error => {
                if (error.response && (error.response.status === 404 || error.response.status === 400)) {
                    localStorage.removeItem('visitor_session_id');
                    recordVisitorStatistics();
                } else {
                    console.error('❌ Error updating activity:', error);
                }
            });
        }

        window.addEventListener('beforeunload', () => {
            const expiry = Date.now() + (10 * 60 * 1000);
            localStorage.setItem('visitor_session_expiry', expiry);
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const sessionExpiry = localStorage.getItem('visitor_session_expiry');
            if (sessionExpiry && Date.now() > parseInt(sessionExpiry)) {
                localStorage.removeItem('visitor_session_id');
                localStorage.removeItem('visitor_session_expiry');
            }
            
            recordVisitorStatistics();
        });
    </script>

    @stack('scripts')
</body>
</html>