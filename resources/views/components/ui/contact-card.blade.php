@props([
    'name'     => '',
    'role'     => null,
    'phone'    => null,
    'location' => null,
    'details'  => [],
])

<div class="bg-surface-container-lowest rounded p-8 flex flex-col gap-5">

    <div>
        <h3 class="font-headline text-xl font-bold text-on-surface tracking-tight">
            {{ $name }}
        </h3>
        @if($role)
        <p class="text-sm text-primary font-semibold mt-1">{{ $role }}</p>
        @endif
    </div>

    <ul class="flex flex-col gap-3">
        @if($phone)
        <li class="flex items-center gap-3">
            <span class="material-symbols-outlined text-xl text-primary shrink-0">call</span>
            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">
                {{ $phone }}
            </a>
        </li>
        @endif

        @if($location)
        <li class="flex items-start gap-3">
            <span class="material-symbols-outlined text-xl text-primary mt-0.5 shrink-0">location_on</span>
            <span class="text-sm text-on-surface-variant">{{ $location }}</span>
        </li>
        @endif

        @foreach($details as $detail)
        <li class="flex items-start gap-3">
            <span class="material-symbols-outlined text-xl text-primary mt-0.5 shrink-0">{{ $detail['icon'] ?? 'info' }}</span>
            @if(!empty($detail['url']))
            <a href="{{ $detail['url'] }}" class="text-sm text-on-surface-variant hover:text-primary transition-colors">
                {{ $detail['label'] ?? '' }}
            </a>
            @else
            <span class="text-sm text-on-surface-variant">{{ $detail['label'] ?? '' }}</span>
            @endif
        </li>
        @endforeach
    </ul>

</div>
