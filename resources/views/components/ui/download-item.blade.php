@props([
    'title' => '',
    'url'   => null,
    'icon'  => 'description',
])

<div class="flex items-center gap-4 bg-surface-container-lowest rounded px-5 py-4">

    <span class="material-symbols-outlined text-2xl text-primary shrink-0">{{ $icon }}</span>

    <span class="flex-1 text-sm font-medium text-on-surface leading-snug">
        {{ $title }}
    </span>

    @if($url)
    <a
        href="{{ $url }}"
        download
        class="inline-flex items-center gap-1.5 px-4 py-2 rounded bg-surface-container text-on-surface text-xs font-semibold uppercase tracking-wide hover:bg-surface-container-high transition-colors shrink-0"
        aria-label="Stáhnout: {{ $title }}"
    >
        <span class="material-symbols-outlined text-base">download</span>
        Stáhnout
    </a>
    @else
    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded bg-surface-container-high text-on-surface-variant text-xs font-semibold uppercase tracking-wide opacity-50 shrink-0 cursor-not-allowed">
        <span class="material-symbols-outlined text-base">download</span>
        Nedostupné
    </span>
    @endif

</div>
