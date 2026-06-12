<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Kontakt | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.contact'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Kontakt'],
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
        <span class="inline-block px-3 py-1 bg-primary text-on-primary text-xs font-bold uppercase tracking-widest rounded mb-6">
            Judo club Raion-ryu
        </span>
        <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-4">
            {{ $content['title'] }}
        </h1>
        <p class="text-on-surface-variant text-lg max-w-xl">
            Obraťte se na nás pro informace o trénincích, zápisech nebo firemních kurzech sebeobrany.
        </p>
    </div>
</section>

{{-- Main contact + locations --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">

            {{-- Left: main contact card --}}
            <div class="flex flex-col gap-6">
                <div class="bg-surface-container-lowest p-8 md:p-10 rounded-lg">

                    {{-- Name & role --}}
                    <div class="mb-8">
                        <h2 class="font-headline text-3xl md:text-4xl font-extrabold tracking-tight text-on-surface">
                            {{ $content['name'] }}
                        </h2>
                        <p class="text-primary font-semibold mt-1">{{ $content['role'] }}</p>
                    </div>

                    {{-- Contact details --}}
                    <ul class="flex flex-col gap-5">

                        {{-- Phone --}}
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary text-xl">call</span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest mb-0.5">Telefon</p>
                                <a href="tel:+420{{ str_replace(' ', '', $content['phone']) }}"
                                   class="font-headline text-xl font-bold text-on-surface hover:text-primary transition-colors">
                                    {{ $content['phone'] }}
                                </a>
                            </div>
                        </li>

                        {{-- Web --}}
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary text-xl">language</span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest mb-0.5">Web</p>
                                <a href="https://{{ $content['web'] }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="font-medium text-on-surface hover:text-primary transition-colors">
                                    {{ $content['web'] }}
                                </a>
                            </div>
                        </li>

                        {{-- IČ --}}
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary text-xl">badge</span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest mb-0.5">IČ</p>
                                <span class="font-medium text-on-surface">{{ $content['ic'] }}</span>
                            </div>
                        </li>

                        {{-- Bank account --}}
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary text-xl">account_balance</span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest mb-0.5">Bankovní účet</p>
                                <span class="font-medium text-on-surface">{{ $content['bank_account'] }}</span>
                            </div>
                        </li>

                        {{-- Email note --}}
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-primary text-xl">mail</span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest mb-0.5">Email</p>
                                <p class="text-sm text-on-surface-variant leading-relaxed">{{ $content['email_note'] }}</p>
                            </div>
                        </li>

                    </ul>
                </div>

                {{-- Quick call CTA --}}
                <a href="tel:+420{{ str_replace(' ', '', $content['phone']) }}"
                   class="inline-flex items-center justify-center gap-3 bg-primary text-on-primary px-8 py-5 rounded-lg font-bold uppercase tracking-widest text-sm hover:bg-primary-container transition-colors w-full">
                    <span class="material-symbols-outlined text-xl">call</span>
                    Zavolat: {{ $content['phone'] }}
                </a>
            </div>

            {{-- Right: dojo locations --}}
            <div class="flex flex-col gap-6">
                <h2 class="font-headline text-2xl font-extrabold tracking-tight text-on-surface">
                    Naše dojo
                </h2>

                @foreach($content['locations'] as $key => $location)
                <div class="bg-surface-container-lowest p-8 rounded-lg flex flex-col gap-5">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-2xl shrink-0 mt-0.5">location_on</span>
                        <div>
                            <h3 class="font-headline text-lg font-bold text-on-surface tracking-tight leading-snug mb-1">
                                {{ $location['name'] }}
                            </h3>
                            <p class="text-sm text-on-surface-variant">{{ $location['address'] }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-on-surface-variant leading-relaxed">{{ $location['note'] }}</p>

                    {{-- Map placeholder --}}
                    <div class="w-full h-48 bg-surface-container-high rounded-lg flex items-center justify-center">
                        <div class="text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-3xl mb-2 block opacity-40">map</span>
                            <span class="text-xs font-semibold uppercase tracking-widest opacity-40">Mapa</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

{{-- CTA for beginners --}}
<section class="py-20 md:py-32 bg-primary relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none select-none flex items-center justify-end pr-8 md:pr-16" aria-hidden="true">
        <span class="font-headline text-[10rem] md:text-[16rem] font-black text-on-primary opacity-[0.05] leading-none"></span>
    </div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <div class="max-w-2xl">
            <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-primary mb-6 leading-tight">
                Začněte s judem
            </h2>
            <p class="text-on-primary/80 text-lg leading-relaxed mb-4">
                První trénink je zdarma a bez závazků. Stačí nás kontaktovat a domluvit se na vhodném termínu.
            </p>
            <ul class="flex flex-col gap-3 mb-10">
                <li class="flex items-center gap-3 text-on-primary/90 text-sm font-medium">
                    <span class="material-symbols-outlined text-on-primary text-xl">check_circle</span>
                    Zapůjčení gi (kimona) zdarma
                </li>
                <li class="flex items-center gap-3 text-on-primary/90 text-sm font-medium">
                    <span class="material-symbols-outlined text-on-primary text-xl">check_circle</span>
                    Konzultace s trenérem
                </li>
                <li class="flex items-center gap-3 text-on-primary/90 text-sm font-medium">
                    <span class="material-symbols-outlined text-on-primary text-xl">check_circle</span>
                    Bezpečné prostředí pro začátečníky
                </li>
            </ul>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="tel:+420{{ str_replace(' ', '', $content['phone']) }}"
                   class="inline-flex items-center justify-center gap-2 bg-on-primary text-primary px-8 py-4 rounded-md font-bold uppercase tracking-widest text-sm hover:translate-y-[-2px] transition-transform">
                    <span class="material-symbols-outlined text-base">call</span>
                    {{ $content['phone'] }}
                </a>
                <a href="{{ route('training.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-on-primary/10 text-on-primary px-8 py-4 rounded-md font-bold uppercase tracking-widest text-sm hover:bg-on-primary/20 transition-colors">
                    Rozpis tréninků
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</section>
</div>
