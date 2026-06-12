<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Ceník | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.training'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Tréninky a ceník', 'url' => route('training.index')],
                ['label' => 'Ceník'],
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
                Tréninky a ceník
            </span>
            <h1 class="font-headline text-5xl md:text-7xl font-black text-primary italic leading-[0.9] tracking-tight mb-4">
                {{ $content['pricing_title'] }}
            </h1>
            <p class="text-on-surface-variant text-lg leading-relaxed">
                Platby se provádějí vždy na celé čtvrtletí předem.
            </p>
        </div>
    </div>
</section>

{{-- Section Nav --}}
@include('livewire.pages.training.partials.section-nav')

{{-- Pricing Cards --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach(array_filter($content['pricing'], fn($p) => isset($p['name'])) as $index => $tier)
            @php $isHighlighted = $index === 1; @endphp
            <div class="rounded-lg p-8 md:p-10 flex flex-col gap-6
                {{ $isHighlighted
                    ? 'bg-primary-container text-on-primary'
                    : 'bg-surface-container-lowest text-on-surface' }}">

                <div>
                    @if($isHighlighted)
                    <span class="inline-block text-xs font-bold uppercase tracking-widest opacity-70 mb-2">Nejoblíbenější</span>
                    @endif
                    <h2 class="font-headline text-2xl font-black tracking-tight">{{ $tier['name'] }}</h2>
                </div>

                <span class="material-symbols-outlined text-4xl {{ $isHighlighted ? 'text-on-primary' : 'text-primary' }}">
                    sports_martial_arts
                </span>

                <ul class="flex flex-col gap-3 flex-1">
                    <li class="flex items-start gap-2 text-sm">
                        <span class="material-symbols-outlined text-base mt-0.5 shrink-0 {{ $isHighlighted ? 'text-on-primary' : 'text-primary' }}">
                            check_circle
                        </span>
                        <span class="{{ $isHighlighted ? 'opacity-90' : 'text-on-surface-variant' }}">
                            {{ $tier['payment'] }}
                        </span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <span class="material-symbols-outlined text-base mt-0.5 shrink-0 {{ $isHighlighted ? 'text-on-primary' : 'text-primary' }}">
                            info
                        </span>
                        <span class="{{ $isHighlighted ? 'opacity-90' : 'text-on-surface-variant' }} italic">
                            {{ $tier['amount'] }}
                        </span>
                    </li>
                </ul>

                <a
                    href="{{ route('contact') }}"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded text-sm font-semibold uppercase tracking-wide transition-opacity hover:opacity-90
                        {{ $isHighlighted
                            ? 'bg-surface text-primary'
                            : 'bg-primary text-on-primary' }}"
                >
                    Začít trénovat
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
            @endforeach
        </div>

        {{-- Note from pricing array --}}
        @foreach($content['pricing'] as $item)
        @if(isset($item['note']))
        <div class="mt-8 flex items-start gap-3 bg-surface-container-low rounded-lg p-5 max-w-3xl">
            <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">info</span>
            <p class="text-sm text-on-surface-variant leading-relaxed">{{ $item['note'] }}</p>
        </div>
        @endif
        @endforeach

    </div>
</section>

{{-- Payment Rules --}}
<section class="py-16 md:py-20 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <div class="max-w-3xl">
            <h2 class="font-headline text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-8">
                Platební informace
            </h2>

            <div class="flex flex-col gap-4">
                <div class="flex items-start gap-4 bg-surface-container-lowest rounded-lg p-6">
                    <span class="material-symbols-outlined text-primary text-2xl shrink-0 mt-0.5">calendar_month</span>
                    <div>
                        <h3 class="font-bold text-on-surface mb-1">Platební podmínky</h3>
                        <p class="text-on-surface-variant text-sm leading-relaxed">{{ $content['payment_rules'] }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 bg-surface-container-lowest rounded-lg p-6">
                    <span class="material-symbols-outlined text-primary text-2xl shrink-0 mt-0.5">account_balance</span>
                    <div>
                        <h3 class="font-bold text-on-surface mb-1">Číslo bankovního účtu</h3>
                        <p class="font-headline font-black text-xl text-primary">{{ $content['bank_account'] }}</p>
                        <p class="text-on-surface-variant text-xs mt-1">Při platbě uvádějte jako variabilní symbol číslo člena.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="bg-primary rounded-lg p-8 md:p-16 flex flex-col md:flex-row items-center justify-between gap-8 overflow-hidden relative">
            <div class="absolute inset-0 opacity-10" aria-hidden="true" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;6&quot; height=&quot;6&quot; viewBox=&quot;0 0 6 6&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.3&quot;%3E%3Cpath d=&quot;M5 0h1L0 6V5zM6 5v1H5z&quot;/%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="relative z-10">
                <h2 class="font-headline text-3xl md:text-4xl font-black text-on-primary mb-2">Máte otázky k ceníku?</h2>
                <p class="text-on-primary opacity-80">Rádi vám odpovíme na vše ohledně členství a plateb.</p>
            </div>
            <a
                href="{{ route('contact') }}"
                class="relative z-10 shrink-0 inline-flex items-center gap-2 px-8 py-4 bg-surface text-primary font-bold uppercase tracking-widest text-sm rounded hover:bg-surface-container transition-colors"
            >
                Kontaktovat nás
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
</div>
