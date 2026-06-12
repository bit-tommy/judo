<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Dospělí | Judo Club Raion-ryu')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.training'),
            'breadcrumbs' => [
                ['label' => 'Úvod', 'url' => route('home')],
                ['label' => 'Tréninky a ceník', 'url' => route('training.index')],
                ['label' => 'Dospělí'],
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
                Tréninky
            </span>
            <h1 class="font-headline text-5xl md:text-7xl font-black text-primary italic leading-[0.9] tracking-tight mb-4">
                Dospělí
            </h1>
            <p class="text-on-surface-variant text-lg leading-relaxed">
                {{ $content['adults']['name'] }} — pro začátečníky i pokročilé
            </p>
        </div>
    </div>
</section>

{{-- Section Nav --}}
@include('livewire.pages.training.partials.section-nav')

{{-- Program Detail --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">

            {{-- Main Content --}}
            <div class="lg:col-span-2">
                <p class="text-xs font-bold tracking-[0.3em] uppercase text-primary mb-4">{{ $content['adults']['name'] }}</p>
                <h2 class="font-headline text-3xl md:text-4xl font-extrabold text-on-surface tracking-tight mb-6">
                    O programu
                </h2>
                <p class="text-on-surface-variant text-lg leading-relaxed mb-8">
                    {{ $content['adults']['description'] }}
                </p>

                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">fitness_center</span>
                        <div>
                            <span class="font-bold text-on-surface text-sm">Vhodné pro</span>
                            <p class="text-on-surface-variant text-sm">Začátečníky i pokročilé dospělé</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">sports</span>
                        <div>
                            <span class="font-bold text-on-surface text-sm">Zaměření</span>
                            <p class="text-on-surface-variant text-sm">Technika, kondice a volný zápas (randori)</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl shrink-0 mt-0.5">location_on</span>
                        <div>
                            <span class="font-bold text-on-surface text-sm">Místo</span>
                            <p class="text-on-surface-variant text-sm">{{ $content['main_dojo'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach(['Technický trénink', 'Kondiční cvičení', 'Randori — volný zápas', 'Přátelská atmosféra', 'Pro všechny úrovně pokročilosti', 'Pravidelný tréninkový program'] as $feature)
                    <div class="flex items-center gap-3 bg-surface-container-low rounded-lg p-4">
                        <span class="material-symbols-outlined text-primary text-lg shrink-0">check_circle</span>
                        <span class="text-sm text-on-surface-variant">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Schedule Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-surface-container-lowest rounded-lg p-6 md:p-8 sticky top-28">
                    <h3 class="font-headline font-bold text-on-surface text-xl mb-6 tracking-tight">
                        Rozvrh
                    </h3>

                    <div class="flex flex-col gap-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl shrink-0">calendar_today</span>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">Dny</p>
                                <p class="font-bold text-on-surface">{{ $content['adults']['days'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl shrink-0">schedule</span>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">Čas</p>
                                <p class="font-bold text-on-surface">{{ $content['adults']['time'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl shrink-0">check_circle</span>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">Stav</p>
                                <p class="font-bold text-on-surface">Aktivní</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a
                            href="{{ route('contact') }}"
                            class="flex items-center justify-center gap-2 px-6 py-3.5 bg-primary text-on-primary font-bold uppercase tracking-wide text-sm rounded hover:opacity-90 transition-opacity w-full"
                        >
                            Zkušební trénink
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- Free Trial CTA --}}
<section class="py-16 md:py-20 bg-primary overflow-hidden relative">
    <div class="absolute inset-0 opacity-10" aria-hidden="true" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;6&quot; height=&quot;6&quot; viewBox=&quot;0 0 6 6&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.3&quot;%3E%3Cpath d=&quot;M5 0h1L0 6V5zM6 5v1H5z&quot;/%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <h2 class="font-headline font-black text-on-primary text-2xl md:text-3xl mb-2">
                    První trénink zdarma!
                </h2>
                <p class="text-on-primary opacity-80 max-w-lg">
                    {{ $content['free_trial'] }}
                </p>
            </div>
            <a
                href="{{ route('contact') }}"
                class="shrink-0 inline-flex items-center gap-2 px-8 py-4 bg-surface text-primary font-bold uppercase tracking-widest text-sm rounded hover:bg-surface-container transition-colors"
            >
                Přihlásit se
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

{{-- Navigation to other programs --}}
<section class="py-20 md:py-32 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <h2 class="font-headline text-2xl md:text-3xl font-extrabold text-on-surface tracking-tight mb-8">
            Další programy
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('training.preparatory') }}" class="bg-surface-container-lowest rounded-lg p-6 flex items-center gap-4 hover:translate-y-[-4px] transition-all duration-300 group">
                <span class="material-symbols-outlined text-primary text-3xl">child_care</span>
                <div>
                    <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Přípravka</p>
                    <p class="text-xs text-on-surface-variant">Děti od 5 let</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-base ml-auto group-hover:text-primary transition-colors">arrow_forward</span>
            </a>
            <a href="{{ route('training.advanced') }}" class="bg-surface-container-lowest rounded-lg p-6 flex items-center gap-4 hover:translate-y-[-4px] transition-all duration-300 group">
                <span class="material-symbols-outlined text-primary text-3xl">sports_martial_arts</span>
                <div>
                    <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Pokročilí</p>
                    <p class="text-xs text-on-surface-variant">Děti a mládež</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-base ml-auto group-hover:text-primary transition-colors">arrow_forward</span>
            </a>
            <a href="{{ route('training.pricing') }}" class="bg-surface-container-lowest rounded-lg p-6 flex items-center gap-4 hover:translate-y-[-4px] transition-all duration-300 group">
                <span class="material-symbols-outlined text-primary text-3xl">payments</span>
                <div>
                    <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Ceník</p>
                    <p class="text-xs text-on-surface-variant">Informace o platbách</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-base ml-auto group-hover:text-primary transition-colors">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
</div>
