<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 999; /* Adjust z-index as needed */
        }

        @media screen and (max-width: 768px) {
            footer {
                position: static;
            }
        }
    </style>
</head>

<body class="font-sans antialiased" style="margin-top: -0;">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @if (isset($header) && (request()->is('/') || request()->is('contact')))
    <header class="bg-cover bg-center" style="background-color: #0047AB; height: 300px; opacity: 0.8;">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
    </header>
@endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="px-6 bg-gray-800 py-2" style="height: 50px;">
        <div class="container mx-auto flex justify-center items-center text-white">
            <p class="px-6 text-sm">&copy; 2024 The Hair Hub. All Rights Reserved.</p>
            <p class="px-6 text-sm"><a href="{{ route('privacystatement') }}" class="underline">Privacy Statement</a></p>
        </div>
    </footer>
    <x-cookie-message />
</body>
</html>
