<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('NAPSALI O NÁS | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.news'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Aktuality', 'url' => route('news.index')],
                ['label' => 'NAPSALI O NÁS'],
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
            <span class="material-symbols-outlined text-4xl text-primary mt-1">newspaper</span>
            <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface">
                NAPSALI O NÁS
            </h1>
        </div>
        <p class="text-on-surface-variant text-lg max-w-xl">
            Mediální ohlasy, články a zmínky o klubu Judo club Raion-ryu v tisku a na internetu.
        </p>
    </div>
</section>

{{-- Media mentions list --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl">

            {{-- Placeholder note --}}
            <div class="flex items-start gap-3 bg-surface-container-lowest p-6 rounded-lg mb-12">
                <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">info</span>
                <p class="text-on-surface-variant text-sm leading-relaxed">
                    Přehled mediálních ohlasů bude průběžně doplňován.
                </p>
            </div>

            {{-- Article list placeholder --}}
            <ul class="flex flex-col gap-4">

                {{-- Placeholder item 1 --}}
                <li>
                    <div class="bg-surface-container-lowest p-6 rounded-lg flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-primary uppercase tracking-widest">Název publikace</span>
                                <span class="text-xs text-on-surface-variant">·</span>
                                <time class="text-xs text-on-surface-variant">DD. MM. RRRR</time>
                            </div>
                            <p class="text-on-surface font-medium leading-snug">
                                Název článku — bude doplněno
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded bg-surface-container text-on-surface-variant text-xs font-semibold uppercase tracking-wide opacity-50 cursor-not-allowed shrink-0">
                            <span class="material-symbols-outlined text-base">open_in_new</span>
                            Odkaz
                        </span>
                    </div>
                </li>

                {{-- Placeholder item 2 --}}
                <li>
                    <div class="bg-surface-container-lowest p-6 rounded-lg flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-primary uppercase tracking-widest">Název publikace</span>
                                <span class="text-xs text-on-surface-variant">·</span>
                                <time class="text-xs text-on-surface-variant">DD. MM. RRRR</time>
                            </div>
                            <p class="text-on-surface font-medium leading-snug">
                                Název článku — bude doplněno
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded bg-surface-container text-on-surface-variant text-xs font-semibold uppercase tracking-wide opacity-50 cursor-not-allowed shrink-0">
                            <span class="material-symbols-outlined text-base">open_in_new</span>
                            Odkaz
                        </span>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</section>
</div>
