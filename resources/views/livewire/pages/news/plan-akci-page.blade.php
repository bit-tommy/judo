<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Plán AKCÍ | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.news'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Aktuality', 'url' => route('news.index')],
                ['label' => 'Plán AKCÍ'],
            ],
        ];
    }
}; ?>

<div>
{{-- Page header --}}
<section class="bg-surface-container-low py-20 md:py-32 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none select-none flex items-center justify-end pr-8 md:pr-16" aria-hidden="true">
        <span class="font-headline text-[10rem] md:text-[16rem] font-black text-primary opacity-[0.04] leading-none"></span>
    </div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">

        {{-- Back link --}}
        <a href="{{ route('news.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors mb-8">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Aktuality
        </a>

        <div class="flex items-start gap-4 mb-4">
            <span class="material-symbols-outlined text-4xl text-primary mt-1">event_note</span>
            <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface">
                Plán AKCÍ
            </h1>
        </div>
        <p class="text-on-surface-variant text-lg max-w-xl">
            Plán akcí bude průběžně aktualizován.
        </p>
    </div>
</section>

{{-- Events timeline --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">

        {{-- Note --}}
        <div class="flex items-start gap-3 bg-surface-container-lowest p-6 rounded-lg mb-12 max-w-2xl">
            <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">info</span>
            <p class="text-on-surface-variant text-sm leading-relaxed">
                Plán akcí bude průběžně aktualizován. Sledujte tuto stránku pro nejnovější informace o plánovaných soustředěních, soutěžích a seminářích.
            </p>
        </div>

        {{-- Year: current year placeholder --}}
        <div class="max-w-3xl">

            <div class="flex items-center gap-4 mb-8">
                <h2 class="font-headline text-3xl font-black text-primary tracking-tight">2026</h2>
                <div class="flex-1 h-px bg-surface-container-high"></div>
                <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest">Připravujeme</span>
            </div>

            <ul class="flex flex-col gap-2 mb-16">
                <li class="flex items-center gap-4 px-4 py-4 bg-surface-container-lowest rounded-lg">
                    <span class="material-symbols-outlined text-primary text-xl shrink-0">event</span>
                    <span class="text-on-surface-variant text-sm italic">
                        Akce budou průběžně doplňovány.
                    </span>
                </li>
            </ul>

            <div class="flex items-center gap-4 mb-8">
                <h2 class="font-headline text-3xl font-black text-primary tracking-tight">2025</h2>
                <div class="flex-1 h-px bg-surface-container-high"></div>
                <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest">Připravujeme</span>
            </div>

            <ul class="flex flex-col gap-2">
                <li class="flex items-center gap-4 px-4 py-4 bg-surface-container-lowest rounded-lg">
                    <span class="material-symbols-outlined text-primary text-xl shrink-0">event</span>
                    <span class="text-on-surface-variant text-sm italic">
                        Akce budou průběžně doplňovány.
                    </span>
                </li>
            </ul>

        </div>
    </div>
</section>
</div>
