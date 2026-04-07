@php
$navItems = [
    ['label' => 'Úvod',            'url' => '/'],
    ['label' => 'O klubu',         'url' => '/o-klubu'],
    ['label' => 'Kodokan Judo',    'url' => '/kodokan-judo'],
    ['label' => 'Tréninky a ceník','url' => '/treninky'],
    ['label' => 'Sebeobrana',      'url' => '/sebeobrana'],
    ['label' => 'Aktuality',       'url' => '/aktuality'],
    ['label' => 'Galerie',         'url' => '/galerie'],
    ['label' => 'Ke stažení',      'url' => '/ke-stazeni'],
    ['label' => 'Odkazy',          'url' => '/odkazy'],
    ['label' => 'Kontakt',         'url' => '/kontakt'],
];
@endphp

<header class="fixed top-0 left-0 right-0 z-50 bg-surface/80 backdrop-blur-md">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-8">
        <div class="flex items-center justify-between h-20" x-data="{ open: false }">

            {{-- Logo --}}
            <a href="/" class="font-headline text-2xl font-black text-primary uppercase tracking-tighter">
                Raion-ryu
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                @foreach($navItems as $item)
                @php
                    if ($item['url'] === '/') {
                        $isActive = request()->is('/') || request()->routeIs('home');
                    } else {
                        $path = ltrim($item['url'], '/');
                        $isActive = request()->is($path) || request()->is($path . '/*');
                    }
                @endphp
                <a
                    href="{{ $item['url'] }}"
                    class="relative px-3 py-2 text-sm font-medium transition-colors
                        {{ $isActive
                            ? 'text-primary after:absolute after:bottom-0 after:left-3 after:right-3 after:h-0.5 after:bg-primary after:rounded-full'
                            : 'text-on-surface opacity-80 hover:text-primary hover:opacity-100' }}"
                >
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>

            {{-- Desktop CTA + Mobile Hamburger --}}
            <div class="flex items-center gap-4">
                <a
                    href="/kontakt"
                    class="hidden md:inline-flex items-center px-5 py-2.5 rounded bg-primary-container text-on-primary text-sm font-semibold tracking-wide uppercase transition-opacity hover:opacity-90"
                >
                    Zkušební trénink
                </a>

                {{-- Hamburger --}}
                <button
                    class="md:hidden flex items-center justify-center w-10 h-10 text-on-surface"
                    @click="open = !open"
                    :aria-expanded="open"
                    aria-label="Otevřít menu"
                >
                    <span class="material-symbols-outlined text-2xl" x-show="!open">menu</span>
                    <span class="material-symbols-outlined text-2xl" x-show="open" x-cloak>close</span>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="absolute top-full left-0 right-0 bg-surface/95 backdrop-blur-md md:hidden"
                @click.outside="open = false"
            >
                <nav class="flex flex-col px-6 py-4 gap-1">
                    @foreach($navItems as $item)
                    @php
                        if ($item['url'] === '/') {
                            $isActive = request()->is('/') || request()->routeIs('home');
                        } else {
                            $path = ltrim($item['url'], '/');
                            $isActive = request()->is($path) || request()->is($path . '/*');
                        }
                    @endphp
                    <a
                        href="{{ $item['url'] }}"
                        class="px-4 py-3 text-base font-medium rounded transition-colors
                            {{ $isActive
                                ? 'text-primary bg-surface-container'
                                : 'text-on-surface opacity-80 hover:text-primary hover:bg-surface-container-low hover:opacity-100' }}"
                        @click="open = false"
                    >
                        {{ $item['label'] }}
                    </a>
                    @endforeach
                    <div class="pt-3 pb-1">
                        <a
                            href="/kontakt"
                            class="block text-center px-5 py-3 rounded bg-primary-container text-on-primary text-sm font-semibold tracking-wide uppercase"
                            @click="open = false"
                        >
                            Zkušební trénink
                        </a>
                    </div>
                </nav>
            </div>

        </div>
    </div>
</header>
