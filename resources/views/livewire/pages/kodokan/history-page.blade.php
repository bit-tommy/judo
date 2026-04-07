<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Historie Judo | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.kodokan'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Kodokan Judo', 'url' => route('kodokan.index')],
                ['label' => 'Historie'],
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
            @if(request()->routeIs('kodokan.history'))
                <span class="absolute bottom-0 left-4 right-4 h-0.5 bg-primary rounded-full"></span>
            @endif
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
        </a>
    </div>
</nav>

{{-- Article Header --}}
<section class="bg-surface-container-low py-20 md:py-32 overflow-hidden relative">
    <div class="absolute inset-0 pointer-events-none select-none flex items-center justify-end pr-8 md:pr-16" aria-hidden="true">
        <span class="font-headline text-[12rem] md:text-[18rem] font-black text-primary opacity-[0.04] leading-none">
            歴史
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
                {{ $content['history']['title'] }}
            </h1>
            <p class="text-on-surface-variant leading-relaxed text-lg">
                Od zakladatele Jigoró Kanó po olympijský sport, který dnes patří k nejrozšířenějším bojovým uměním světa.
            </p>
        </div>
    </div>
</section>

{{-- Article Body --}}
<section class="bg-surface py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl mx-auto">

            {{-- Image placeholder --}}
            <div class="rounded-xl overflow-hidden bg-surface-container-low aspect-video flex items-center justify-center mb-12">
                <div class="text-center">
                    <span class="material-symbols-outlined text-6xl text-primary opacity-30">history_edu</span>
                    <p class="text-xs uppercase tracking-widest text-on-surface-variant mt-3 font-bold opacity-50">Historická fotografie</p>
                </div>
            </div>

            <div class="font-body text-on-surface-variant leading-relaxed text-lg space-y-6">
                <p>{{ $content['history']['text'] }}</p>
            </div>

            {{-- Key Dates --}}
            <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-surface-container-low p-8 rounded-lg">
                    <div class="font-headline text-4xl font-black text-primary mb-2">1860</div>
                    <p class="text-sm font-bold text-on-surface mb-1">Jigoró Kanó</p>
                    <p class="text-sm text-on-surface-variant">Narozen 28. října 1860 v Japonsku, zakladatel Kodokan Judo.</p>
                </div>
                <div class="bg-surface-container-low p-8 rounded-lg">
                    <div class="font-headline text-4xl font-black text-primary mb-2">1882</div>
                    <p class="text-sm font-bold text-on-surface mb-1">Vznik Kodokanu</p>
                    <p class="text-sm text-on-surface-variant">Kanó otevřel první dojo Kodokan v tokijském chrámu Eishoji.</p>
                </div>
                <div class="bg-surface-container-low p-8 rounded-lg">
                    <div class="font-headline text-4xl font-black text-primary mb-2">1964</div>
                    <p class="text-sm font-bold text-on-surface mb-1">Olympiáda v Tokiu</p>
                    <p class="text-sm text-on-surface-variant">Judo bylo poprvé zařazeno na Olympijské hry jako soutěžní sport pro muže.</p>
                </div>
                <div class="bg-surface-container-low p-8 rounded-lg">
                    <div class="font-headline text-4xl font-black text-primary mb-2">1992</div>
                    <p class="text-sm font-bold text-on-surface mb-1">Barcelona</p>
                    <p class="text-sm text-on-surface-variant">Na hrách v Barceloně bylo judo zařazeno i pro ženy.</p>
                </div>
            </div>

            {{-- Second image placeholder --}}
            <div class="rounded-xl overflow-hidden bg-surface-container-low aspect-video flex items-center justify-center my-12">
                <div class="text-center">
                    <span class="material-symbols-outlined text-6xl text-primary opacity-30">sports_martial_arts</span>
                    <p class="text-xs uppercase tracking-widest text-on-surface-variant mt-3 font-bold opacity-50">Trénink v Kodokanu</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Navigation to Siblings --}}
<section class="bg-surface-container-low py-16">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('kodokan.index') }}"
               class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Kodokan Judo
            </a>
            <a href="{{ route('kodokan.kata') }}"
               class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-primary hover:gap-3 transition-all">
                Kata
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
</div>
