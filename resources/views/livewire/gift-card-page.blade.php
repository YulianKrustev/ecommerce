@push('meta')
    <!-- Meta Title -->
    <title>Gift Card | {{ config('app.name') }}</title>

    <!-- Meta Description -->
    <meta name="description" content="Give the perfect gift with a {{ config('app.name') }} gift card. It's the perfect choice for any occasion, allowing recipients to choose what they love from our collection of high-quality children's clothing and accessories.">

    <!-- Meta Keywords -->
    <meta name="keywords" content="gift card, online gift card, buy gift card, {{ config('app.name') }} gift card, children's clothing gift card, baby clothing gift card, Malta gift card">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="Gift Card | {{ config('app.name') }}">
    <meta property="og:description" content="Give the perfect gift with a {{ config('app.name') }} gift card. Shop high-quality children's clothing, accessories, and toys.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('storage/assets/site_logo.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
@endpush
<div>
    <h2 class="text-center">Gift Card</h2>
</div>
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Product",
      "name": "Gift Card",
      "description": "Give the gift of choice with a {{ config('app.name') }} gift card, perfect for any occasion. Let your loved ones select what they love from our collection of children's clothing, toys, and accessories.",
      "url": "{{ url()->current() }}",
      "image": "{{ url('storage/assets/site_logo.png') }}",
      "brand": {
        "@type": "Brand",
        "name": "{{ config('app.name') }}",
        "logo": "{{ url('storage/assets/site_logo.png') }}"
      },
      "offers": {
        "@type": "Offer",
        "priceCurrency": "EUR",
        "url": "{{ url()->current() }}",
        "availability": "https://schema.org/InStock"
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
            "item": "{{ config('app.url') }}"
          },
          {
            "@type": "ListItem",
            "position": 2,
            "name": "Gift Card",
            "item": "{{ url()->current() }}"
          }
        ]
      }
    }
</script>

