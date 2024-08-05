<!DOCTYPE html>
{{-- This is the general layout, everything else is inserted on here. Ex.: navbar and footer --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Magazine Lindinha' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{--Ensures stuff like wire:loading and wire:target works--}}
        @livewireStyles
    </head>
    <body class="bg-slate-200 dark:bg-slate-700">
        @livewire('partials.navbar')
        <main>
            {{ $slot }}
        </main>
        
        
        @livewireScripts
        @livewire('partials.footer')
        {{--Sweet alert (alert shown at the bottom of the page once you add a new item to the cart)--}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <x-livewire-alert::scripts />
    </body>
</html>
