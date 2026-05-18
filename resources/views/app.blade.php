<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="dateModified" content="2026-05-19">

        <link rel="canonical" href="{{ url()->current() }}">

        <meta property="og:site_name" content="{{ config('app.name') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:image" content="{{ asset('assets/img/pexels-suhashanjar-36779954.webp') }}" />
        <meta name="twitter:card" content="summary_large_image" />

        <link rel="icon" href="/favicon.svg" type="image/svg+xml">

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
        </x-inertia::head>

        <script type="application/ld+json">
        @php
        echo json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'dndparfems',
            'url' => url('/'),
            'logo' => asset('favicon.svg'),
            'description' => 'Online prodavnica luksuznih parfema. Pažljivo odabrana kolekcija muških, ženskih i uniseks mirisa.',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        @endphp
        </script>

        <script type="application/ld+json">
        @php
        echo json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'dndparfems',
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/pretraga').'?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        @endphp
        </script>

        @php
        $routeName = request()->route()?->getName();
        $breadcrumbs = [];

        $home = ['name' => 'Početna', 'url' => url('/')];

        match ($routeName) {
            'home' => $breadcrumbs = [$home],
            'products.index' => $breadcrumbs = [$home, ['name' => 'Parfemi', 'url' => route('products.index')]],
            'products.show' => $breadcrumbs = [
                $home,
                ['name' => 'Parfemi', 'url' => route('products.index')],
                ['name' => ucwords(str_replace('-', ' ', request()->route('slug') ?? '')), 'url' => url()->current()],
            ],
            'brands.index' => $breadcrumbs = [$home, ['name' => 'Brendovi', 'url' => route('brands.index')]],
            'contact.create' => $breadcrumbs = [$home, ['name' => 'Kontakt', 'url' => route('contact.create')]],
            'cart.index' => $breadcrumbs = [$home, ['name' => 'Korpa', 'url' => route('cart.index')]],
            'checkout.create' => $breadcrumbs = [$home, ['name' => 'Kasa', 'url' => route('checkout.create')]],
            'track-order.create' => $breadcrumbs = [$home, ['name' => 'Prati porudžbinu', 'url' => route('track-order.create')]],
            'orders.show' => $breadcrumbs = [$home, ['name' => 'Porudžbina '.request()->route('orderNumber'), 'url' => url()->current()]],
            'login' => $breadcrumbs = [$home, ['name' => 'Prijava', 'url' => route('login')]],
            'register' => $breadcrumbs = [$home, ['name' => 'Registracija', 'url' => route('register')]],
            'profile' => $breadcrumbs = [$home, ['name' => 'Profil', 'url' => route('profile')]],
            'privacy' => $breadcrumbs = [$home, ['name' => 'Privatnost', 'url' => route('privacy')]],
            'terms' => $breadcrumbs = [$home, ['name' => 'Uslovi korišćenja', 'url' => route('terms')]],
            'products.search' => $breadcrumbs = [$home, ['name' => 'Pretraga', 'url' => route('products.search')]],
            'guides.choose-perfume' => $breadcrumbs = [$home, ['name' => 'Vodič za odabir parfema', 'url' => route('guides.choose-perfume')]],
            'guides.fragrance-notes' => $breadcrumbs = [$home, ['name' => 'Vodič kroz mirisne note', 'url' => route('guides.fragrance-notes')]],
            'guides.edt-vs-edp' => $breadcrumbs = [$home, ['name' => 'EDT vs EDP vodič', 'url' => route('guides.edt-vs-edp')]],
            default => $breadcrumbs = [$home],
        };
        @endphp

        @if (! empty($breadcrumbs) && count($breadcrumbs) > 1)
        <script type="application/ld+json">
        @php
        echo json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($breadcrumbs)->map(fn ($item, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ])->values()->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        @endphp
        </script>
        @endif
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
