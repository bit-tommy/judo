<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Tréninky a ceník | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.training'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Tréninky a ceník'],
            ],
        ];
    }
}; ?>

<div>
{{-- Hero / Intro --}}
<section class="relative bg-gradient-to-br from-surface via-surface-container-low to-surface-container overflow-hidden">
    <div class="absolute inset-0 flex items-center justify-end pr-8 md:pr-16 pointer-events-none select-none" aria-hidden="true">
        <span class="font-headline text-[12rem] md:text-[18rem] font-black text-primary opacity-[0.04] leading-none">
            
        </span>
    </div>
    <div class="container mx-auto px-6 md:px-8 py-20 md:py-32 relative z-10">
        <div class="max-w-3xl">
            <span class="inline-block px-3 py-1 bg-primary text-on-primary text-xs font-bold uppercase tracking-widest rounded mb-6">
                Judo Club Raion-ryu
            </span>
            <h1 class="font-headline text-5xl md:text-7xl font-black text-primary italic leading-[0.9] tracking-tight mb-6">
                {{ $content['title'] }}
            </h1>
            <p class="text-lg md:text-xl text-on-surface-variant max-w-2xl leading-relaxed">
                Nabízíme tréninky pro děti od 5 let, mládež i dospělé. Tréninky probíhají v našem Honbu Dojo Invalidovna v Praze 8.
            </p>
        </div>
    </div>
</section>

{{-- Section Nav --}}
@include('livewire.pages.training.partials.section-nav')

{{-- Schedule Section --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">

        <div class="mb-12 md:mb-16">
            <p class="text-xs font-bold tracking-[0.3em] uppercase text-primary mb-3">{{ $content['schedule_title'] }}</p>
            <h2 class="font-headline text-4xl md:text-5xl font-extrabold text-on-surface tracking-tight mb-3">
                Rozpis tréninků
            </h2>
            <p class="text-on-surface-variant font-medium uppercase tracking-wide text-sm">
                {{ $content['main_dojo'] }}
            </p>
        </div>

        {{-- Mobile: cards --}}
        <div class="md:hidden flex flex-col gap-4">
            @foreach($content['schedule'] as $entry)
            @php $isPaused = isset($entry['status']); @endphp
            <div class="bg-surface-container-lowest rounded-lg p-5 {{ $isPaused ? 'opacity-70' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-2xl mt-0.5 shrink-0">
                            {{ $isPaused ? 'sports_martial_arts' : 'sports_martial_arts' }}
                        </span>
                        <div>
                            <p class="font-headline font-bold text-on-surface text-base tracking-tight">{{ $entry['name'] }}</p>
                            @if(!$isPaused)
                            <p class="text-sm text-on-surface-variant mt-0.5">
                                {{ $entry['days'] }} &bull; {{ $entry['time'] }}
                            </p>
                            @if(!empty($entry['note']))
                            <p class="text-xs text-on-surface-variant mt-1">{{ $entry['note'] }}</p>
                            @endif
                            @endif
                        </div>
                    </div>
                    @if($isPaused)
                    <span class="shrink-0 inline-block px-2 py-1 bg-error text-on-error text-xs font-black uppercase tracking-wide rounded leading-tight text-center">
                        Pozastaveno
                    </span>
                    @else
                    <span class="shrink-0 inline-flex items-center gap-1 px-2 py-1 bg-surface-container-high text-on-surface-variant text-xs font-medium rounded">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        Aktivní
                    </span>
                    @endif
                </div>
                @if($isPaused)
                <p class="text-xs font-black text-error uppercase tracking-wide mt-3">{{ $entry['status'] }}</p>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Desktop: table --}}
        <div class="hidden md:block rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary text-on-primary">
                        <th class="px-6 py-4 text-left font-bold uppercase tracking-widest text-xs">Program</th>
                        <th class="px-6 py-4 text-left font-bold uppercase tracking-widest text-xs">Dny</th>
                        <th class="px-6 py-4 text-left font-bold uppercase tracking-widest text-xs">Čas</th>
                        <th class="px-6 py-4 text-left font-bold uppercase tracking-widest text-xs">Poznámka</th>
                        <th class="px-6 py-4 text-left font-bold uppercase tracking-widest text-xs">Stav</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($content['schedule'] as $index => $entry)
                    @php $isPaused = isset($entry['status']); @endphp
                    <tr class="{{ $index % 2 === 0 ? 'bg-surface-container-lowest' : 'bg-surface-container-low' }} {{ $isPaused ? 'opacity-60' : 'hover:bg-surface-container transition-colors' }}">
                        <td class="px-6 py-4">
                            <span class="font-headline font-bold text-on-surface tracking-tight">{{ $entry['name'] }}</span>
                            @if($isPaused)
                            <p class="text-xs font-black text-error uppercase tracking-wide mt-0.5">{{ $entry['status'] }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-on-surface-variant">
                            {{ $entry['days'] ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-on-surface-variant font-medium">
                            {{ $entry['time'] ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-on-surface-variant text-xs">
                            {{ $entry['note'] ?? '' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($isPaused)
                            <span class="inline-block px-3 py-1 bg-error text-on-error text-xs font-black uppercase tracking-wide rounded">
                                Pozastaveno
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-surface-container-high text-on-surface-variant text-xs font-medium rounded">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Aktivní
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</section>

{{-- Free Trial CTA Banner --}}
<section class="bg-primary py-16 md:py-20 overflow-hidden relative">
    <div class="absolute inset-0 opacity-10" aria-hidden="true" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;6&quot; height=&quot;6&quot; viewBox=&quot;0 0 6 6&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.3&quot;%3E%3Cpath d=&quot;M5 0h1L0 6V5zM6 5v1H5z&quot;/%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-on-primary text-4xl shrink-0">star</span>
                <div>
                    <p class="text-on-primary font-headline font-black text-2xl md:text-3xl mb-1">
                        První trénink zdarma!
                    </p>
                    <p class="text-on-primary opacity-80 text-sm md:text-base max-w-xl">
                        {{ $content['free_trial'] }}
                    </p>
                </div>
            </div>
            <a
                href="{{ route('contact') }}"
                class="shrink-0 inline-flex items-center gap-2 px-8 py-4 bg-surface text-primary font-bold uppercase tracking-widest text-sm rounded hover:bg-surface-container transition-colors"
            >
                Zkusit zdarma
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

{{-- Kimono Note --}}
<section class="py-16 md:py-20 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex items-start gap-5 bg-surface-container-lowest rounded-lg p-6 md:p-8 max-w-3xl">
            <span class="material-symbols-outlined text-primary text-3xl shrink-0 mt-0.5">checkroom</span>
            <div>
                <h3 class="font-headline font-bold text-on-surface text-lg mb-2">Kimono (Judogi)</h3>
                <p class="text-on-surface-variant leading-relaxed">
                    {{ $content['kimono_note'] }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Quick Pricing Overview --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
            <div>
                <p class="text-xs font-bold tracking-[0.3em] uppercase text-primary mb-3">{{ $content['pricing_title'] }}</p>
                <h2 class="font-headline text-4xl md:text-5xl font-extrabold text-on-surface tracking-tight">
                    Přehled plateb
                </h2>
            </div>
            <a
                href="{{ route('training.pricing') }}"
                class="inline-flex items-center gap-2 text-primary font-bold uppercase tracking-widest text-xs hover:gap-4 transition-all shrink-0"
            >
                Detailní ceník
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-8 mb-12">
            @foreach(array_filter($content['pricing'], fn($p) => isset($p['name'])) as $tier)
            <div class="bg-surface-container-lowest rounded-lg p-8 flex flex-col gap-4">
                <span class="material-symbols-outlined text-primary text-3xl">sports_martial_arts</span>
                <h3 class="font-headline font-bold text-on-surface text-xl tracking-tight">{{ $tier['name'] }}</h3>
                <p class="text-on-surface-variant text-sm leading-relaxed">{{ $tier['payment'] }}</p>
                <p class="text-xs text-on-surface-variant italic">{{ $tier['amount'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Payment Rules --}}
        <div class="flex items-start gap-4 bg-surface-container-low rounded-lg p-6">
            <span class="material-symbols-outlined text-primary text-2xl shrink-0 mt-0.5">account_balance</span>
            <div>
                <h4 class="font-bold text-on-surface mb-1">Platební podmínky</h4>
                <p class="text-on-surface-variant text-sm leading-relaxed">{{ $content['payment_rules'] }}</p>
                <p class="text-sm text-on-surface-variant mt-2">
                    Číslo účtu: <span class="font-bold text-on-surface">{{ $content['bank_account'] }}</span>
                </p>
            </div>
        </div>

    </div>
</section>

{{-- CTA to Contact --}}
<section class="py-20 md:py-32 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8 text-center">
        <span class="material-symbols-outlined text-primary text-5xl mb-6 block">sports_martial_arts</span>
        <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-on-surface mb-4 tracking-tight">
            Připraveni začít?
        </h2>
        <p class="text-on-surface-variant mb-8 max-w-md mx-auto leading-relaxed">
            Kontaktujte nás a domluvte si zkušební trénink. Rádi vás přivítáme na tatami.
        </p>
        <a
            href="{{ route('contact') }}"
            class="inline-flex items-center gap-2 px-8 py-4 bg-primary-container text-on-primary font-bold uppercase tracking-widest text-sm rounded hover:opacity-90 transition-opacity"
        >
            Kontaktujte nás
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
    </div>
</section>
</div>
