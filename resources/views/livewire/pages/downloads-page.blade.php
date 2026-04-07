<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Ke stažení | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.downloads'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Ke stažení'],
            ],
        ];
    }
}; ?>

@php
    $categoryIcons = [
        'Přihlášky'                      => 'edit_document',
        'GDPR a pravidla'                => 'policy',
        'Techniky a studijní materiály'  => 'school',
        'Dokumenty ČSJu'                 => 'gavel',
    ];
@endphp

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
            Formuláře, dokumenty a studijní materiály ke stažení.
        </p>
    </div>
</section>

{{-- Download categories --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl flex flex-col gap-16">

            @foreach($content['categories'] as $categoryName => $items)
            @php $categoryIcon = $categoryIcons[$categoryName] ?? 'folder'; @endphp
            <div>
                {{-- Category heading --}}
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-2xl text-primary">{{ $categoryIcon }}</span>
                    <h2 class="font-headline text-2xl font-extrabold text-on-surface tracking-tight">
                        {{ $categoryName }}
                    </h2>
                </div>

                {{-- Items --}}
                <ul class="flex flex-col gap-3">
                    @foreach($items as $item)
                    <li>
                        <x-ui.download-item :title="$item" icon="description" />
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach

        </div>
    </div>
</section>

{{-- Help note --}}
<section class="py-16 md:py-20 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex items-start gap-4 max-w-2xl bg-surface-container-lowest p-6 rounded-lg">
            <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">help</span>
            <div>
                <p class="font-semibold text-on-surface mb-1">Potřebujete pomoc?</p>
                <p class="text-on-surface-variant text-sm leading-relaxed">
                    Pokud nemůžete stáhnout dokument nebo potřebujete jinou verzi, kontaktujte nás telefonicky nebo navštivte trénink.
                </p>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 text-sm font-bold text-primary uppercase tracking-widest mt-4 hover:gap-3 transition-all">
                    Kontakt
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</section>
</div>
