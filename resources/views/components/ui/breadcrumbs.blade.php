@props(['items' => []])

@if(count($items) > 0)
<nav aria-label="Breadcrumb" class="bg-surface-container-low py-3 px-8">
    <ol class="flex items-center flex-wrap gap-1 text-sm text-on-surface-variant">
        @foreach($items as $index => $item)
            @if($index > 0)
                <li aria-hidden="true" class="select-none opacity-50">
                    <span class="material-symbols-outlined text-base leading-none align-middle">chevron_right</span>
                </li>
            @endif
            <li>
                @if(isset($item['url']))
                    <a
                        href="{{ $item['url'] }}"
                        class="hover:text-primary transition-colors"
                    >{{ $item['label'] }}</a>
                @else
                    <span class="text-on-surface font-medium" aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
