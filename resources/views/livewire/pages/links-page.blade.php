<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Odkazy | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.links'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Odkazy'],
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
            Výběr externích odkazů na organizace, federace a partnerské weby. Odkazované weby jsou provozovány třetími stranami.
        </p>
    </div>
</section>

{{-- Links grid --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">

            @foreach($content['links'] as $link)
            <a href="{{ $link['url'] }}"
               target="_blank"
               rel="noopener noreferrer"
               class="group bg-surface-container-lowest p-8 rounded-lg flex flex-col gap-4 hover:translate-y-[-4px] transition-all duration-300 block">

                <div class="flex items-start justify-between gap-3">
                    <span class="material-symbols-outlined text-2xl text-primary">language</span>
                    <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary transition-colors">open_in_new</span>
                </div>

                <div class="flex-1">
                    <h3 class="font-headline text-lg font-bold text-on-surface tracking-tight group-hover:text-primary transition-colors mb-2 leading-snug">
                        {{ $link['title'] }}
                    </h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">
                        {{ $link['note'] }}
                    </p>
                </div>

                <span class="text-xs text-on-surface-variant truncate mt-auto pt-2">
                    {{ $link['url'] }}
                </span>
            </a>
            @endforeach

        </div>
    </div>
</section>
</div>
