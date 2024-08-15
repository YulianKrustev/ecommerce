<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'eCommerce' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('vendor/cookie-consent/css/cookie-consent.css') }}">
    @livewireStyles
</head>
<body class="bg-slate-200 dark:bg-slate-700">
@livewire('partials.navbar')
<main>
    {{ $slot }}
</main>
@livewire('partials.footer')
@livewireScripts
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-livewire-alert::scripts />
<x-laravel-cookies-consent></x-laravel-cookies-consent>
</body>
</html>
