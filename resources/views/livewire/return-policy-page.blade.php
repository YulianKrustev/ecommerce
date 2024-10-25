@push('meta')
    <!-- Meta Description -->
    <meta name="description" content="Learn about our hassle-free return policy. Find out how you can easily return or exchange products within our specified timeframe.">

    <!-- Meta Keywords -->
    <meta name="keywords" content="return policy, product return, refund, exchange, return conditions, customer satisfaction, hassle-free returns">

    <!-- Open Graph Tags for Social Sharing -->
    <meta property="og:title" content="Return Policy | {{ config('app.name') }}">
    <meta property="og:description" content="Our return policy is designed to ensure your satisfaction. Find out how to return or exchange products easily.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('storage/assets/site_logo.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('title')
    Return Policy | {{ config('app.name') }}
@endpush
<div>
    <h2 class="text-center">Return Policy</h2>
</div>
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Return Policy",
      "description": "Understand the return and exchange policy at {{ config('app.name') }}. Learn about our return conditions, processing times, and refund options.",
  "url": "{{ url()->current() }}",
  "image": "{{ asset('storage/assets/site_logo.png') }}",
  "breadcrumb": {
    "@type": "BreadcrumbList",
    "itemListElement": [
      { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
      { "@type": "ListItem", "position": 2, "name": "Return Policy", "item": "{{ url()->current() }}" }
    ]
  },
  "mainEntity": {
    "@type": "FAQPage",
    "name": "Return Policy FAQs",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "What is the return period for products?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Customers have 14 days from the delivery date to return items in their original condition for a full refund."
        }
      },
      {
        "@type": "Question",
        "name": "Are there any items that cannot be returned?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Certain items like perishable goods, gift cards, and customized products are non-returnable. Refer to our full policy for more details."
        }
      },
      {
        "@type": "Question",
        "name": "How long does it take to process a refund?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Refunds are typically processed within 5-7 business days after the returned item is received and inspected."
        }
      }
    ]
  }
}
</script>
