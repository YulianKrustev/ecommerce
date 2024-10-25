@push('title')
    Cancel | {{ config('app.name') }}
@endpush
@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush
    <div class="flex items-center justify-center min-h-96 mb-5">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-800 mb-4">Payment Failed! Order Cancelled!</h1>
            <p class="text-lg text-gray-600 mb-6">Oops!</p>
            <a wire:navigate href="/" class="btn btn-primary btn-checkout custom-button" x-data="{ buttonClicked: false }" x-on:click="buttonClicked = true" :class="{ 'opacity-50 text-orange-600': buttonClicked }">Go Back Home</a>
        </div>
    </div>





