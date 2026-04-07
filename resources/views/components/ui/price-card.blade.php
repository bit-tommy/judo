@props([
    'title'       => '',
    'price'       => '',
    'period'      => '',
    'features'    => [],
    'highlighted' => false,
])

<div class="rounded p-8 flex flex-col gap-6 h-full
    {{ $highlighted
        ? 'bg-primary-container text-on-primary'
        : 'bg-surface-container-lowest text-on-surface' }}">

    <div>
        <h3 class="font-headline text-xl font-bold tracking-tight mb-1">
            {{ $title }}
        </h3>
        @if($highlighted)
        <span class="inline-block text-xs font-bold uppercase tracking-widest opacity-70">Doporučujeme</span>
        @endif
    </div>

    <div class="flex items-end gap-2">
        <span class="font-headline text-4xl font-black tracking-tight leading-none">
            {{ $price }}
        </span>
        @if($period)
        <span class="text-sm pb-1 {{ $highlighted ? 'opacity-70' : 'text-on-surface-variant' }}">
            / {{ $period }}
        </span>
        @endif
    </div>

    @if(count($features) > 0)
    <ul class="flex flex-col gap-3 flex-1">
        @foreach($features as $feature)
        <li class="flex items-start gap-2 text-sm">
            <span class="material-symbols-outlined text-base mt-0.5 shrink-0
                {{ $highlighted ? 'text-on-primary' : 'text-primary' }}">check_circle</span>
            <span class="{{ $highlighted ? 'text-on-primary' : 'text-on-surface-variant' }}">{{ $feature }}</span>
        </li>
        @endforeach
    </ul>
    @endif

    <a
        href="/kontakt"
        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded text-sm font-semibold uppercase tracking-wide transition-all mt-auto
            {{ $highlighted
                ? 'bg-on-primary text-primary hover:opacity-90'
                : 'bg-primary text-on-primary hover:opacity-90' }}"
    >
        Začít trénovat
        <span class="material-symbols-outlined text-base">arrow_forward</span>
    </a>

</div>
