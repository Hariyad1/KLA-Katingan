<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kota Layak Anak - Kabupaten Katingan') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased h-full">
        <div class="h-full flex flex-col bg-gradient-to-br from-green-600 to-green-700">
            <div class="flex-1 flex flex-col items-center justify-center p-4">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-white">Kota Layak Anak</h1>
                    <p class="text-green-100">Kabupaten Katingan</p>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
