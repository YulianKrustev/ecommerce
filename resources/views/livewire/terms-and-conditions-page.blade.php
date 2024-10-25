@push('meta')
    <!-- Meta Description -->
    <meta name="description" content="Review the terms and conditions for using {{ config('app.name') }}. Understand your rights, obligations, and the policies governing our services.">

    <!-- Meta Keywords -->
    <meta name="keywords" content="terms and conditions, user agreement, policies, site usage, {{ config('app.name') }} policies, legal agreement, rights and obligations">

    <!-- Open Graph Tags for Social Sharing -->
    <meta property="og:title" content="Terms and Conditions | {{ config('app.name') }}">
    <meta property="og:description" content="Explore the terms and conditions governing the use of {{ config('app.name') }}. This document outlines your rights and responsibilities as a user.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('storage/assets/site_logo.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('title')
    Terms and Conditions | {{ config('app.name') }}
@endpush
<div>
    <h2 class="text-center">Terms and Conditions</h2>
</div>
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Terms and Conditions",
      "description": "The terms and conditions document for {{ config('app.name') }} outlines the policies, rules, and guidelines for using our services.",
  "url": "{{ url()->current() }}",
  "image": "{{ asset('storage/assets/site_logo.png') }}",
  "breadcrumb": {
    "@type": "BreadcrumbList",
    "itemListElement": [
      { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
      { "@type": "ListItem", "position": 2, "name": "Terms and Conditions", "item": "{{ url()->current() }}" }
    ]
  },
  "mainEntity": {
    "@type": "FAQPage",
    "name": "Terms and Conditions FAQs",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "What are the terms of using this website?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "The terms of use for this website cover user responsibilities, limitations, and the legal obligations involved in using our services."
        }
      },
      {
        "@type": "Question",
        "name": "Can I use content from {{ config('app.name') }} for my own purposes?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Content provided on {{ config('app.name') }} is owned by the site and may not be reused without permission."
        }
      },
      {
        "@type": "Question",
        "name": "What policies govern my interactions on the website?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Our terms include policies on user behavior, prohibited activities, and security measures to ensure a safe experience for all users."
        }
      }
    ]
  }
}
</script>
