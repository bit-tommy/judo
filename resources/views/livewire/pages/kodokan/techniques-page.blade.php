<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Techniky | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.kodokan'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Kodokan Judo', 'url' => route('kodokan.index')],
                ['label' => 'Techniky'],
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
            @if(request()->routeIs('kodokan.techniques'))
                <span class="absolute bottom-0 left-4 right-4 h-0.5 bg-primary rounded-full"></span>
            @endif
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
            技
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
                {{ $content['techniques']['title'] }}
            </h1>
            <p class="text-on-surface-variant leading-relaxed text-lg">
                Systematický přehled technik Kodokan Judo od hodů po techniky na zemi.
            </p>
        </div>
    </div>
</section>

{{-- Article Body --}}
<section class="bg-surface py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl mx-auto">

            <div class="font-body text-on-surface-variant leading-relaxed text-lg space-y-6 mb-16">
                <p>{{ $content['techniques']['text'] }}</p>
            </div>

            {{-- Technique Groups --}}
            <h2 class="font-headline text-2xl font-bold text-on-surface mb-8">Přehled technik</h2>

            <div class="space-y-6">
                {{-- Tachi-waza --}}
                <div class="bg-surface-container-low p-8 md:p-10 rounded-lg">
                    <h3 class="font-headline text-xl font-bold text-on-surface mb-1">Tachi-waza</h3>
                    <p class="text-xs font-bold uppercase tracking-widest text-primary mb-4">Techniky v postoji</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <p class="text-sm font-bold text-on-surface mb-2">Te-waza</p>
                            <p class="text-sm text-on-surface-variant leading-relaxed">Hody prováděné rukama</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface mb-2">Koshi-waza</p>
                            <p class="text-sm text-on-surface-variant leading-relaxed">Hody prováděné bokem</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface mb-2">Ashi-waza</p>
                            <p class="text-sm text-on-surface-variant leading-relaxed">Hody prováděné nohama</p>
                        </div>
                    </div>
                </div>

                {{-- Ne-waza --}}
                <div class="bg-surface-container-low p-8 md:p-10 rounded-lg">
                    <h3 class="font-headline text-xl font-bold text-on-surface mb-1">Ne-waza</h3>
                    <p class="text-xs font-bold uppercase tracking-widest text-primary mb-4">Techniky na zemi</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <p class="text-sm font-bold text-on-surface mb-2">Osaekomi-waza</p>
                            <p class="text-sm text-on-surface-variant leading-relaxed">Techniky znehybnění</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface mb-2">Kansetsu-waza</p>
                            <p class="text-sm text-on-surface-variant leading-relaxed">Páky na klouby</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface mb-2">Shime-waza</p>
                            <p class="text-sm text-on-surface-variant leading-relaxed">Techniky škrcení</p>
                        </div>
                    </div>
                </div>

                {{-- Go-kyo --}}
                <div class="bg-surface-container-low p-8 md:p-10 rounded-lg flex gap-6 items-start">
                    <span class="material-symbols-outlined text-3xl text-primary flex-shrink-0 mt-0.5">menu_book</span>
                    <div>
                        <h3 class="font-headline text-xl font-bold text-on-surface mb-2">Go-kyo no waza</h3>
                        <p class="text-on-surface-variant text-sm leading-relaxed">
                            Základní systém 40 technik hodů organizovaných do pěti skupin (kyo), který tvoří základ výuky Kodokan Judo.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Image placeholder --}}
            <div class="rounded-xl overflow-hidden bg-surface-container-low aspect-video flex items-center justify-center my-12">
                <div class="text-center">
                    <span class="material-symbols-outlined text-6xl text-primary opacity-30">sports_martial_arts</span>
                    <p class="text-xs uppercase tracking-widest text-on-surface-variant mt-3 font-bold opacity-50">Demonstrace technik</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Navigation to Siblings --}}
<section class="bg-surface-container-low py-16">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('kodokan.kata') }}"
               class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Kata
            </a>
            <a href="{{ route('kodokan.japanese-masters') }}"
               class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-primary hover:gap-3 transition-all">
                Japonští mistři
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
</div>
