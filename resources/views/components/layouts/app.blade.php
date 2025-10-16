<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'SiMagang' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-gray-100">
        <div class="flex">
            {{-- Sidebar akan tampil di sini --}}
            <x-sidebar />

            {{-- Area Konten Utama --}}
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
        @livewireScripts
    </body>
</html>