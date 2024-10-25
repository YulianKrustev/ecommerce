@push('meta')
    <!-- Meta Tags -->
    <meta name="description" content="{{ $post->meta_description }}">
    <meta name="keywords" content="{{ implode(',', $post->meta_keywords ?? []) }}">

    <!-- Open Graph Tags for Social Sharing -->
    <meta property="og:title" content="{{ $post->meta_title }}">
    <meta property="og:description" content="{{ $post->meta_description }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url('/blog/' . $post->slug) }}">
    <meta property="og:image" content="{{ asset('storage/' . $post->image) }}">
    <meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->author->name ?? config('app.name') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url('/blog/' . $post->slug) }}">
@endpush

@push('title')
    {{ $post->title }} | {{ config('app.name') }}
@endpush

<div>
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

<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BlogPosting",
      "headline": "{{ $post->meta_title }}",
  "description": "{{ $post->meta_description }}",
  "image": "{{ asset('storage/' . $post->image) }}",
  "author": {
    "@type": "Person",
    "name": "{{ $post->author->name ?? config('app.name') }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ url('storage/assets/site_logo.png') }}"
    }
  },
  "datePublished": "{{ $post->created_at->toIso8601String() }}",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url('/blog/' . $post->slug) }}"
  },
  "sameAs": [
    "{{ config('app.social.facebook') }}",
    "{{ config('app.social.instagram') }}"
  ],
  "breadcrumb": {
    "@type": "BreadcrumbList",
    "itemListElement": [
      {
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "{{ url('/') }}"
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": "Blog",
        "item": "{{ url('/blog') }}"
      },
      {
        "@type": "ListItem",
        "position": 3,
        "name": "{{ $post->title }}",
        "item": "{{ url('/blog/' . $post->slug) }}"
      }
    ]
  }
}
</script>

