@push('meta')
    <!-- Meta Title -->
    <title>Privacy Policy | {{ config('app.name') }}</title>

    <!-- Meta Description -->
    <meta name="description" content="Learn how {{ config('app.name') }} protects your personal information. Review our Privacy Policy for details on data handling, security measures, and user rights.">

    <!-- Meta Keywords -->
    <meta name="keywords" content="privacy policy, data protection, personal information, data security, {{ config('app.name') }} privacy">

    <!-- Open Graph Tags for Social Sharing -->
    <meta property="og:title" content="Privacy Policy | {{ config('app.name') }}">
    <meta property="og:description" content="Review the {{ config('app.name') }} Privacy Policy to understand our practices in protecting your personal data and ensuring online security.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('storage/assets/site_logo.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
@endpush
<div>
    <h2 class="text-center">Privacy Policy</h2>
</div>

<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Privacy Policy",
      "description": "Learn how {{ config('app.name') }} protects your personal information. Review our Privacy Policy for details on data handling, security measures, and user rights.",
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
            "name": "Privacy Policy",
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

