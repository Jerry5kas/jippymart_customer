@php
    // Static SEO implementation for performance optimization
    // SEO models removed to prevent 503 errors on shared hosting

    // Determine page key based on current route or passed parameter
    $pageKey = $pageKey ?? 'home';

    // Get dynamic data if provided
    $dynamicTitle = $dynamicTitle ?? null;
    $dynamicDescription = $dynamicDescription ?? null;
    $dynamicImage = $dynamicImage ?? null;
    $dynamicKeywords = $dynamicKeywords ?? null;

    // Static SEO settings for performance
    $siteName = 'JippyMart';
    $siteDescription = 'Get groceries, medicines, and daily essentials delivered to your doorstep. Fast delivery, quality products, and great prices.';
    $defaultKeywords = 'groceries, delivery, online shopping, food delivery, medicines, essentials';

    // Build final SEO values with static fallbacks
    $finalTitle = $dynamicTitle ?: $siteName;
    $finalDescription = $dynamicDescription ?: $siteDescription;
    $finalKeywords = $dynamicKeywords ?: $defaultKeywords;
    $finalOgImage = $dynamicImage ?: '/images/logo.png';

    // Ensure title includes site name if not already present
    if (!str_contains($finalTitle, $siteName)) {
        $finalTitle = $finalTitle . ' - ' . $siteName;
    }

    // Get current URL
    $currentUrl = url()->current();

    // Get canonical URL (remove query parameters)
    $canonicalUrl = url()->current();
@endphp

<!-- SEO Meta Tags -->
<title>{{ $finalTitle }}</title>
<meta name="description" content="{{ $finalDescription }}">
<meta name="keywords" content="{{ $finalKeywords }}">
<meta name="author" content="{{ $siteName }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Open Graph Meta Tags -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $finalTitle }}">
<meta property="og:description" content="{{ $finalDescription }}">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:site_name" content="{{ $siteName }}">
@if($finalOgImage)
<meta property="og:image" content="{{ url($finalOgImage) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $finalTitle }}">
@endif

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $finalTitle }}">
<meta name="twitter:description" content="{{ $finalDescription }}">
@if($finalOgImage)
<meta name="twitter:image" content="{{ url($finalOgImage) }}">
@endif

<!-- Additional Meta Tags -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="theme-color" content="#007bff">
<meta name="msapplication-TileColor" content="#007bff">

<!-- Google Analytics - Static implementation for performance -->
<!-- Add your Google Analytics ID here if needed -->
<!--
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'GA_MEASUREMENT_ID');
</script>
-->

<!-- Structured Data (JSON-LD) -->
@if(isset($structuredData) && is_array($structuredData))
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

<!-- Default Organization Schema -->
@if(!isset($structuredData) || !isset($structuredData['@type']) || $structuredData['@type'] !== 'Organization')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "{{ $siteName }}",
  "url": "{{ url('/') }}",
  "logo": "{{ url('/images/logo.png') }}",
  "description": "{{ $siteDescription }}",
  "sameAs": [
    "https://www.facebook.com/jippymart",
    "https://www.instagram.com/jippymart"
  ]
}
</script>
@endif

<!-- Breadcrumb Schema -->
@if(isset($breadcrumbs) && is_array($breadcrumbs))
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    @foreach($breadcrumbs as $index => $breadcrumb)
    {
      "@type": "ListItem",
      "position": {{ $index + 1 }},
      "name": "{{ $breadcrumb['name'] }}",
      "item": "{{ $breadcrumb['url'] }}"
    }{{ $index < count($breadcrumbs) - 1 ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif

<!-- Website Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "{{ $siteName }}",
  "url": "{{ url('/') }}",
  "description": "{{ $siteDescription }}",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ url('/search') }}?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>

