<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Pokročilí | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.training'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Tréninky a ceník', 'url' => route('training.index')],
                ['label' => 'Pokročilí'],
            ],
        ];
    }
}; ?>

<div>
{{-- Page Header --}}
<section class="relative bg-gradient-to-br from-surface via-surface-container-low to-surface-container overflow-hidden">
    <div class="absolute inset-0 flex items-center justify-end pr-8 md:pr-16 pointer-events-none select-none" aria-hidden="true">
        <span class="font-headline text-[12rem] md:text-[18rem] font-black text-primary opacity-[0.04] leading-none"></span>
    </div>
    <div class="container mx-auto px-6 md:px-8 py-16 md:py-24 relative z-10">
        <div class="max-w-2xl">
            <span class="inline-block px-3 py-1 bg-primary text-on-primary text-xs font-bold uppercase tracking-widest rounded mb-6">
                Tréninky
            </span>
            <h1 class="font-headline text-5xl md:text-7xl font-black text-primary italic leading-[0.9] tracking-tight mb-4">
                Pokročilí
            </h1>
            <p class="text-on-surface-variant text-lg leading-relaxed">
                {{ $content['advanced']['name'] }} — {{ $content['advanced']['age'] }}
            </p>
        </div>
    </div>
</section>

{{-- Section Nav --}}
@include('livewire.pages.training.partials.section-nav')

{{-- Program Detail --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">

            {{-- Main Content --}}
            <div class="lg:col-span-2">
                <p class="text-xs font-bold tracking-[0.3em] uppercase text-primary mb-4">{{ $content['advanced']['name'] }}</p>
                <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-on-surface tracking-tight mb-6">
                    O programu
                </h2>
                <p class="text-on-surface-variant text-lg leading-relaxed mb-8">
                    {{ $content['advanced']['description'] }}
                </p>

                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">group</span>
                        <div>
                            <span class="font-bold text-on-surface text-sm">Věková skupina</span>
                            <p class="text-on-surface-variant text-sm">{{ $content['advanced']['age'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">emoji_events</span>
                        <div>
                            <span class="font-bold text-on-surface text-sm">Zaměření</span>
                            <p class="text-on-surface-variant text-sm">Technika, taktika a příprava na soutěže</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">location_on</span>
                        <div>
                            <span class="font-bold text-on-surface text-sm">Místo</span>
                            <p class="text-on-surface-variant text-sm">{{ $content['main_dojo'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach(['Zdokonalování techniky hodů', 'Nácvik katame-waza (práce na zemi)', 'Taktická příprava na soutěže', 'Rozvoj fyzické kondice', 'Randori — volný zápas', 'Účast na turnajích'] as $feature)
                    <div class="flex items-center gap-3 bg-surface-container-low rounded-lg p-4">
                        <span class="material-symbols-outlined text-primary text-lg shrink-0">check_circle</span>
                        <span class="text-sm text-on-surface-variant">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Schedule Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-surface-container-lowest rounded-lg p-6 md:p-8 sticky top-28">
                    <h3 class="font-headline font-bold text-on-surface text-xl mb-6 tracking-tight">
                        Rozvrh
                    </h3>

                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl shrink-0">calendar_today</span>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">Dny</p>
                                <p class="font-bold text-on-surface">{{ $content['advanced']['days'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl shrink-0">schedule</span>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">Čas</p>
                                <p class="font-bold text-on-surface">{{ $content['advanced']['time'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl shrink-0">check_circle</span>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">Stav</p>
                                <p class="font-bold text-on-surface">Aktivní</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a
                            href="{{ route('contact') }}"
                            class="flex items-center justify-center gap-2 px-6 py-3.5 bg-primary text-on-primary font-bold uppercase tracking-wide text-sm rounded hover:opacity-90 transition-opacity w-full"
                        >
                            Zkušební trénink
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- Navigation to other programs --}}
<section class="py-20 md:py-32 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <h2 class="font-headline text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-8">
            Další programy
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('training.preparatory') }}" class="bg-surface-container-lowest rounded-lg p-6 flex items-center gap-4 hover:translate-y-[-4px] transition-all duration-300 group">
                <span class="material-symbols-outlined text-primary text-3xl">child_care</span>
                <div>
                    <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Přípravka</p>
                    <p class="text-xs text-on-surface-variant">Děti od 5 let</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-base ml-auto group-hover:text-primary transition-colors">arrow_forward</span>
            </a>
            <a href="{{ route('training.adults') }}" class="bg-surface-container-lowest rounded-lg p-6 flex items-center gap-4 hover:translate-y-[-4px] transition-all duration-300 group">
                <span class="material-symbols-outlined text-primary text-3xl">person</span>
                <div>
                    <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Dospělí</p>
                    <p class="text-xs text-on-surface-variant">Judo pro dospělé</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-base ml-auto group-hover:text-primary transition-colors">arrow_forward</span>
            </a>
            <a href="{{ route('training.pricing') }}" class="bg-surface-container-lowest rounded-lg p-6 flex items-center gap-4 hover:translate-y-[-4px] transition-all duration-300 group">
                <span class="material-symbols-outlined text-primary text-3xl">payments</span>
                <div>
                    <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Ceník</p>
                    <p class="text-xs text-on-surface-variant">Informace o platbách</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-base ml-auto group-hover:text-primary transition-colors">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
</div>
