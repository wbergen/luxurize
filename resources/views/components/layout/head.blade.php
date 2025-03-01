<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('site.name') }}</title>

    <!-- Externals -->
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">--}}

    <!-- Google tag (gtag.js) -->
{{--    <script async src="https://www.googletagmanager.com/gtag/js?id=G-11Z9KH1L6Z"></script>--}}
{{--    <script>--}}
{{--        window.dataLayer = window.dataLayer || [];--}}
{{--        function gtag(){dataLayer.push(arguments);}--}}
{{--        gtag('js', new Date());--}}
{{----}}
{{--        gtag('config', 'G-11Z9KH1L6Z');--}}
{{--    </script>--}}

    <!-- Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ config('site.logos.square') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ config('site.logos.square') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ config('site.logos.square') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ config('site.logos.square') }}">

    <!-- Meta -->
    <meta name="description" content="{{ config('site.description') }}">
    <meta name="keywords" content="{{ config('site.keywords') }}">

{{--    <link rel="preload" href="your-resource" as="resource-type">--}}
{{--    <link rel="prefetch" href="your-resource">--}}
    <link rel="canonical" href="{{ app('request')->url() }}">


    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ config('site.name') }}">
    <meta property="og:description" content="{{ config('site.description') }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:title" content="{{ config('site.name') }}">
    <meta name="twitter:description" content="{{ config('site.description') }}">

    <!-- Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "WebPage",
            "name": "{{ config('site.name') }}",
            "description": "{{ config('site.description') }}",
            "url": "{{ app('request')->url() }}"
        }
    </script>

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "{{ config('site.ld_type') }}",
          "image": "{{ config('site.logo') }}",
          "url": "{{ config('app.url') }}",
          "logo": "{{ config('site.logo') }}",
          "name": "{{ config('site.name') }}",
          "description": "{{ config('site.description') }}",
          "email": "{{ config('site.email') }}",
          "telephone": "{{ config('site.phone') }}",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ config('site.address.street') }}",
            "addressLocality": "{{ config('site.address.locality') }}",
            "addressCountry": "{{ config('site.address.country') }}",
            "addressRegion": "{{ config('site.address.region') }}",
            "postalCode": "{{ config('site.address.zip') }}"
          }
        }
    </script>
    @vite(['resources/css/scss/app.scss'])
</head>
