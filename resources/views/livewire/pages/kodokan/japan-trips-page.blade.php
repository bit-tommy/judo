<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Japonsko 2016/2019 | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.kodokan'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Kodokan Judo', 'url' => route('kodokan.index')],
                ['label' => 'Japonsko 2016/2019'],
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
        </a>
        <a href="{{ route('kodokan.japan-trips') }}"
           class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors
           {{ request()->routeIs('kodokan.japan-trips') ? 'bg-surface-container text-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container' }}">
            Japonsko 2016/2019
            @if(request()->routeIs('kodokan.japan-trips'))
                <span class="absolute bottom-0 left-4 right-4 h-0.5 bg-primary rounded-full"></span>
            @endif
        </a>
    </div>
</nav>

{{-- Article Header --}}
<section class="bg-surface-container-low py-20 md:py-32 overflow-hidden relative">
    <div class="absolute inset-0 pointer-events-none select-none flex items-center justify-end pr-8 md:pr-16" aria-hidden="true">
        <span class="font-headline text-[12rem] md:text-[18rem] font-black text-primary opacity-[0.04] leading-none">
            日本
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
                {{ $content['japan_trips']['title'] }}
            </h1>
            <p class="text-on-surface-variant leading-relaxed text-lg">
                Studijní pobyty přímo u zdroje — tréninky v Kodokanu a tradičních tokijských dojo.
            </p>
        </div>
    </div>
</section>

{{-- Article Body --}}
<section class="bg-surface py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl mx-auto">

            {{-- Highlighted stat strip --}}
            <div class="flex flex-wrap gap-8 mb-12 pb-12">
                <div class="text-center">
                    <div class="font-headline text-4xl font-black text-primary">2016</div>
                    <div class="text-xs uppercase tracking-widest font-bold text-on-surface-variant mt-1">První pobyt</div>
                </div>
                <div class="text-center">
                    <div class="font-headline text-4xl font-black text-primary">2019</div>
                    <div class="text-xs uppercase tracking-widest font-bold text-on-surface-variant mt-1">Druhý pobyt</div>
                </div>
                <div class="text-center">
                    <div class="font-headline text-4xl font-black text-primary">Tokio</div>
                    <div class="text-xs uppercase tracking-widest font-bold text-on-surface-variant mt-1">Kodokan</div>
                </div>
            </div>

            <div class="font-body text-on-surface-variant leading-relaxed text-lg space-y-6 mb-12">
                <p>{{ $content['japan_trips']['text'] }}</p>
            </div>

            {{-- Image placeholder featured --}}
            <div class="rounded-xl overflow-hidden bg-surface-container-low aspect-[16/9] flex items-center justify-center mb-8">
                <div class="text-center">
                    <span class="material-symbols-outlined text-6xl text-primary opacity-30">flight</span>
                    <p class="text-xs uppercase tracking-widest text-on-surface-variant mt-3 font-bold opacity-50">Kodokan Tokio</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-12">
                <div class="rounded-xl overflow-hidden bg-surface-container-low aspect-square flex items-center justify-center">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-4xl text-primary opacity-20">photo_camera</span>
                        <p class="text-xs text-on-surface-variant mt-2 opacity-50">2016</p>
                    </div>
                </div>
                <div class="rounded-xl overflow-hidden bg-surface-container-low aspect-square flex items-center justify-center">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-4xl text-primary opacity-20">photo_camera</span>
                        <p class="text-xs text-on-surface-variant mt-2 opacity-50">2019</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Gallery Grid --}}
<section class="bg-surface-container-low py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <h2 class="font-headline text-3xl md:text-4xl font-extrabold tracking-tight text-on-surface mb-10">
            Fotogalerie
        </h2>

        {{-- Gallery placeholder grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @for($i = 0; $i < 12; $i++)
                <div class="aspect-square rounded-lg bg-surface-container flex items-center justify-center overflow-hidden group">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-3xl text-primary opacity-20 group-hover:opacity-40 transition-opacity">photo_camera</span>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>

{{-- Navigation to Siblings --}}
<section class="bg-surface py-16">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('kodokan.japanese-masters') }}"
               class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Japonští mistři
            </a>
            <a href="{{ route('kodokan.index') }}"
               class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-primary hover:gap-3 transition-all">
                Zpět na Kodokan Judo
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
</div>
