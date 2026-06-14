{{--
    Sdílená navigace landing designu (Noto Serif / krémová + červená).

    Používá ji domovská stránka i samostatné podstránky. Sama pozná, zda jsme
    na úvodu (kotvy typu #judo scrollují v rámci stránky) nebo na podstránce
    (kotvy míří na route('home').'#judo').

    Skupina „Japonsko" je dropdown:
      • desktop – otevírá se hoverem i klikem (caret se otáčí), položka sama
        nikam nescrolluje, takže nevzniká dvojznačnost „odkaz vs. menu";
      • mobil – hamburger panel s rozbalovacím (accordion) submenu.

    Alpine.js je k dispozici přes Livewire (@livewireScripts v layoutu).
--}}
@php
    $onHome = request()->routeIs('home');
    $home   = $onHome ? '' : route('home');          // prefix pro kotvy na úvodní stránku
    $onStay = request()->routeIs('kodokan.masters-stay');
    $onDl   = request()->routeIs('downloads');
    $onInst  = request()->routeIs('instructors');
    $onDeti  = request()->routeIs('children');
    $onAkce  = request()->routeIs('events');
    $onCenik = request()->routeIs('pricing');
    $onGal   = request()->routeIs('gallery');
    $judoActive = $onDl || $onInst || $onAkce || $onCenik; // „Judo" dropdown je aktivní na svých podstránkách

    // Položky dropdownu „Judo" – sdílené pro desktop i mobil.
    $judo = [
        ['label' => 'Trenéři',           'href' => route('instructors'), 'active' => $onInst],
        ['label' => 'Úvod',              'href' => route('home'),        'active' => false],
        ['label' => 'Akce',              'href' => route('events'),      'active' => $onAkce],
        ['label' => 'Ceník',             'href' => route('pricing'),     'active' => $onCenik],
        ['label' => 'Historie',          'href' => $home . '#judo',      'active' => false],
        ['label' => 'Klub – ke stažení', 'href' => route('downloads'),   'active' => $onDl],
    ];

    // Položky dropdownu „Japonsko" – sdílené pro desktop i mobil.
    $japan = [
        ['label' => 'Japonští mistři u nás', 'href' => $home . '#mistri',                 'active' => false],
        ['label' => 'Pobyt mistrů u nás',    'href' => route('kodokan.masters-stay'),      'active' => $onStay],
        ['label' => 'Trénink v Japonsku',    'href' => $home . '#japonsko',                'active' => false],
    ];
@endphp

@php
    // wire:navigate jen pro odkazy na skutečné routy. Kotvy (#…) necháme na
    // klasickém scrollu – po SPA navigaci je skok na #kotvu nespolehlivý.
    $isRoute = fn (string $href) => ! str_contains($href, '#');
@endphp
<nav x-data="{ mobile: false }" @keydown.escape.window="mobile = false">
    <x-ui.logo href="{{ route('home') }}" size="48px" wire:navigate />

    {{-- ─── Desktop ─── --}}
    <ul class="nav-links">
        <li class="nav-dd"
            x-data="{ open: false }"
            @mouseenter="open = true"
            @mouseleave="open = false"
            @click.outside="open = false">
            <button type="button"
                    class="nav-dd-trigger {{ $judoActive ? 'active' : '' }}"
                    @click="open = !open"
                    :aria-expanded="open.toString()">
                Judo<span class="nav-dd-caret" aria-hidden="true">▾</span>
            </button>
            <ul class="nav-dd-menu nav-dd-menu--left" x-show="open" x-cloak x-transition.opacity.duration.150ms>
                @foreach ($judo as $it)
                    <li>
                        <a href="{{ $it['href'] }}" @class(['active' => $it['active']]) @if ($isRoute($it['href'])) wire:navigate @endif>
                            {{ $it['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        <li><a href="{{ $home }}#techniky">Techniky</a></li>
        <li><a href="{{ route('children') }}" class="{{ $onDeti ? 'active' : '' }}" wire:navigate>Tréninky dětí</a></li>

        <li class="nav-dd"
            x-data="{ open: false }"
            @mouseenter="open = true"
            @mouseleave="open = false"
            @click.outside="open = false">
            <button type="button"
                    class="nav-dd-trigger {{ $onStay ? 'active' : '' }}"
                    @click="open = !open"
                    :aria-expanded="open.toString()">
                Japonsko<span class="nav-dd-caret" aria-hidden="true">▾</span>
            </button>
            <ul class="nav-dd-menu" x-show="open" x-cloak x-transition.opacity.duration.150ms>
                @foreach ($japan as $it)
                    <li>
                        <a href="{{ $it['href'] }}" @class(['active' => $it['active']]) @if ($isRoute($it['href'])) wire:navigate @endif>
                            {{ $it['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        <li><a href="{{ route('gallery') }}" class="{{ $onGal ? 'active' : '' }}" wire:navigate>Galerie</a></li>
        <li><a href="{{ $home }}#kontakt">Kontakt</a></li>
        <li><a href="{{ $home }}#faq">FAQ</a></li>
    </ul>

    {{-- ─── CTA + hamburger ─── --}}
    <div class="nav-right">
        <a href="{{ $home }}#kontakt" class="nav-cta">Přijďte trénovat</a>
        <button type="button" class="nav-burger"
                @click="mobile = !mobile"
                :aria-expanded="mobile.toString()"
                aria-label="Otevřít menu">
            <span x-show="!mobile">☰</span>
            <span x-show="mobile" x-cloak>✕</span>
        </button>
    </div>

    {{-- ─── Mobilní panel ─── --}}
    <div class="nav-mobile" x-show="mobile" x-cloak x-transition
         @click.outside="mobile = false">
        <div class="nav-mobile-group" x-data="{ open: {{ $judoActive ? 'true' : 'false' }} }">
            <button type="button" @click="open = !open" :aria-expanded="open.toString()">
                Judo<span class="nav-dd-caret" :class="open ? 'is-open' : ''" aria-hidden="true">▾</span>
            </button>
            <div class="nav-mobile-sub" x-show="open" x-cloak>
                @foreach ($judo as $it)
                    <a href="{{ $it['href'] }}" @class(['active' => $it['active']]) @if ($isRoute($it['href'])) wire:navigate @endif @click="mobile = false">
                        {{ $it['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <a href="{{ $home }}#techniky" @click="mobile = false">Techniky</a>
        <a href="{{ route('children') }}" class="{{ $onDeti ? 'active' : '' }}" wire:navigate @click="mobile = false">Tréninky dětí</a>

        <div class="nav-mobile-group" x-data="{ open: {{ $onStay ? 'true' : 'false' }} }">
            <button type="button" @click="open = !open" :aria-expanded="open.toString()">
                Japonsko<span class="nav-dd-caret" :class="open ? 'is-open' : ''" aria-hidden="true">▾</span>
            </button>
            <div class="nav-mobile-sub" x-show="open" x-cloak>
                @foreach ($japan as $it)
                    <a href="{{ $it['href'] }}" @class(['active' => $it['active']]) @if ($isRoute($it['href'])) wire:navigate @endif @click="mobile = false">
                        {{ $it['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <a href="{{ route('gallery') }}" class="{{ $onGal ? 'active' : '' }}" wire:navigate @click="mobile = false">Galerie</a>
        <a href="{{ $home }}#kontakt" @click="mobile = false">Kontakt</a>
        <a href="{{ $home }}#faq" @click="mobile = false">FAQ</a>
        <a href="{{ $home }}#kontakt" class="nav-cta" @click="mobile = false">Přijďte trénovat</a>

        <div class="footer-social">
            <a href="https://www.instagram.com/raion_ryu_judo_club_praha" target="_blank" rel="noopener" aria-label="Instagram">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </a>
            <a href="https://www.facebook.com/profile.php?id=100063623530603" target="_blank" rel="noopener" aria-label="Facebook">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047v-2.66c0-3.025 1.791-4.697 4.533-4.697 1.313 0 2.686.235 2.686.235v2.97h-1.514c-1.491 0-1.956.93-1.956 1.886v2.266h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
            </a>
            <a href="https://www.tiktok.com/@rubinekfilip" target="_blank" rel="noopener" aria-label="TikTok">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
            </a>
        </div>
    </div>
</nav>

{{-- Plovoucí CTA jen pro mobil (CSS .floating-cta v layoutu); skok na rozvrh + formulář.
     MUSÍ být mimo <nav> — nav má backdrop-filter, který vytváří containing block
     pro position:fixed; uvnitř by se tlačítko ukotvilo k navigaci a zůstalo nahoře
     přes menu místo dole u spodního okraje obrazovky. --}}
<a class="floating-cta" href="{{ $home }}#inquiry">První trénink zdarma</a>
