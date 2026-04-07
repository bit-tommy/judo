@props([
    'items' => [],
])

<nav class="w-full overflow-x-auto" aria-label="Section navigation">
    <div class="flex items-center gap-1 min-w-max px-6 md:px-0 py-1">
        @foreach($items as $item)
        @php $isActive = $item['active'] ?? false; @endphp
        <a
            href="{{ $item['url'] ?? '#' }}"
            class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors
                {{ $isActive
                    ? 'bg-surface-container text-primary'
                    : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low' }}"
            @if($isActive) aria-current="page" @endif
        >
            {{ $item['label'] ?? '' }}
            @if($isActive)
            <span class="absolute bottom-0 left-4 right-4 h-0.5 bg-primary rounded-full"></span>
            @endif
        </a>
        @endforeach
    </div>
</nav>
