@push('meta')
    <!-- Meta Title -->
    <title>Payments | {{ config('app.name') }}</title>

    <!-- Meta Description -->
    <meta name="description" content="Learn about the secure payment options available at {{ config('app.name') }}. We use Stripe to ensure safe, encrypted transactions for all orders.">

    <!-- Meta Keywords -->
    <meta name="keywords" content="secure payments, Stripe payments, online payment security, encrypted transactions, {{ config('app.name') }}">

    <!-- Open Graph Tags for Social Sharing -->
    <meta property="og:title" content="Payments | {{ config('app.name') }}">
    <meta property="og:description" content="Discover the secure payment options with Stripe at {{ config('app.name') }}. Your transactions are protected with the highest level of encryption.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('storage/assets/site_logo.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
@endpush
<div>
    <h2 class="text-center">Payments</h2>
</div>
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Payments",
      "description": "Learn about the secure payment options at {{ config('app.name') }}, where Stripe ensures safe, encrypted transactions.",
      "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ url('storage/assets/site_logo.png') }}"
        }
      },
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ url()->current() }}"
      },
      "image": "{{ url('storage/assets/site_logo.png') }}",
      "paymentAccepted": ["Credit Card", "Debit Card", "Apple Pay"],
      "potentialAction": {
        "@type": "ViewAction",
        "target": "{{ url()->current() }}"
      },
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
            "name": "Payments",
            "item": "{{ url()->current() }}"
          }
        ]
      },
      "sameAs": [
        "{{ config('app.social.facebook') }}",
        "{{ config('app.social.instagram') }}"
      ]
    }
</script>

