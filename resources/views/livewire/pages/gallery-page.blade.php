<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Galerie | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Galerie'],
            ],
        ];
    }
}; ?>

<div>
{{-- Page header --}}
<section class="bg-surface-container-low py-20 md:py-32 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none select-none flex items-center justify-end pr-8 md:pr-16" aria-hidden="true">
        <span class="font-headline text-[10rem] md:text-[16rem] font-black text-primary opacity-[0.04] leading-none">柔道</span>
    </div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <span class="inline-block px-3 py-1 bg-primary text-on-primary text-xs font-bold uppercase tracking-widest rounded mb-6">
            Judo club Raion-ryu
        </span>
        <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-4">
            Galerie
        </h1>
        <p class="text-on-surface-variant text-lg max-w-xl">
            Fotografie a videa ze života klubu, soutěží a tréninků.
        </p>
    </div>
</section>

{{-- Gallery type cards --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-3xl">

            {{-- Fotogalerie --}}
            <div class="bg-surface-container-lowest p-10 rounded-lg flex flex-col gap-5">
                <span class="material-symbols-outlined text-4xl text-primary">photo_camera</span>
                <h2 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Fotogalerie
                </h2>
                <p class="text-on-surface-variant leading-relaxed flex-1">
                    Fotografie ze soutěží, tréninků a akcí klubu jsou dostupné v naší online galerii na portálu Rajče.
                </p>
                <a href="https://judo-raion-ryu.rajce.idnes.cz/"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-primary text-on-primary px-6 py-3 rounded-md font-bold uppercase tracking-widest text-xs hover:bg-primary-container transition-colors w-fit">
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                    Otevřít fotogalerii
                </a>
            </div>

            {{-- Videogalerie --}}
            <div class="bg-surface-container-lowest p-10 rounded-lg flex flex-col gap-5">
                <span class="material-symbols-outlined text-4xl text-primary">videocam</span>
                <h2 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight">
                    Videogalerie
                </h2>
                <p class="text-on-surface-variant leading-relaxed flex-1">
                    Záznamy z tréninků, soutěží a instruktážních videí klubu Judo club Raion-ryu.
                </p>
                <a href="https://www.youtube.com/"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-primary text-on-primary px-6 py-3 rounded-md font-bold uppercase tracking-widest text-xs hover:bg-primary-container transition-colors w-fit">
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                    Otevřít videogalerii
                </a>
            </div>

        </div>
    </div>
</section>

{{-- Internal gallery placeholder --}}
<section class="py-20 md:py-32 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <div class="mb-12 md:mb-16 max-w-2xl">
            <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-4">
                Výběr fotografií
            </h2>
            <p class="text-on-surface-variant text-lg leading-relaxed">
                Ukázka fotografií ze života klubu.
            </p>
        </div>

        <x-ui.gallery-grid :images="[]" :columns="3" />
    </div>
</section>
</div>
