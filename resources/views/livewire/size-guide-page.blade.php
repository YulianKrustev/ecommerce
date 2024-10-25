@push('meta')
    <!-- Meta Description -->
    <meta name="description" content="Find the perfect fit with our detailed size guide. Learn how to measure accurately for children's clothing to ensure comfort and quality.">

    <!-- Meta Keywords -->
    <meta name="keywords" content="size guide, clothing sizes, kids size chart, measurement guide, fit guide, children's sizes, accurate sizing">

    <!-- Open Graph Tags for Social Sharing -->
    <meta property="og:title" content="Size Guide | {{ config('app.name') }}">
    <meta property="og:description" content="Use our size guide to find the right fit for children's clothing. Detailed charts and measurement tips help ensure comfort and quality.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('storage/assets/site_logo.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('title')
    Size Guide | {{ config('app.name') }}
@endpush
<div>
    <h2 class="text-center">Size Guide</h2>
</div>
<!-- Structured Data Schema (JSON-LD) -->
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Size Guide",
      "description": "Our comprehensive size guide helps you find the perfect fit for children's clothing. Get accurate measurement instructions and refer to our detailed size charts.",
      "url": "{{ url()->current() }}",
  "image": "{{ asset('storage/assets/site_logo.png') }}",
  "breadcrumb": {
    "@type": "BreadcrumbList",
    "itemListElement": [
      { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
      { "@type": "ListItem", "position": 2, "name": "Size Guide", "item": "{{ url()->current() }}" }
    ]
  },
  "mainEntity": {
    "@type": "FAQPage",
    "name": "Size Guide FAQs",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "How do I measure my child for the correct size?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Use a tape measure to determine chest, waist, and height measurements for accurate sizing. Follow our detailed instructions for precise results."
        }
      },
      {
        "@type": "Question",
        "name": "What if my child is between sizes?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "If your child is between sizes, we recommend sizing up for added comfort and room to grow."
        }
      },
      {
        "@type": "Question",
        "name": "Do sizes vary for different types of clothing?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes, sizes may vary slightly depending on the style or fabric. Refer to the specific size guide for each product for best fit information."
        }
      }
    ]
  }
}
</script>
