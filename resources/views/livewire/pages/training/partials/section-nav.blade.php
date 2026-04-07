@php
$navItems = [
    [
        'label' => 'Přehled tréninků',
        'route' => 'training.index',
    ],
    [
        'label' => 'Ceník',
        'route' => 'training.pricing',
    ],
    [
        'label' => 'Přípravka',
        'route' => 'training.preparatory',
    ],
    [
        'label' => 'Pokročilí',
        'route' => 'training.advanced',
    ],
    [
        'label' => 'Dospělí',
        'route' => 'training.adults',
    ],
    [
        'label' => 'Hiko-ryu Taijutsu',
        'route' => 'training.hikoryu',
    ],
    [
        'label' => 'Randori',
        'route' => 'training.randori',
    ],
];
@endphp

<div class="bg-surface-container-low sticky top-20 z-40">
    <div class="container mx-auto px-6 md:px-8">
        <nav class="overflow-x-auto -mx-6 md:mx-0" aria-label="Tréninky navigace">
            <div class="flex items-center gap-1 min-w-max px-6 md:px-0 py-1">
                @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a
                    href="{{ route($item['route']) }}"
                    class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors
                        {{ $isActive
                            ? 'bg-surface-container text-primary'
                            : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container' }}"
                    @if($isActive) aria-current="page" @endif
                >
                    {{ $item['label'] }}
                    @if($isActive)
                    <span class="absolute bottom-0 left-4 right-4 h-0.5 bg-primary rounded-full"></span>
                    @endif
                </a>
                @endforeach
            </div>
        </nav>
    </div>
</div>
