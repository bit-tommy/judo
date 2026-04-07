<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Sebeobrana | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.sebeobrana'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Sebeobrana'],
            ],
        ];
    }
}; ?>

<div>
{{-- Hero --}}
<x-ui.hero
    tagline="Bojová umění v praxi"
    title="{{ $content['title'] }}"
    subtitle="{{ $content['intro'] }}"
    primaryCta="Kontaktujte nás"
    primaryCtaLink="{{ route('contact') }}"
/>

{{-- Services --}}
<x-ui.content-section title="Nabídka kurzů" bg="surface">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-4xl">
        @foreach($content['services'] as $service)
        <div class="flex items-start gap-4 bg-surface-container-lowest p-6 rounded-lg">
            <span class="material-symbols-outlined text-primary text-2xl shrink-0 mt-0.5">self_improvement</span>
            <span class="text-on-surface leading-relaxed">{{ $service }}</span>
        </div>
        @endforeach
    </div>
</x-ui.content-section>

{{-- Corporate offer banner --}}
<section class="py-20 md:py-32 bg-primary overflow-hidden relative">
    <div class="absolute inset-0 pointer-events-none select-none flex items-center justify-end pr-8 md:pr-16" aria-hidden="true">
        <span class="font-headline text-[10rem] md:text-[16rem] font-black text-on-primary opacity-[0.05] leading-none">柔</span>
    </div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-on-primary/10 text-on-primary text-xs font-bold tracking-[0.2em] uppercase px-4 py-2 rounded mb-8">
                <span class="material-symbols-outlined text-sm">corporate_fare</span>
                Firemní nabídka
            </div>
            <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-primary mb-8 leading-tight">
                {{ $content['corporate_title'] }}
            </h2>
            <p class="text-on-primary/80 text-lg leading-relaxed mb-8 max-w-2xl">
                {{ $content['corporate_text'] }}
            </p>
            <p class="text-on-primary/60 text-sm leading-relaxed italic mb-10">
                {{ $content['pricing_note'] }}
            </p>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center gap-2 bg-on-primary text-primary px-8 py-4 rounded-md font-bold uppercase tracking-widest text-sm hover:translate-y-[-2px] transition-transform">
                <span class="material-symbols-outlined text-base">mail</span>
                Nezávazně nás kontaktujte
            </a>
        </div>
    </div>
</section>

{{-- CTA strip --}}
<section class="py-16 md:py-20 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <h3 class="font-headline text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-2">
                    Máte zájem o kurz sebeobrany?
                </h3>
                <p class="text-on-surface-variant">Ozvěte se nám a domluvíme se na detailech.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 shrink-0">
                <a href="tel:+420777166156"
                   class="inline-flex items-center gap-2 bg-primary text-on-primary px-8 py-4 rounded-md font-bold uppercase tracking-widest text-sm hover:bg-primary-container transition-colors">
                    <span class="material-symbols-outlined text-base">call</span>
                    777 166 156
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 bg-surface-container-high text-on-surface px-8 py-4 rounded-md font-bold uppercase tracking-widest text-sm hover:bg-surface-container transition-colors">
                    Kontaktní formulář
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</section>
</div>
