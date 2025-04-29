<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (session()->has('api_token'))
    <meta name="api-token" content="{{ session('api_token') }}">
    @endif

    <title>{{ config('app.name', 'KLA-Katingan') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/logo_katingan.png') }}">
    <link rel="icon" href="{{ asset('images/logo_katingan.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo_katingan.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        body::-webkit-scrollbar {
            width: 0;
        }

        @media (max-width: 920px) {
            table {
                display: block;
                overflow-x: auto;
            }

            table::-webkit-scrollbar-track {
                width: 5px;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div x-data="{ open: false }" class="min-h-screen bg-gray-100">
        <!-- Sidebar Navigation -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <div class="lg:ml-60">
            <!-- Top Navigation Bar -->
            <div class="bg-white shadow-sm p-4 flex items-center justify-between lg:hidden">
                <span class="text-xl font-bold text-indigo-700">Admin</span>
                <button @click="open = !open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Page Content -->
            <div :class="{'pointer-events-none': open}" class="lg:pointer-events-auto">
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="p-4">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
