@props([
    'schedule' => [],
])

{{-- Mobile: cards --}}
<div class="md:hidden flex flex-col gap-3">
    @forelse($schedule as $entry)
    <div class="bg-surface-container-lowest rounded p-5 flex flex-col gap-2
        {{ ($entry['status'] ?? '') === 'paused' ? 'opacity-60' : '' }}">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="font-bold text-on-surface">{{ $entry['name'] ?? '' }}</p>
                <p class="text-sm text-on-surface-variant mt-0.5">{{ $entry['day'] ?? '' }} &bull; {{ $entry['time'] ?? '' }}</p>
                @if(!empty($entry['location']))
                <p class="text-sm text-on-surface-variant flex items-center gap-1 mt-1">
                    <span class="material-symbols-outlined text-base">location_on</span>
                    {{ $entry['location'] }}
                </p>
                @endif
            </div>
            @if(($entry['status'] ?? '') === 'paused')
            <span class="shrink-0 inline-block px-2 py-1 bg-error text-on-error text-xs font-bold uppercase tracking-wide rounded leading-tight text-center">
                Pozastaveno
            </span>
            @else
            <span class="shrink-0 inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-xs font-medium uppercase tracking-wide rounded">
                Aktivní
            </span>
            @endif
        </div>
        @if(($entry['status'] ?? '') === 'paused')
        <p class="text-xs font-bold text-error uppercase tracking-wide">DOČASNĚ POZASTAVENO!!!</p>
        @endif
    </div>
    @empty
    <p class="text-on-surface-variant text-sm py-4">Žádné tréninky nenalezeny.</p>
    @endforelse
</div>

{{-- Desktop: table --}}
<div class="hidden md:block overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-surface-container text-on-surface-variant">
                <th class="px-5 py-3 text-left font-semibold uppercase tracking-wide text-xs">Den</th>
                <th class="px-5 py-3 text-left font-semibold uppercase tracking-wide text-xs">Čas</th>
                <th class="px-5 py-3 text-left font-semibold uppercase tracking-wide text-xs">Trénink</th>
                <th class="px-5 py-3 text-left font-semibold uppercase tracking-wide text-xs">Místo</th>
                <th class="px-5 py-3 text-left font-semibold uppercase tracking-wide text-xs">Stav</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedule as $index => $entry)
            <tr class="transition-colors
                {{ $index % 2 === 0 ? 'bg-surface-container-lowest' : 'bg-surface-container-low' }}
                {{ ($entry['status'] ?? '') === 'paused' ? 'opacity-60' : 'hover:bg-surface-container' }}">
                <td class="px-5 py-4 font-medium text-on-surface">{{ $entry['day'] ?? '' }}</td>
                <td class="px-5 py-4 text-on-surface-variant">{{ $entry['time'] ?? '' }}</td>
                <td class="px-5 py-4">
                    <span class="font-semibold text-on-surface">{{ $entry['name'] ?? '' }}</span>
                    @if(($entry['status'] ?? '') === 'paused')
                    <p class="text-xs font-bold text-error uppercase tracking-wide mt-0.5">DOČASNĚ POZASTAVENO!!!</p>
                    @endif
                </td>
                <td class="px-5 py-4 text-on-surface-variant">{{ $entry['location'] ?? '' }}</td>
                <td class="px-5 py-4">
                    @if(($entry['status'] ?? '') === 'paused')
                    <span class="inline-block px-2.5 py-1 bg-error text-on-error text-xs font-bold uppercase tracking-wide rounded">
                        Pozastaveno
                    </span>
                    @else
                    <span class="inline-block px-2.5 py-1 bg-surface-container-high text-on-surface-variant text-xs font-medium uppercase tracking-wide rounded">
                        Aktivní
                    </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-8 text-center text-on-surface-variant">
                    Žádné tréninky nenalezeny.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
