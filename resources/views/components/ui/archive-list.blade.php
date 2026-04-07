@props([
    'items' => [],
])

{{-- $items is expected as an associative array keyed by year:
     ['2024' => [['title' => '...', 'date' => '...', 'url' => '...'], ...], '2023' => [...]]
--}}

@if(count($items) > 0)
<div class="flex flex-col gap-12">
    @foreach($items as $year => $entries)
    <div>
        {{-- Year heading --}}
        <div class="flex items-center gap-4 mb-6">
            <h3 class="font-headline text-3xl font-black text-primary tracking-tight">
                {{ $year }}
            </h3>
            <div class="flex-1 h-px bg-surface-container-high"></div>
            <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest">
                {{ count($entries) }} {{ count($entries) === 1 ? 'příspěvek' : (count($entries) < 5 ? 'příspěvky' : 'příspěvků') }}
            </span>
        </div>

        {{-- Entries --}}
        <ul class="flex flex-col gap-1">
            @foreach($entries as $entry)
            <li>
                <a
                    href="{{ $entry['url'] ?? '#' }}"
                    class="flex items-center gap-4 px-4 py-3 rounded hover:bg-surface-container-low transition-colors group"
                >
                    @if(!empty($entry['date']))
                    <time
                        datetime="{{ $entry['date'] }}"
                        class="shrink-0 text-xs font-semibold text-on-surface-variant uppercase tracking-wide w-20"
                    >
                        {{ \Carbon\Carbon::parse($entry['date'])->isoFormat('D. MMM') }}
                    </time>
                    @endif
                    <span class="flex-1 text-sm font-medium text-on-surface group-hover:text-primary transition-colors leading-snug">
                        {{ $entry['title'] ?? '' }}
                    </span>
                    <span class="material-symbols-outlined text-base text-on-surface-variant group-hover:text-primary transition-colors shrink-0">
                        arrow_forward
                    </span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endforeach
</div>
@else
<p class="text-on-surface-variant text-sm py-8 text-center">Žádné příspěvky nenalezeny.</p>
@endif
