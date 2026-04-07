@props([
    'title'    => '',
    'subtitle' => null,
    'bg'       => 'surface',
])

@php
$bgClass = match($bg) {
    'surface-container-low'     => 'bg-surface-container-low',
    'surface-container'         => 'bg-surface-container',
    'surface-container-high'    => 'bg-surface-container-high',
    'surface-container-highest' => 'bg-surface-container-highest',
    'surface-container-lowest'  => 'bg-surface-container-lowest',
    'primary'                   => 'bg-primary text-on-primary',
    default                     => 'bg-surface',
};
@endphp

<section class="{{ $bgClass }} py-20 md:py-32">
    <div class="max-w-screen-2xl mx-auto px-6 md:px-8">

        @if($title || $subtitle)
        <div class="mb-12 md:mb-16 max-w-2xl">
            @if($title)
            <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight leading-tight mb-4">
                {{ $title }}
            </h2>
            @endif
            @if($subtitle)
            <p class="text-on-surface-variant text-lg leading-relaxed">
                {{ $subtitle }}
            </p>
            @endif
        </div>
        @endif

        {{ $slot }}

    </div>
</section>
