@props([
    'title'       => '',
    'description' => '',
    'icon'        => 'info',
    'link'        => '#',
])

<div class="group bg-surface-container-lowest p-10 rounded flex flex-col gap-5 hover:-translate-y-2 transition-all duration-300">

    <span class="material-symbols-outlined text-4xl text-primary">{{ $icon }}</span>

    <h3 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight">
        {{ $title }}
    </h3>

    <p class="text-on-surface-variant leading-relaxed flex-1">
        {{ $description }}
    </p>

    <a
        href="{{ $link }}"
        class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-auto hover:gap-3 transition-all"
    >
        Více informací
        <span class="material-symbols-outlined text-base">arrow_forward</span>
    </a>

</div>
