<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};

new #[Layout('components.layouts.app')]
#[Title('Judo Club Raion-ryu | Judo Praha')]
class extends Component {
    public function with(): array
    {
        return [
            'content' => config('content.homepage'),
        ];
    }
}; ?>

<div>
{{-- Hero Section --}}
<section class="relative min-h-[90vh] flex items-center overflow-hidden bg-surface-container-low">
    <div class="absolute inset-0 z-0">
        <img
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAwLv7nhChQqUau-usvF_iwrT9F6-03pdvfqQAbFPKYtuFlWhQpNjPmy3PqevzW6B3rWst0Z_aCHx5bar0LOFsYriveh2Zogq1Bw6z4r4CC_Xk1VzSbWoZYKe3FiNnJomWWZxuO5YvaR7nj1-SKoEElH4qnGZr8FbFsaa1gFtteGcODJ1YFzXxUqnMIE6XWpzvDwwBOyKQz4Zk4fOyQlpgHbMnfyetvguTokjpdCJ6MVneFXHZzcwZuArrHCRsYrGZdp4OW0hPHPTHm"
            alt="Judoka v bílém gi na tatami"
            class="w-full h-full object-cover opacity-90 grayscale-[0.2] contrast-[1.1]"
        />
        <div class="absolute inset-0 bg-gradient-to-r from-surface via-surface/40 to-transparent"></div>
    </div>

    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <div class="max-w-3xl">
            {{-- Phone CTA --}}
            <a href="tel:+420{{ str_replace(' ', '', $content['phone']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-on-primary text-xs font-bold tracking-[0.2em] uppercase mb-6 rounded-md hover:bg-primary-container transition-colors">
                <span class="material-symbols-outlined text-sm">call</span>
                {{ $content['phone_label'] }}
            </a>

            <h1 class="font-headline text-5xl sm:text-6xl md:text-8xl font-black text-primary leading-[0.9] tracking-tighter mb-4 italic">
                {{ $content['hero_title'] }}
            </h1>

            <p class="text-lg md:text-2xl text-on-surface-variant max-w-xl mb-10 leading-relaxed font-light">
                {{ $content['hero_subtitle'] }}
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('contact') }}"
                   class="bg-primary-container text-on-primary px-8 md:px-10 py-4 rounded-md font-bold uppercase tracking-widest text-sm hover:translate-y-[-2px] transition-transform inline-block">
                    {{ $content['cta_primary'] }}
                </a>
                <a href="{{ route('club') }}"
                   class="border border-outline/30 px-8 md:px-10 py-4 rounded-md font-bold uppercase tracking-widest text-sm hover:bg-surface-container transition-colors inline-block">
                    {{ $content['cta_secondary'] }}
                </a>
            </div>
        </div>
    </div>

    {{-- Decorative Japanese characters --}}
    <div class="absolute bottom-12 right-12 hidden lg:flex flex-col gap-4 text-primary font-headline text-5xl font-black opacity-10 select-none">
        <span>柔</span>
        <span>道</span>
    </div>
</section>

{{-- Teaser Cards --}}
<section class="py-20 md:py-32 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 md:mb-16 gap-4">
            <div>
                <h2 class="font-headline text-4xl md:text-5xl font-extrabold text-on-surface mb-4 tracking-tight">Naše Programy</h2>
                <p class="text-on-surface-variant max-w-md">Najděte tu správnou cestu pro věk a úroveň pokročilosti.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($content['teaser_cards'] as $card)
                <a href="{{ $card['link'] }}" class="bg-surface-container-lowest p-8 md:p-10 rounded-lg hover:translate-y-[-8px] transition-all duration-300 group block">
                    <div class="text-primary mb-6">
                        <span class="material-symbols-outlined text-4xl">{{ $card['icon'] }}</span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold mb-4 text-on-surface">{{ $card['title'] }}</h3>
                    <p class="text-on-surface-variant mb-8 leading-relaxed">{{ $card['description'] }}</p>
                    <span class="inline-flex items-center text-primary font-bold uppercase tracking-widest text-xs group-hover:gap-4 transition-all">
                        VÍCE INFORMACÍ
                        <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Club Intro --}}
<section class="py-20 md:py-32 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-center">
            <div class="w-full lg:w-1/2 relative">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>
                <img
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuD1QaXcY4S9fVKtOXxX-bGoPbjstTD0_MqI75Om3jheZJKYDviBCTBQVfWDtsjZ1FcV8gBDwuhy22EzpzPs3k9elv3Y9bd6KpfJ39NVesWtUHJ7h8lDOiflGHq9Vw18xwa0klTiKxVlLeO7YvXdW7Onxt23-pZS70wTMa0J_qLquGLcnRiicSXk1X51EP3Kxl-0s4hZStT9JdrxTXtMM_BAszzq62_KmN34_wZXLRHqHadr7gB6Y-06ElhHY6sEs9wFG0cSWL70gjqp"
                    alt="Děti v judo gi"
                    class="rounded-xl shadow-2xl relative z-10 grayscale-[0.1] w-full"
                />
                <div class="absolute -bottom-6 -right-6 bg-primary-container p-8 text-on-primary rounded-xl z-20 hidden md:block">
                    <div class="text-4xl font-headline font-black">15+</div>
                    <div class="text-xs uppercase tracking-widest font-bold opacity-80">Let Tradice</div>
                </div>
            </div>

            <div class="w-full lg:w-1/2">
                <p class="text-xs font-bold tracking-[0.3em] uppercase text-primary mb-4">{{ $content['club_name'] }}</p>
                <h2 class="font-headline text-4xl md:text-5xl font-extrabold text-on-surface mb-6 tracking-tight">
                    {{ $content['intro_title'] }}
                </h2>
                <p class="text-on-surface-variant leading-relaxed text-lg mb-8">
                    {{ $content['intro_text'] }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <span class="text-sm text-on-surface-variant">
                        <span class="font-bold text-primary">{{ $content['motto'] }}</span>
                        <span class="italic ml-2 opacity-70">— {{ $content['motto_en'] }}</span>
                    </span>
                </div>

                <div class="mt-8">
                    <a href="{{ route('club') }}" class="inline-flex items-center text-primary font-bold uppercase tracking-widest text-xs hover:gap-4 transition-all">
                        O KLUBU
                        <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Gallery Grid --}}
<section class="py-20 md:py-32 bg-surface overflow-hidden">
    <div class="container mx-auto px-6 md:px-8">
        <h2 class="font-headline text-4xl md:text-5xl font-extrabold text-center mb-12 md:mb-20 tracking-tight">Život v klubu</h2>
        <div class="grid grid-cols-2 md:grid-cols-12 md:grid-rows-2 gap-3 md:gap-4 h-auto md:h-[700px]">
            <div class="col-span-2 md:col-span-8 md:row-span-1 overflow-hidden rounded-lg aspect-video md:aspect-auto">
                <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-700" alt="Děti sedí v seiza na tatami" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDP1uKEMrmOH6LqlO6DumfQjjwTpPsP--0V1_eSEiHeYNhqCeLJK6AmF-wWy4u-_DY9FlRSUJQmoiQMSJPjI_e2VJxgJbnuk0kZGPqf29JRiUTkcOVlDHlOAPI9PtFwYMpmk2L1f8jdyAykOuvxgoPsKZdAYlXonEesjyEUUsYv0JJqqBtV7zfipR-RDr8_MvvW3z1Zij5Hdh2NinauxT82MQhxt7XjWAPGP8NPfVBEfSPAbd3r3P_7gM-ygvteQjteYN5ewU3bH_MG"/>
            </div>
            <div class="col-span-1 md:col-span-4 md:row-span-2 overflow-hidden rounded-lg aspect-[3/4] md:aspect-auto">
                <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-700" alt="Mladý judista" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJbBgvJyrRdLKIoWNIhJjiZu6ebM-RMTfkm_J_JmitcPHt5bq1LoTqBOifuL3-q04Dl3DQrZFHgnj41Ptyg02mR4SB3meNJQMizKZDbfHh4OZwiFBNeNmzY5UE10nRK7ARUw0JlNgFKTMyW_0_5O7f4fKfdnye8IPu-CKnfsor6aWPd70b37gxB3yfASnNH9ELPHEaEA7-d7ugVFygqEsl8tiBmtP1WlrXLWn5Zy7jkm5x4TOVnKySYSVyET74daXvRj_TxX-CXdaU"/>
            </div>
            <div class="col-span-1 md:col-span-4 md:row-span-1 overflow-hidden rounded-lg aspect-square md:aspect-auto">
                <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-700" alt="Judisté si podávají ruce" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDFxSduLo97VV71jBWIeATh_9SkQhbR60jxvY2c-4ZE9E1AG85-rKUzmh3jta6BwJ-2NuA5wsJAxUkD1PXronuE1C-Nt7kxLY3TdvD3u1H0JXyIn_5vqcFhRVMnTSCcoqtO-4ijjzX-tXBTh6P7log6ICCEJR13LKB6PmVzCdQ9eivO1X2DKQOQ-vzDLkQqVaK_-rDyp-D_Uu_lqkYX9fCYaAKPllO-jyrO_coP36rNq9G_l8Riax005p5SOQg2c5YN2KSti9G9RzQP"/>
            </div>
            <div class="col-span-2 md:col-span-4 md:row-span-1 overflow-hidden rounded-lg aspect-video md:aspect-auto">
                <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-700" alt="Hod při zápase" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_Di63uuaCObrU-pgsgyKWmhbWmIQqqMxpiyriFp4DUDpgGsRFlUfP62h6kZIAbAsLxanaiYL03C2gfOWFQXSgSLz-cui-k3oDE3R6ajpE_fcs1cNQWOwnBqdheYK6fa825Wc_cmxipdT3vS_ugsemXLnrziAETEuM-1hmTwVpd18YqVgUGq8mWKeVgfRIt5jry9OSDi-WRXdXm9CzLA3i8KGxgShNZdKwU7bIdz1aKBi_p9_ON6KTxcwMDZKe6ZqXKhJRHtPZ4NXk"/>
            </div>
        </div>
    </div>
</section>

{{-- Aktuality Preview --}}
<section class="py-20 md:py-32 bg-surface-container-low">
    <div class="container mx-auto px-6 md:px-8">
        <h2 class="font-headline text-4xl md:text-5xl font-extrabold text-on-surface mb-12 tracking-tight">Aktuality</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            @foreach($content['aktuality_links'] as $item)
                <a href="{{ $item['link'] }}" class="bg-surface-container-lowest p-8 md:p-10 rounded-lg hover:translate-y-[-4px] transition-all duration-300 group block">
                    <div class="text-primary mb-4">
                        <span class="material-symbols-outlined text-3xl">{{ $item['icon'] }}</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-on-surface group-hover:text-primary transition-colors">{{ $item['title'] }}</h3>
                    <p class="text-on-surface-variant leading-relaxed text-sm">{{ $item['description'] }}</p>
                    <span class="inline-flex items-center text-primary font-bold uppercase tracking-widest text-xs mt-6 group-hover:gap-3 transition-all">
                        ZOBRAZIT
                        <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA / Trial Section --}}
<section class="py-20 md:py-32 bg-primary overflow-hidden relative">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;6&quot; height=&quot;6&quot; viewBox=&quot;0 0 6 6&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.15&quot;%3E%3Cpath d=&quot;M5 0h1L0 6V5zM6 5v1H5z&quot;/%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="container mx-auto px-6 md:px-8 relative z-10">
        <div class="bg-surface p-8 md:p-16 lg:p-20 rounded-xl flex flex-col lg:flex-row gap-12 lg:gap-16 items-center">
            <div class="w-full lg:w-1/2">
                <h2 class="font-headline text-4xl md:text-5xl font-black text-primary mb-6 leading-tight">První trénink zdarma!</h2>
                <p class="text-lg md:text-xl text-on-surface-variant mb-10 leading-relaxed">
                    Nechte své dítě vyzkoušet atmosféru našeho dojo bez jakýchkoliv závazků. Stačí nám zavolat a domluvíme se.
                </p>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3 font-bold text-primary">
                        <span class="material-symbols-outlined">check_circle</span>
                        Zapůjčení gi (kimona) zdarma
                    </li>
                    <li class="flex items-center gap-3 font-bold text-primary">
                        <span class="material-symbols-outlined">check_circle</span>
                        Konzultace s trenérem
                    </li>
                    <li class="flex items-center gap-3 font-bold text-primary">
                        <span class="material-symbols-outlined">check_circle</span>
                        Bezpečné prostředí pro začátečníky
                    </li>
                </ul>
            </div>
            <div class="w-full lg:w-1/2 text-center">
                <div class="bg-surface-container-low p-8 md:p-12 rounded-xl">
                    <p class="font-headline text-2xl font-bold text-on-surface mb-4">Zavolejte nám</p>
                    <a href="tel:+420777166156" class="inline-flex items-center gap-3 text-3xl md:text-4xl font-headline font-black text-primary hover:text-primary-container transition-colors">
                        <span class="material-symbols-outlined text-4xl">call</span>
                        {{ $content['phone'] }}
                    </a>
                    <p class="text-on-surface-variant mt-4">{{ $content['kids_phone'] }}</p>
                    <div class="mt-8">
                        <a href="{{ route('contact') }}" class="bg-primary-container text-on-primary px-10 py-4 rounded-md font-bold uppercase tracking-widest text-sm hover:translate-y-[-2px] transition-transform inline-block">
                            Kontaktujte nás
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contact/Location Strip --}}
<section class="py-16 md:py-20 bg-surface">
    <div class="container mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 text-center">
            <div>
                <span class="material-symbols-outlined text-primary text-4xl mb-4">location_on</span>
                <h3 class="font-headline font-bold text-lg mb-2">HONBU DOJO INVALIDOVNA</h3>
                <p class="text-on-surface-variant">Praha 8</p>
            </div>
            <div>
                <span class="material-symbols-outlined text-primary text-4xl mb-4">call</span>
                <h3 class="font-headline font-bold text-lg mb-2">{{ $content['phone'] }}</h3>
                <p class="text-on-surface-variant">{{ $content['club_name'] }}</p>
            </div>
            <div>
                <span class="material-symbols-outlined text-primary text-4xl mb-4">language</span>
                <h3 class="font-headline font-bold text-lg mb-2">{{ $content['sebeobrana_web'] }}</h3>
                <p class="text-on-surface-variant">Kurzy sebeobrany</p>
            </div>
        </div>
    </div>
</section>
</div>
