<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Kodokan Judo | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.kodokan'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Kodokan Judo'],
            ],
        ];
    }
}; ?>

<div>
{{-- Hero Section --}}
<section class="bg-surface-container-low py-20 md:py-32 overflow-hidden relative">
    <div class="absolute inset-0 pointer-events-none select-none flex items-center justify-end pr-8 md:pr-16" aria-hidden="true">
        <span class="font-headline text-[12rem] md:text-[18rem] font-black text-primary opacity-[0.04] leading-none">
            柔道
        </span>
    </div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <div class="max-w-3xl">
            <span class="inline-block px-3 py-1 bg-primary text-on-primary text-xs font-bold uppercase tracking-widest rounded mb-6">
                Bojové umění
            </span>
            <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-6">
                {{ $content['title'] }}
            </h1>
            <p class="text-on-surface-variant leading-relaxed text-lg md:text-xl max-w-2xl">
                {{ $content['intro'] }}
            </p>
        </div>
    </div>
</section>

{{-- Navigation Cards --}}
<section class="bg-surface py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="mb-12 md:mb-16">
            <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-4">
                Prozkoumejte Kodokan
            </h2>
            <p class="text-on-surface-variant leading-relaxed max-w-xl">
                Ponořte se do světa tradičního Kodokan Judo — od jeho bohaté historie po techniky, kata a naše zahraniční zkušenosti.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">

            {{-- Historie --}}
            <a href="{{ route('kodokan.history') }}"
               class="group bg-surface-container-lowest p-10 rounded flex flex-col gap-5 hover:-translate-y-2 transition-all duration-300">
                <span class="material-symbols-outlined text-4xl text-primary">history_edu</span>
                <h3 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    {{ $content['history']['title'] }}
                </h3>
                <p class="text-on-surface-variant leading-relaxed flex-1 text-sm">
                    Od Jigoró Kanó a vzniku Kodokanu v roce 1882 po olympijský sport, který dnes provozují miliony lidí po celém světě.
                </p>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                    Číst více
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </span>
            </a>

            {{-- Kata --}}
            <a href="{{ route('kodokan.kata') }}"
               class="group bg-surface-container-lowest p-10 rounded flex flex-col gap-5 hover:-translate-y-2 transition-all duration-300">
                <span class="material-symbols-outlined text-4xl text-primary">self_improvement</span>
                <h3 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    {{ $content['kata']['title'] }}
                </h3>
                <p class="text-on-surface-variant leading-relaxed flex-1 text-sm">
                    Přesně definované formy technik prováděné s partnerem — cesta k pochopení hlubokých principů juda.
                </p>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                    Číst více
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </span>
            </a>

            {{-- Techniky --}}
            <a href="{{ route('kodokan.techniques') }}"
               class="group bg-surface-container-lowest p-10 rounded flex flex-col gap-5 hover:-translate-y-2 transition-all duration-300">
                <span class="material-symbols-outlined text-4xl text-primary">sports_martial_arts</span>
                <h3 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    {{ $content['techniques']['title'] }}
                </h3>
                <p class="text-on-surface-variant leading-relaxed flex-1 text-sm">
                    Kompletní přehled technik Kodokan Judo — hody, znehybnění, páky a škrcení v systematickém výkladu.
                </p>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                    Číst více
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </span>
            </a>

            {{-- Japonští mistři --}}
            <a href="{{ route('kodokan.japanese-masters') }}"
               class="group bg-surface-container-lowest p-10 rounded flex flex-col gap-5 hover:-translate-y-2 transition-all duration-300">
                <span class="material-symbols-outlined text-4xl text-primary">diversity_3</span>
                <h3 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    {{ $content['japanese_masters']['title'] }}
                </h3>
                <p class="text-on-surface-variant leading-relaxed flex-1 text-sm">
                    Výjimečné semináře s japonskými mistry přímo v našem dojo — přímé předávání tradice z Japonska do Prahy.
                </p>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                    Číst více
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </span>
            </a>

            {{-- Japonsko --}}
            <a href="{{ route('kodokan.japan-trips') }}"
               class="group bg-surface-container-lowest p-10 rounded flex flex-col gap-5 hover:-translate-y-2 transition-all duration-300">
                <span class="material-symbols-outlined text-4xl text-primary">flight</span>
                <h3 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    {{ $content['japan_trips']['title'] }}
                </h3>
                <p class="text-on-surface-variant leading-relaxed flex-1 text-sm">
                    Studijní pobyty přímo v Kodokanu a tradičních tokijských dojo v letech 2016 a 2019.
                </p>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                    Číst více
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </span>
            </a>

        </div>
    </div>
</section>

{{-- Intro / Philosophy Strip --}}
<section class="bg-surface-container-low py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <span class="font-headline text-7xl md:text-9xl font-black text-primary opacity-10 leading-none select-none" aria-hidden="true">柔道</span>
            <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mt-4 mb-6">
                Filozofie Kodokan
            </h2>
            <p class="text-on-surface-variant leading-relaxed text-lg mb-8">
                Dvě základní zásady formulované zakladatelem Jigoró Kanó tvoří základ veškeré výuky: <strong class="text-on-surface font-semibold">Seiryoku Zenyo</strong> — maximální efektivita při minimálním úsilí, a <strong class="text-on-surface font-semibold">Jita Kyoei</strong> — vzájemná pomoc a prospěch.
            </p>
            <div class="flex flex-wrap justify-center gap-8 mt-10">
                <div class="text-center">
                    <div class="font-headline text-3xl font-black text-primary">1882</div>
                    <div class="text-xs uppercase tracking-widest font-bold text-on-surface-variant mt-1">Vznik Kodokanu</div>
                </div>
                <div class="text-center">
                    <div class="font-headline text-3xl font-black text-primary">1964</div>
                    <div class="text-xs uppercase tracking-widest font-bold text-on-surface-variant mt-1">Olympijský sport</div>
                </div>
                <div class="text-center">
                    <div class="font-headline text-3xl font-black text-primary">200+</div>
                    <div class="text-xs uppercase tracking-widest font-bold text-on-surface-variant mt-1">Zemí světa</div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
