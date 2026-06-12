@props([
    'tagline'           => null,
    'title'             => '',
    'subtitle'          => null,
    'primaryCta'        => null,
    'primaryCtaLink'    => '#',
    'secondaryCta'      => null,
    'secondaryCtaLink'  => '#',
    'image'             => null,
])

<section class="relative min-h-[80vh] flex items-center overflow-hidden">

    {{-- Background image --}}
    @if($image)
    <div class="absolute inset-0 z-0">
        <img src="{{ $image }}" alt="" class="w-full h-full object-cover" aria-hidden="true"/>
        <div class="absolute inset-0 bg-gradient-to-r from-surface via-surface/40 to-transparent"></div>
    </div>
    @else
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-surface via-surface-container-low to-surface-container"></div>
    @endif

    {{-- Decorative Japanese characters --}}
    <div class="absolute inset-0 z-0 flex items-center justify-end pr-8 md:pr-16 pointer-events-none select-none" aria-hidden="true">
        <span class="font-headline text-[12rem] md:text-[18rem] lg:text-[22rem] font-black text-primary opacity-[0.04] leading-none">
            
        </span>
    </div>

    {{-- Content --}}
    <div class="relative z-10 max-w-screen-2xl mx-auto px-6 md:px-8 py-20 md:py-32 w-full">
        <div class="max-w-3xl flex flex-col gap-6">

            @if($tagline)
            <div>
                <span class="inline-block px-3 py-1 bg-primary text-on-primary text-xs font-bold uppercase tracking-widest rounded">
                    {{ $tagline }}
                </span>
            </div>
            @endif

            <h1 class="font-headline text-6xl md:text-8xl font-black text-primary italic leading-[0.9] tracking-tight">
                {!! $title !!}
            </h1>

            @if($subtitle)
            <p class="text-xl text-on-surface-variant max-w-xl leading-relaxed">
                {{ $subtitle }}
            </p>
            @endif

            @if($primaryCta || $secondaryCta)
            <div class="flex flex-wrap gap-4 pt-2">
                @if($primaryCta)
                <a
                    href="{{ $primaryCtaLink }}"
                    class="inline-flex items-center gap-2 px-7 py-3.5 bg-primary text-on-primary font-semibold tracking-wide uppercase text-sm rounded transition-opacity hover:opacity-90"
                >
                    {{ $primaryCta }}
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
                @endif
                @if($secondaryCta)
                <a
                    href="{{ $secondaryCtaLink }}"
                    class="inline-flex items-center gap-2 px-7 py-3.5 bg-surface-container text-on-surface font-semibold tracking-wide uppercase text-sm rounded transition-colors hover:bg-surface-container-high"
                >
                    {{ $secondaryCta }}
                </a>
                @endif
            </div>
            @endif

        </div>
    </div>

</section>
