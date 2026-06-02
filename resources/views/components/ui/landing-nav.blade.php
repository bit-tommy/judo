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
    $onInst = request()->routeIs('instructors');
    $onGal  = request()->routeIs('gallery');
    $judoActive = $onDl || $onInst;          // „Judo" dropdown je aktivní na svých podstránkách

    // Položky dropdownu „Judo" – sdílené pro desktop i mobil.
    $judo = [
        ['label' => 'Úvod',              'href' => route('home'),        'active' => false],
        ['label' => 'Trenéři',           'href' => route('instructors'), 'active' => $onInst],
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

<nav x-data="{ mobile: false }" @keydown.escape.window="mobile = false">
    <x-ui.logo href="{{ route('home') }}" size="48px" />

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
                        <a href="{{ $it['href'] }}" class="{{ $it['active'] ? 'active' : '' }}">
                            {{ $it['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        <li><a href="{{ $home }}#techniky">Techniky</a></li>
        <li><a href="{{ $home }}#deti">Děti</a></li>

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
                        <a href="{{ $it['href'] }}" class="{{ $it['active'] ? 'active' : '' }}">
                            {{ $it['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        <li><a href="{{ route('gallery') }}" class="{{ $onGal ? 'active' : '' }}">Galerie</a></li>
        <li><a href="{{ $home }}#kontakt">Kontakt</a></li>
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
                    <a href="{{ $it['href'] }}" class="{{ $it['active'] ? 'active' : '' }}" @click="mobile = false">
                        {{ $it['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <a href="{{ $home }}#techniky" @click="mobile = false">Techniky</a>
        <a href="{{ $home }}#deti" @click="mobile = false">Děti</a>

        <div class="nav-mobile-group" x-data="{ open: {{ $onStay ? 'true' : 'false' }} }">
            <button type="button" @click="open = !open" :aria-expanded="open.toString()">
                Japonsko<span class="nav-dd-caret" :class="open ? 'is-open' : ''" aria-hidden="true">▾</span>
            </button>
            <div class="nav-mobile-sub" x-show="open" x-cloak>
                @foreach ($japan as $it)
                    <a href="{{ $it['href'] }}" class="{{ $it['active'] ? 'active' : '' }}" @click="mobile = false">
                        {{ $it['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <a href="{{ route('gallery') }}" class="{{ $onGal ? 'active' : '' }}" @click="mobile = false">Galerie</a>
        <a href="{{ $home }}#kontakt" @click="mobile = false">Kontakt</a>
        <a href="{{ $home }}#kontakt" class="nav-cta" @click="mobile = false">Přijďte trénovat</a>
    </div>
</nav>
