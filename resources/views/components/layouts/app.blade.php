@php
    $settings = \Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting::first();
@endphp

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    @stack('meta')
    <title>@stack('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/custom.css',])
    <link rel="stylesheet" href="{{ asset('vendor/cookie-consent/css/cookie-consent.css') }}">
    <link rel="icon" href="{{ url('/storage/' . ($settings->site_favicon ?? 'default_favicon.ico')) }}">
    @livewireStyles
</head>
<body class="gradient-bg">
@include('icons')
<style>
    .logo__image {
        max-width: 220px;
    }
</style>
@livewire('partials.navbar')
<main>
    {{ $slot }}
</main>
@livewire('partials.footer')
@livewireScripts
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-livewire-alert::scripts/>
<x-laravel-cookies-consent></x-laravel-cookies-consent>
</body>
</html>
