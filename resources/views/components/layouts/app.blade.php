@php
    $settings = \Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting::first();
@endphp

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $settings->site_description ?? 'Default site description goes here.' }}">
    <title>{{ $settings->site_name ?? 'eCommerce' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/custom.css'])
    <link rel="stylesheet" href="{{ asset('vendor/cookie-consent/css/cookie-consent.css') }}">
    <link rel="icon" href="{{ url('/storage/' . ($settings->site_favicon ?? 'default_favicon.ico')) }}">
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
<x-livewire-alert::scripts/>
<x-laravel-cookies-consent></x-laravel-cookies-consent>
</body>
</html>
