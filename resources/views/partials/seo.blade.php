@php
    use App\Models\SeoPage;
    use App\Models\SeoSetting;

    // Get global SEO settings
    $globalSettings = SeoSetting::getGlobalSettings();

    // Determine page key based on current route or passed parameter
    $pageKey = $pageKey ?? 'home';

    // Get SEO data for current page
    $seoData = SeoPage::getForPage($pageKey);

    // Get dynamic data if provided
    $dynamicTitle = $dynamicTitle ?? null;
    $dynamicDescription = $dynamicDescription ?? null;
    $dynamicImage = $dynamicImage ?? null;
    $dynamicKeywords = $dynamicKeywords ?? null;

    // Build final SEO values
    $finalTitle = $dynamicTitle ?: ($seoData ? $seoData->getMetaTitle() : ($globalSettings['site_name'] ?? 'JippyMart'));
    $finalDescription = $dynamicDescription ?: ($seoData ? $seoData->getMetaDescription() : ($globalSettings['site_description'] ?? 'Get groceries, medicines, and daily essentials delivered to your doorstep'));
    $finalKeywords = $dynamicKeywords ?: ($seoData ? $seoData->keywords : 'groceries, delivery, online shopping');
    $finalOgImage = $dynamicImage ?: ($seoData ? $seoData->getOgImage() : ($globalSettings['default_og_image'] ?? null));

    // Ensure title includes site name if not already present
    if (!str_contains($finalTitle, ($globalSettings['site_name'] ?? 'JippyMart'))) {
        $finalTitle = $finalTitle . ' - ' . ($globalSettings['site_name'] ?? 'JippyMart');
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
<meta name="author" content="{{ $globalSettings['site_name'] }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Open Graph Meta Tags -->
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $seoData ? $seoData->getOgTitle($finalTitle) : $finalTitle }}">
<meta property="og:description" content="{{ $seoData ? $seoData->getOgDescription($finalDescription) : $finalDescription }}">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:site_name" content="{{ $globalSettings['site_name'] }}">
@if($finalOgImage)
<meta property="og:image" content="{{ url($finalOgImage) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $finalTitle }}">
@endif

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoData ? $seoData->getOgTitle($finalTitle) : $finalTitle }}">
<meta name="twitter:description" content="{{ $seoData ? $seoData->getOgDescription($finalDescription) : $finalDescription }}">
@if($finalOgImage)
<meta name="twitter:image" content="{{ url($finalOgImage) }}">
@endif
@if($globalSettings['twitter_handle'])
<meta name="twitter:site" content="{{ $globalSettings['twitter_handle'] }}">
<meta name="twitter:creator" content="{{ $globalSettings['twitter_handle'] }}">
@endif

<!-- Additional Meta Tags -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="theme-color" content="#007bff">
<meta name="msapplication-TileColor" content="#007bff">

<!-- Google Analytics -->
@if($globalSettings['google_analytics_id'])
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $globalSettings['google_analytics_id'] }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $globalSettings['google_analytics_id'] }}');
</script>
@endif

<!-- Google Search Console Verification -->
@if($globalSettings['google_search_console_verification'])
<meta name="google-site-verification" content="{{ $globalSettings['google_search_console_verification'] }}">
@endif

<!-- Facebook App ID -->
@if($globalSettings['facebook_app_id'])
<meta property="fb:app_id" content="{{ $globalSettings['facebook_app_id'] }}">
@endif

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
  "name": "{{ $globalSettings['site_name'] }}",
  "url": "{{ url('/') }}",
  "logo": "{{ url('/images/logo.png') }}",
  "description": "{{ $globalSettings['site_description'] }}",
  @if($globalSettings['contact_email'])
  "email": "{{ $globalSettings['contact_email'] }}",
  @endif
  @if($globalSettings['contact_phone'])
  "telephone": "{{ $globalSettings['contact_phone'] }}",
  @endif
  @if($globalSettings['business_address'])
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ $globalSettings['business_address'] }}"
  },
  @endif
  "sameAs": [
    @if($globalSettings['twitter_handle'])
    "https://twitter.com/{{ str_replace('@', '', $globalSettings['twitter_handle']) }}",
    @endif
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
  "name": "{{ $globalSettings['site_name'] }}",
  "url": "{{ url('/') }}",
  "description": "{{ $globalSettings['site_description'] }}",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ url('/search') }}?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>

