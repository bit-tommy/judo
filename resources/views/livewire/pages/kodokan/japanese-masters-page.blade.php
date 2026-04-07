<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Pobyt japonských mistrů u nás | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.kodokan'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Kodokan Judo', 'url' => route('kodokan.index')],
                ['label' => 'Japonští mistři'],
            ],
        ];
    }
}; ?>

<div>
{{-- Section Nav --}}
<nav class="bg-surface-container-low py-4 overflow-x-auto sticky top-20 z-30">
    <div class="container mx-auto px-6 md:px-8 flex gap-1 min-w-max">
        <a href="{{ route('kodokan.index') }}"
           class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            Kodokan Judo
        </a>
        <a href="{{ route('kodokan.history') }}"
           class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors
           {{ request()->routeIs('kodokan.history') ? 'bg-surface-container text-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container' }}">
            Historie
        </a>
        <a href="{{ route('kodokan.kata') }}"
           class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors
           {{ request()->routeIs('kodokan.kata') ? 'bg-surface-container text-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container' }}">
            Kata
        </a>
        <a href="{{ route('kodokan.techniques') }}"
           class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors
           {{ request()->routeIs('kodokan.techniques') ? 'bg-surface-container text-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container' }}">
            Techniky
        </a>
        <a href="{{ route('kodokan.japanese-masters') }}"
           class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors
           {{ request()->routeIs('kodokan.japanese-masters') ? 'bg-surface-container text-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container' }}">
            Japonští mistři
            @if(request()->routeIs('kodokan.japanese-masters'))
                <span class="absolute bottom-0 left-4 right-4 h-0.5 bg-primary rounded-full"></span>
            @endif
        </a>
        <a href="{{ route('kodokan.japan-trips') }}"
           class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors
           {{ request()->routeIs('kodokan.japan-trips') ? 'bg-surface-container text-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container' }}">
            Japonsko 2016/2019
        </a>
    </div>
</nav>

{{-- Article Header --}}
<section class="bg-surface-container-low py-20 md:py-32 overflow-hidden relative">
    <div class="absolute inset-0 pointer-events-none select-none flex items-center justify-end pr-8 md:pr-16" aria-hidden="true">
        <span class="font-headline text-[12rem] md:text-[18rem] font-black text-primary opacity-[0.04] leading-none">
            師
        </span>
    </div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <div class="max-w-3xl">
            <a href="{{ route('kodokan.index') }}"
               class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-primary mb-6 hover:gap-3 transition-all">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Kodokan Judo
            </a>
            <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-6">
                {{ $content['japanese_masters']['title'] }}
            </h1>
            <p class="text-on-surface-variant leading-relaxed text-lg">
                Přímé předávání tradičního Kodokan Judo z Japonska přímo do našeho dojo.
            </p>
        </div>
    </div>
</section>

{{-- Article Body --}}
<section class="bg-surface py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl mx-auto">

            <div class="font-body text-on-surface-variant leading-relaxed text-lg space-y-6 mb-16">
                <p>{{ $content['japanese_masters']['text'] }}</p>
            </div>

            {{-- News CTA --}}
            <div class="bg-surface-container-low p-8 md:p-10 rounded-lg flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                <span class="material-symbols-outlined text-4xl text-primary flex-shrink-0">notifications</span>
                <div class="flex-1">
                    <h3 class="font-headline text-xl font-bold text-on-surface mb-2">Nadcházející semináře</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">
                        Informace o plánovaných seminářích s japonskými mistry jsou průběžně zveřejňovány v sekci Aktuality.
                    </p>
                </div>
                <a href="{{ route('news.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-primary whitespace-nowrap hover:gap-3 transition-all flex-shrink-0">
                    Aktuality
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

        </div>
    </div>
</section>

{{-- Gallery Grid --}}
<section class="bg-surface-container-low py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <h2 class="font-headline text-3xl md:text-4xl font-extrabold tracking-tight text-on-surface mb-10">
            Galerie
        </h2>

        {{-- Gallery placeholder grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @for($i = 0; $i < 8; $i++)
                <div class="aspect-square rounded-lg bg-surface-container flex items-center justify-center overflow-hidden group">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-3xl text-primary opacity-20 group-hover:opacity-40 transition-opacity">photo_camera</span>
                    </div>
                </div>
            @endfor
        </div>

        <p class="text-center text-on-surface-variant text-sm mt-8">
            Fotografie budou doplněny po nejbližším semináři.
        </p>
    </div>
</section>

{{-- Navigation to Siblings --}}
<section class="bg-surface py-16">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('kodokan.techniques') }}"
               class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Techniky
            </a>
            <a href="{{ route('kodokan.japan-trips') }}"
               class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-primary hover:gap-3 transition-all">
                Japonsko 2016/2019
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
</div>
