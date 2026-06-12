<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Kondiční cvičení - RANDORI | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.training'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Tréninky a ceník', 'url' => route('training.index')],
                ['label' => 'RANDORI'],
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
            <h1 class="font-headline text-4xl md:text-6xl font-black text-primary italic leading-[0.9] tracking-tight mb-4">
                Kondiční cvičení — RANDORI
            </h1>
        </div>
    </div>
</section>

{{-- Section Nav --}}
@include('livewire.pages.training.partials.section-nav')

{{-- PROMINENT Paused Banner --}}
<div class="bg-error py-6">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-center sm:text-left">
            <span class="material-symbols-outlined text-on-error text-4xl shrink-0">warning</span>
            <div>
                <p class="font-headline font-black text-on-error text-2xl md:text-3xl tracking-tight uppercase">
                    {{ $content['randori']['status'] }}
                </p>
                <p class="text-on-error opacity-80 text-sm mt-1">
                    Sledujte naše aktuality pro informace o obnovení tréninků.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Program Content --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">

        {{-- Paused Notice Card --}}
        <div class="bg-error/10 rounded-lg p-6 md:p-8 mb-12 flex items-start gap-4 max-w-3xl">
            <span class="material-symbols-outlined text-error text-2xl shrink-0 mt-0.5">pause_circle</span>
            <div>
                <h2 class="font-headline font-black text-error text-xl mb-2 uppercase tracking-tight">
                    {{ $content['randori']['status'] }}
                </h2>
                <p class="text-on-surface-variant leading-relaxed">
                    {{ $content['randori']['description'] }}
                </p>
            </div>
        </div>

        <div class="max-w-3xl">
            <p class="text-xs font-bold tracking-[0.3em] uppercase text-on-surface-variant mb-4">Program</p>
            <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-on-surface tracking-tight mb-6">
                O programu Kondiční cvičení — Randori
            </h2>
            <p class="text-on-surface-variant text-lg leading-relaxed mb-8">
                Kondiční cvičení zaměřené na randori (volný zápas) je intenzivní trénink určený pro judisty, kteří
                chtějí rozvíjet svou kondici a techniku v zápasovém prostředí.
                Tréninky jsou v současné době dočasně pozastaveny.
            </p>

            <div class="flex flex-col gap-4">
                <div class="flex items-start gap-3 opacity-50">
                    <span class="material-symbols-outlined text-on-surface-variant text-xl shrink-0 mt-0.5">calendar_today</span>
                    <div>
                        <span class="font-bold text-on-surface text-sm">Rozvrh</span>
                        <p class="text-on-surface-variant text-sm">Tréninky dočasně pozastaveny</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 opacity-50">
                    <span class="material-symbols-outlined text-on-surface-variant text-xl shrink-0 mt-0.5">location_on</span>
                    <div>
                        <span class="font-bold text-on-surface text-sm">Místo</span>
                        <p class="text-on-surface-variant text-sm">{{ $content['main_dojo'] }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-error text-xl shrink-0 mt-0.5">info</span>
                    <div>
                        <span class="font-bold text-error text-sm uppercase tracking-wide">Aktuální stav</span>
                        <p class="text-error font-bold text-sm">{{ $content['randori']['status'] }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- Stay Informed / Contact CTA --}}
<section class="py-20 md:py-32 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <span class="material-symbols-outlined text-primary text-5xl mb-6 block">notifications</span>
            <h2 class="font-headline text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-4">
                Chcete být informováni o obnovení?
            </h2>
            <p class="text-on-surface-variant mb-8 leading-relaxed">
                Kontaktujte nás a budeme vás informovat, jakmile kondiční tréninky Randori budou obnoveny.
                Sledujte také naše aktuality.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a
                    href="{{ route('contact') }}"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-primary-container text-on-primary font-bold uppercase tracking-widest text-sm rounded hover:opacity-90 transition-opacity"
                >
                    Kontaktovat nás
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
                <a
                    href="{{ route('news.index') }}"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-surface-container text-on-surface font-bold uppercase tracking-widest text-sm rounded hover:bg-surface-container-high transition-colors"
                >
                    Aktuality
                    <span class="material-symbols-outlined text-base">campaign</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Navigation to other programs --}}
<section class="py-16 md:py-20 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <h2 class="font-headline text-xl font-bold text-on-surface tracking-tight mb-6">
            Aktivní programy
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('training.preparatory') }}" class="bg-surface-container-lowest rounded-lg p-5 flex items-center gap-3 hover:translate-y-[-4px] transition-all duration-300 group">
                <span class="material-symbols-outlined text-primary text-2xl shrink-0">child_care</span>
                <div>
                    <p class="font-bold text-sm text-on-surface group-hover:text-primary transition-colors">Přípravka</p>
                    <p class="text-xs text-on-surface-variant">Děti od 5 let</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-sm ml-auto group-hover:text-primary transition-colors">arrow_forward</span>
            </a>
            <a href="{{ route('training.advanced') }}" class="bg-surface-container-lowest rounded-lg p-5 flex items-center gap-3 hover:translate-y-[-4px] transition-all duration-300 group">
                <span class="material-symbols-outlined text-primary text-2xl shrink-0">sports_martial_arts</span>
                <div>
                    <p class="font-bold text-sm text-on-surface group-hover:text-primary transition-colors">Pokročilí</p>
                    <p class="text-xs text-on-surface-variant">Děti a mládež</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-sm ml-auto group-hover:text-primary transition-colors">arrow_forward</span>
            </a>
            <a href="{{ route('training.adults') }}" class="bg-surface-container-lowest rounded-lg p-5 flex items-center gap-3 hover:translate-y-[-4px] transition-all duration-300 group">
                <span class="material-symbols-outlined text-primary text-2xl shrink-0">person</span>
                <div>
                    <p class="font-bold text-sm text-on-surface group-hover:text-primary transition-colors">Dospělí</p>
                    <p class="text-xs text-on-surface-variant">Pro všechny úrovně</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-sm ml-auto group-hover:text-primary transition-colors">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
</div>
