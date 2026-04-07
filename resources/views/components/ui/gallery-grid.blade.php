@props([
    'images'  => [],
    'columns' => 3,
])

@php
$colClass = match((int)$columns) {
    2       => 'grid-cols-1 sm:grid-cols-2',
    4       => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4',
    default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
};
@endphp

@if(count($images) > 0)
<div class="grid {{ $colClass }} gap-3 md:gap-4">
    @foreach($images as $image)
    <div class="group relative overflow-hidden rounded bg-surface-container aspect-square">
        <img
            src="{{ $image['src'] ?? $image }}"
            alt="{{ $image['alt'] ?? '' }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
        @if(!empty($image['caption']))
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-on-surface/70 to-transparent px-4 py-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
            <p class="text-xs text-surface-container-lowest font-medium leading-snug">
                {{ $image['caption'] }}
            </p>
        </div>
        @endif
    </div>
    @endforeach
</div>
@else
<p class="text-on-surface-variant text-sm py-8 text-center">Galerie je prázdná.</p>
@endif
