<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('proběhlé akce klubu | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.news'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Aktuality', 'url' => route('news.index')],
                ['label' => 'proběhlé akce klubu'],
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

        {{-- Back link --}}
        <a href="{{ route('news.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors mb-8">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Aktuality
        </a>

        <div class="flex items-start gap-4 mb-4">
            <span class="material-symbols-outlined text-4xl text-primary mt-1">history</span>
            <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface">
                proběhlé akce klubu
            </h1>
        </div>
        <p class="text-on-surface-variant text-lg max-w-xl">
            Archiv uskutečněných akcí, soutěží, soustředění a seminářů klubu Judo club Raion-ryu.
        </p>
    </div>
</section>

{{-- Archive by year --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl flex flex-col gap-14">

            {{-- 2024 --}}
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <h2 class="font-headline text-3xl font-black text-primary tracking-tight">2024</h2>
                    <div class="flex-1 h-px bg-surface-container-high"></div>
                </div>
                <ul class="flex flex-col gap-2">
                    <li class="flex items-center gap-4 px-4 py-3 rounded-lg hover:bg-surface-container-low transition-colors group">
                        <time class="shrink-0 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-20">
                            Datum
                        </time>
                        <span class="flex-1 text-sm font-medium text-on-surface group-hover:text-primary transition-colors leading-snug">
                            Název akce — bude doplněno
                        </span>
                        <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary transition-colors shrink-0">arrow_forward</span>
                    </li>
                    <li class="flex items-center gap-4 px-4 py-3 rounded-lg hover:bg-surface-container-low transition-colors group">
                        <time class="shrink-0 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-20">
                            Datum
                        </time>
                        <span class="flex-1 text-sm font-medium text-on-surface group-hover:text-primary transition-colors leading-snug">
                            Název akce — bude doplněno
                        </span>
                        <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary transition-colors shrink-0">arrow_forward</span>
                    </li>
                </ul>
            </div>

            {{-- 2023 --}}
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <h2 class="font-headline text-3xl font-black text-primary tracking-tight">2023</h2>
                    <div class="flex-1 h-px bg-surface-container-high"></div>
                </div>
                <ul class="flex flex-col gap-2">
                    <li class="flex items-center gap-4 px-4 py-3 rounded-lg hover:bg-surface-container-low transition-colors group">
                        <time class="shrink-0 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-20">
                            Datum
                        </time>
                        <span class="flex-1 text-sm font-medium text-on-surface group-hover:text-primary transition-colors leading-snug">
                            Název akce — bude doplněno
                        </span>
                        <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary transition-colors shrink-0">arrow_forward</span>
                    </li>
                    <li class="flex items-center gap-4 px-4 py-3 rounded-lg hover:bg-surface-container-low transition-colors group">
                        <time class="shrink-0 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-20">
                            Datum
                        </time>
                        <span class="flex-1 text-sm font-medium text-on-surface group-hover:text-primary transition-colors leading-snug">
                            Název akce — bude doplněno
                        </span>
                        <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary transition-colors shrink-0">arrow_forward</span>
                    </li>
                </ul>
            </div>

            {{-- 2022 --}}
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <h2 class="font-headline text-3xl font-black text-primary tracking-tight">2022</h2>
                    <div class="flex-1 h-px bg-surface-container-high"></div>
                </div>
                <ul class="flex flex-col gap-2">
                    <li class="flex items-center gap-4 px-4 py-3 rounded-lg hover:bg-surface-container-low transition-colors group">
                        <time class="shrink-0 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-20">
                            Datum
                        </time>
                        <span class="flex-1 text-sm font-medium text-on-surface group-hover:text-primary transition-colors leading-snug">
                            Název akce — bude doplněno
                        </span>
                        <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary transition-colors shrink-0">arrow_forward</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</section>
</div>
