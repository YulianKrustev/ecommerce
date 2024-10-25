@push('meta')
    <meta name="description" content="Explore our blog at Little Sailors Malta for the latest updates on baby and children's clothing, accessories, and toys. Stay informed with tips, trends, and parenting advice.">
    <meta name="keywords" content="baby clothing blog, kids accessories blog, Malta parenting blog, baby trends, children's fashion, kids clothing articles">
    <meta property="og:title" content="Blog | {{ config('app.name') }}">
    <meta property="og:description" content="Explore our blog at Little Sailors Malta for the latest updates on baby and children's clothing, accessories, and toys.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('storage/assets/site_logo.png') }}">
    <link rel="canonical" href="{{ url()->current() }}">
@endpush
@push('title')
    Blog | {{ config('app.name') }}
@endpush
<section class="products-grid shop-checkout container mb-4 pb-4 pt-12">
    <div class="mw-930 ">
        <h1 class="page-title">BLOG</h1>
    </div>

    <div class="row mb-14">
        @forelse($posts as $post)
            <div wire:key="{{ $post->id }}" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                    <div class="pc__img-wrapper">
                        <a wire:navigate href="/blog/{{ $post->slug }}">
                            <img loading="lazy" src="{{ asset('storage/' . $post->image) }}"
                                 width="330" height="400"
                                 alt="{{ $post->name }}" class="pc__img">
                        </a>
                    </div>

                    <div class="pc__info relative">
                        <h2 class="pc__title">
                            <a wire:navigate
                               href="/blog/{{ $post->slug }}">{{ $post->title }}</a>
                        </h2>
                    </div>
                </div>
            </div>
        @empty
            <span class="text-center py-10 text-2xl font-semibold text-slate-600 mb-82">
                No posts available in Blog!
            </span>
        @endforelse
    </div>
    <div class="pagination">
        {{ $posts->links() }}
    </div>
</section>

<<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Blog",
      "headline": "Blog | {{ config('app.name') }}",
  "description": "Explore the Little Sailors Malta blog for parenting tips, fashion trends, and baby clothing advice.",
  "publisher": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "url": "{{ config('app.url') }}",
    "logo": "{{ url('storage/assets/site_logo.png') }}"
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url()->current() }}"
  },
  "image": "{{ url('storage/assets/site_logo.png') }}",
  "url": "{{ url()->current() }}",
  "datePublished": "{{ $posts->first()?->created_at->toIso8601String() ?? now()->toIso8601String() }}",
  "author": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}"
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
      }
    ]
  }
}
</script>

