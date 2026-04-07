<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Aktuality | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.news'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Aktuality'],
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
            {{ $content['title'] }}
        </h1>
        <p class="text-on-surface-variant text-lg max-w-xl">
            Sledujte dění v klubu, plánované akce a mediální ohlasy.
        </p>
    </div>
</section>

{{-- Section cards --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

            {{-- Plán AKCÍ --}}
            <a href="{{ route('news.plan-akci') }}"
               class="group bg-surface-container-lowest p-10 rounded-lg hover:translate-y-[-8px] transition-all duration-300 flex flex-col gap-5 block">
                <span class="material-symbols-outlined text-4xl text-primary">event_note</span>
                <h2 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight group-hover:text-primary transition-colors">
                    {{ $content['sections']['plan_akci']['title'] }}
                </h2>
                <p class="text-on-surface-variant leading-relaxed flex-1">
                    {{ $content['sections']['plan_akci']['description'] }}
                </p>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                    Zobrazit
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </span>
            </a>

            {{-- NAPSALI O NÁS --}}
            <a href="{{ route('news.napsali-o-nas') }}"
               class="group bg-surface-container-lowest p-10 rounded-lg hover:translate-y-[-8px] transition-all duration-300 flex flex-col gap-5 block">
                <span class="material-symbols-outlined text-4xl text-primary">newspaper</span>
                <h2 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight group-hover:text-primary transition-colors">
                    {{ $content['sections']['napsali_o_nas']['title'] }}
                </h2>
                <p class="text-on-surface-variant leading-relaxed flex-1">
                    {{ $content['sections']['napsali_o_nas']['description'] }}
                </p>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                    Zobrazit
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </span>
            </a>

            {{-- proběhlé akce --}}
            <a href="{{ route('news.probehle-akce') }}"
               class="group bg-surface-container-lowest p-10 rounded-lg hover:translate-y-[-8px] transition-all duration-300 flex flex-col gap-5 block">
                <span class="material-symbols-outlined text-4xl text-primary">history</span>
                <h2 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight group-hover:text-primary transition-colors">
                    {{ $content['sections']['probehle_akce']['title'] }}
                </h2>
                <p class="text-on-surface-variant leading-relaxed flex-1">
                    {{ $content['sections']['probehle_akce']['description'] }}
                </p>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-auto group-hover:gap-3 transition-all">
                    Zobrazit
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </span>
            </a>

        </div>
    </div>
</section>
</div>
