@push('meta')
    <meta name="description" content="Post">
@endpush
<div class="">
    <div class="mb-4 pb-4"></div>
    <section class="about-us container">
        <div class="mw-930">
            <h1 class="page-title">{{ $post->title }}</h1>
        </div>

        <div class="about-us__content pb-5 mb-5">
            <p class="mb-5">
                <img loading="lazy" class="w-100 h-auto d-block" src="{{ asset('storage/' . $post->image) }}" width=""
                     height="" alt="{{ $post->title }}"/>
            </p>
            <div class="mw-930">
                {!! $post->content !!}
            </div>

        </div>
    </section>
</div>
