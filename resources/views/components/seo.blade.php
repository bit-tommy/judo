{{--
    Sdílená SEO hlavička – meta description, canonical, Open Graph, Twitter Card,
    favicony, theme-color a JSON-LD strukturovaná data.

    Použití v layoutu:
        <x-seo :title="$title ?? null" :description="$metaDescription ?? null" />

    Per-stránka (Volt) se předává přes #[Layout(..., ['metaDescription' => '...'])].
    Absolutní URL se staví ze skutečného host hlavičky requestu, takže fungují
    bez ohledu na hodnotu APP_URL (dev i produkce).
--}}
@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'noindex' => false,
])

@php
    $siteName = 'Judo Club Raion-ryu';
    $defaultTitle = 'Judo Club Raion-ryu | Kódókan Judo Praha & Vodochody';
    $defaultDescription = 'Judo Club Raion-ryu – tradiční Kódókan Judo a Hiko-ryu Taijutsu v Praze 8 a ve Vodochodech. Tréninky pro děti, mládež i dospělé pod vedením japonských mistrů. Od roku 2010.';

    $base = rtrim(request()->getSchemeAndHttpHost(), '/');
    $canonical = $base . '/' . ltrim(request()->path() === '/' ? '' : request()->path(), '/');
    $canonical = rtrim($canonical, '/') ?: $base . '/';

    $metaTitle = $title ?: $defaultTitle;
    $metaDesc = $description ?: $defaultDescription;
    $ogImage = $base . '/' . ltrim($image ?: 'images/og-image.jpg', '/');

    // JSON-LD stavíme zde v @php bloku – Blade tu neskenuje direktivy,
    // takže klíč '@context' nepadne za oběť direktivě @context.
    $jsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'SportsClub',
        'name' => 'Judo Club Raion-ryu',
        'alternateName' => 'Škola bojových umění Rubidó – JC Raion-Ryu',
        'description' => $defaultDescription,
        'url' => $base . '/',
        'logo' => $base . '/images/logo-vodochody.jpeg',
        'image' => $ogImage,
        'telephone' => '+420777166156',
        'foundingDate' => '2010',
        'sport' => ['Judo', 'Bojová umění'],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Československé armády 363',
            'addressLocality' => 'Odolena Voda',
            'postalCode' => '250 70',
            'addressCountry' => 'CZ',
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => 50.2297614,
            'longitude' => 14.4126358,
        ],
        'location' => [
            [
                '@type' => 'Place',
                'name' => 'Pobočka Praha 8',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Za Invalidovnou 579/3',
                    'addressLocality' => 'Praha 8',
                    'addressCountry' => 'CZ',
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => 50.0939230,
                    'longitude' => 14.4618969,
                ],
            ],
            [
                '@type' => 'Place',
                'name' => 'Pobočka Vodochody',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Průběžná 50',
                    'addressLocality' => 'Vodochody',
                    'postalCode' => '250 69',
                    'addressCountry' => 'CZ',
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => 50.2031226,
                    'longitude' => 14.4008113,
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

{{-- Primární meta --}}
<meta name="description" content="{{ $metaDesc }}"/>
<link rel="canonical" href="{{ $canonical }}"/>
<meta name="robots" content="{{ $noindex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large' }}"/>
<meta name="author" content="{{ $siteName }}"/>
<meta name="theme-color" content="#C0261E"/>

{{-- Open Graph --}}
<meta property="og:type" content="{{ $type }}"/>
<meta property="og:site_name" content="{{ $siteName }}"/>
<meta property="og:title" content="{{ $metaTitle }}"/>
<meta property="og:description" content="{{ $metaDesc }}"/>
<meta property="og:url" content="{{ $canonical }}"/>
<meta property="og:image" content="{{ $ogImage }}"/>
<meta property="og:image:width" content="1200"/>
<meta property="og:image:height" content="630"/>
<meta property="og:image:alt" content="{{ $siteName }} – škola bojových umění"/>
<meta property="og:locale" content="cs_CZ"/>

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="{{ $metaTitle }}"/>
<meta name="twitter:description" content="{{ $metaDesc }}"/>
<meta name="twitter:image" content="{{ $ogImage }}"/>

{{-- Favicony / ikony --}}
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any"/>
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}"/>
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}"/>
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}"/>
<link rel="manifest" href="{{ asset('site.webmanifest') }}"/>

{{-- JSON-LD: sportovní klub (lokální SEO) – sestaveno výše v @php bloku --}}
<script type="application/ld+json">{!! $jsonLd !!}</script>
