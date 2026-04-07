<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('O klubu | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.club'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'O klubu'],
            ],
        ];
    }
}; ?>

<div>
{{-- Sub-navigation --}}
<nav class="bg-surface-container-low py-4 overflow-x-auto sticky top-20 z-30">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex gap-1 min-w-max">
            <a href="#klub"
               class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
                Klub
            </a>
            <a href="#instruktori"
               class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
                Instruktoři
            </a>
            <a href="#vnitrni-rad"
               class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
                Vnitřní řád
            </a>
            <a href="#vodochody"
               class="relative px-4 py-2.5 text-sm font-semibold whitespace-nowrap rounded transition-colors text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
                Vodochody
            </a>
        </div>
    </div>
</nav>

{{-- Hero Intro --}}
<section class="bg-surface-container-low py-20 md:py-32 overflow-hidden relative">
    <div class="absolute inset-0 pointer-events-none select-none flex items-center justify-end pr-8 md:pr-16" aria-hidden="true">
        <span class="font-headline text-[12rem] md:text-[18rem] font-black text-primary opacity-[0.04] leading-none">
            柔道
        </span>
    </div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <div class="max-w-3xl">
            <span class="inline-block px-3 py-1 bg-primary text-on-primary text-xs font-bold uppercase tracking-widest rounded mb-6">
                {{ $content['club_name'] }}
            </span>
            <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-6">
                {{ $content['title'] }}
            </h1>
            <p class="text-on-surface-variant leading-relaxed text-lg md:text-xl max-w-2xl">
                Vše o historii, struktuře a hodnotách klubu Judo club Raion-ryu — od profilu instruktorů po vnitřní řád a naši pobočku v Praze a ve Vodchodech.
            </p>
        </div>
    </div>
</section>

{{-- Section: Judo club Raion-ryu --}}
<section id="klub" class="bg-surface py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-center">
            <div class="w-full lg:w-1/2 order-2 lg:order-1">
                <p class="text-xs font-bold tracking-[0.3em] uppercase text-primary mb-4">O nás</p>
                <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-6">
                    {{ $content['club_name'] }}
                </h2>
                <p class="text-on-surface-variant leading-relaxed text-lg">
                    {{ $content['club_text'] }}
                </p>
                <div class="mt-10 flex flex-wrap gap-8">
                    <div>
                        <div class="font-headline text-4xl font-black text-primary">2007</div>
                        <div class="text-xs uppercase tracking-widest font-bold text-on-surface-variant mt-1">Rok vzniku</div>
                    </div>
                    <div>
                        <div class="font-headline text-4xl font-black text-primary">ČSJu</div>
                        <div class="text-xs uppercase tracking-widest font-bold text-on-surface-variant mt-1">Člen svazu</div>
                    </div>
                    <div>
                        <div class="font-headline text-4xl font-black text-primary">柔道</div>
                        <div class="text-xs uppercase tracking-widest font-bold text-on-surface-variant mt-1">Kodokan Judo</div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/2 order-1 lg:order-2">
                {{-- Image placeholder --}}
                <div class="relative rounded-xl overflow-hidden bg-surface-container-low aspect-[4/3] flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-primary/10"></div>
                    <div class="relative text-center">
                        <span class="material-symbols-outlined text-6xl text-primary opacity-30">sports_martial_arts</span>
                        <p class="text-xs uppercase tracking-widest text-on-surface-variant mt-3 font-bold opacity-50">Fotografie klubu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Section: Instruktoři --}}
<section id="instruktori" class="bg-surface-container-low py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="mb-12 md:mb-16">
            <p class="text-xs font-bold tracking-[0.3em] uppercase text-primary mb-4">Náš tým</p>
            <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-4">
                Instruktoři
            </h2>
            <p class="text-on-surface-variant leading-relaxed max-w-xl">
                Naši instruktoři jsou certifikovaní odborníci s hlubokým znalostmi tradičního Kodokan Judo.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($content['instructors'] as $instructor)
                <div class="bg-surface p-8 md:p-10 rounded-lg flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-2xl text-primary">person</span>
                        </div>
                        <div>
                            <h3 class="font-headline text-xl font-bold text-on-surface leading-tight">
                                {{ $instructor['name'] }}
                            </h3>
                            <p class="text-sm text-primary font-semibold mt-0.5">
                                {{ $instructor['role'] }}
                            </p>
                        </div>
                    </div>
                    @if(!empty($instructor['credentials']))
                        <ul class="flex flex-col gap-2">
                            @foreach($instructor['credentials'] as $credential)
                                <li class="flex items-start gap-3 text-sm text-on-surface-variant leading-relaxed">
                                    <span class="material-symbols-outlined text-base text-primary mt-0.5 flex-shrink-0">check_circle</span>
                                    {{ $credential }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Section: Vnitřní řád --}}
<section id="vnitrni-rad" class="bg-surface py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center gap-4 mb-6">
                <span class="material-symbols-outlined text-3xl text-primary">gavel</span>
                <p class="text-xs font-bold tracking-[0.3em] uppercase text-primary">Pravidla</p>
            </div>
            <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-8">
                {{ $content['vnitrni_rad_title'] }}
            </h2>
            <p class="text-on-surface-variant leading-relaxed text-lg mb-10">
                {{ $content['vnitrni_rad_text'] }}
            </p>
            <a href="{{ route('downloads') }}"
               class="inline-flex items-center gap-3 bg-primary text-on-primary px-8 py-4 rounded font-bold uppercase tracking-widest text-sm hover:bg-primary-container transition-colors">
                <span class="material-symbols-outlined text-base">download</span>
                Stáhnout vnitřní řád
            </a>
        </div>
    </div>
</section>

{{-- Section: Vodochody --}}
<section id="vodochody" class="bg-surface-container-low py-20 md:py-32">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-center">
            <div class="w-full lg:w-1/2">
                <p class="text-xs font-bold tracking-[0.3em] uppercase text-primary mb-4">Pobočka</p>
                <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-6">
                    {{ $content['vodochody']['title'] }}
                </h2>
                <p class="text-on-surface-variant leading-relaxed text-lg mb-8">
                    {{ $content['vodochody']['text'] }}
                </p>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 text-primary font-bold uppercase tracking-widest text-xs hover:gap-4 transition-all">
                    Kontaktujte nás
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
            <div class="w-full lg:w-1/2">
                {{-- Map / image placeholder --}}
                <div class="relative rounded-xl overflow-hidden bg-surface-container aspect-[4/3] flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-br from-tertiary/5 to-tertiary/10"></div>
                    <div class="relative text-center">
                        <span class="material-symbols-outlined text-6xl text-primary opacity-30">location_on</span>
                        <p class="text-xs uppercase tracking-widest text-on-surface-variant mt-3 font-bold opacity-50">Vodochody</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
