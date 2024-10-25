<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{--    <meta name="description" content="{{ $settings->site_description ?? 'Default site description goes here.' }}">--}}
    @stack('meta')
    <title>{{ '404' }}</title>
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
    <div class="flex items-center justify-center min-h-96 mb-5">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
            <p class="text-lg text-gray-600 mb-6">Oops! The page you're looking for doesn't exist.</p>
            <a href="{{ url('/') }}" class="btn btn-primary btn-checkout custom-button" x-data="{ buttonClicked: false }" x-on:click="buttonClicked = true" :class="{ 'opacity-50 text-orange-600': buttonClicked }">Go Back Home</a>
        </div>
    </div>
</main>
@livewire('partials.footer')
@livewireScripts
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-livewire-alert::scripts/>
<x-laravel-cookies-consent></x-laravel-cookies-consent>
</body>
</html>

