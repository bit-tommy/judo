<?php

use App\Models\Event;
use App\Models\Inquiry;
use App\Models\Member;
use App\Models\ScheduleOverride;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.admin')]
#[Title('Přehled | Administrace JC Raion-Ryu')]
class extends Component {
    private const CZ_DAYS = ['', 'Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota', 'Neděle'];

    private const CZ_DAYS_SHORT = ['', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So', 'Ne'];

    public function markHandled(int $inquiryId): void
    {
        Inquiry::findOrFail($inquiryId)->update(['handled_at' => now()]);

        $this->dispatch('toast', message: 'Poptávka byla označena jako vyřízená.');
    }

    public function with(): array
    {
        // Klubová sezóna začíná 1. září.
        $seasonStart = now()->month >= 9
            ? Carbon::create(now()->year, 9, 1)
            : Carbon::create(now()->year - 1, 9, 1);

        $mainEvent = Event::where('is_main', true)->upcoming()->first()
            ?? Event::upcoming()->first();

        return [
            'activeCount' => Member::active()->count(),
            'sinceSeason' => Member::active()->where('created_at', '>=', $seasonStart)->count(),
            'pendingCount' => Inquiry::unhandled()->count(),
            'weeklyTrainings' => collect(config('content.schedule.days', []))->flatten(1)->count(),
            'mainEvent' => $mainEvent,
            'upcomingTrainings' => $this->upcomingTrainings(),
            'newInquiries' => Inquiry::unhandled()->latest()->take(5)->get(),
            'todayLabel' => self::CZ_DAYS[now()->dayOfWeekIso].' · '.now()->day.'. '.now()->month.'. '.now()->year,
        ];
    }

    /**
     * Nejbližší 3 tréninky odvozené ze sdíleného rozvrhu — bez zrušených,
     * včetně mimořádných (výjimky spravuje sekce Rozvrh).
     *
     * @return array<int, array{date: Carbon, isToday: bool, type: string, place: ?string, loc: ?string, time: string}>
     */
    private function upcomingTrainings(): array
    {
        $schedule = config('content.schedule.days', []);
        $overrides = ScheduleOverride::whereBetween('date', [today(), today()->addDays(14)])->get();
        $trainings = [];
        $cursor = today();

        for ($i = 0; $i < 14 && count($trainings) < 3; $i++) {
            $daily = collect($schedule[$cursor->dayOfWeekIso] ?? [])
                ->reject(fn (array $session) => $overrides->contains(
                    fn (ScheduleOverride $o) => $o->isCancellation()
                        && $o->date->isSameDay($cursor)
                        && $o->form === $session['form']
                        && $o->time === $session['time'],
                ))
                ->concat(
                    $overrides
                        ->filter(fn (ScheduleOverride $o) => ! $o->isCancellation() && $o->date->isSameDay($cursor))
                        ->map(fn (ScheduleOverride $o) => [
                            'type' => $o->type,
                            'place' => $o->place,
                            'loc' => $o->loc,
                            'time' => $o->time,
                            'form' => $o->form,
                        ]),
                )
                ->sortBy(fn (array $session) => Str::before($session['time'], '–'))
                ->values();

            foreach ($daily as $session) {
                if (count($trainings) >= 3) {
                    break;
                }

                $trainings[] = [
                    'date' => $cursor->copy(),
                    'isToday' => $cursor->isToday(),
                    ...$session,
                ];
            }

            $cursor->addDay();
        }

        return $trainings;
    }

    public function dayShort(Carbon $date): string
    {
        return self::CZ_DAYS_SHORT[$date->dayOfWeekIso];
    }

    public function dayLong(Carbon $date): string
    {
        return self::CZ_DAYS[$date->dayOfWeekIso];
    }
}; ?>

<section class="panel">
  <div class="main-head">
    <div>
      <div class="eyebrow reveal" style="--i: 0">Dashboard</div>
      <h1 class="main-title reveal" style="--i: 1">Přehled klubu</h1>
    </div>
    <div class="main-date reveal" style="--i: 2">{{ $todayLabel }}</div>
  </div>

  <div class="stats reveal" style="--i: 3">
    <div class="stat">
      <div class="stat-label">Aktivní členové</div>
      <div class="stat-num">{{ $activeCount }}</div>
      <div class="stat-sub">@if ($sinceSeason > 0)<em>+{{ $sinceSeason }}</em> od září@else evidence členů @endif</div>
    </div>
    <div class="stat">
      <div class="stat-label">Nové poptávky</div>
      <div class="stat-num">{{ $pendingCount }}</div>
      <div class="stat-sub">{{ $pendingCount === 0 ? 'vše vyřízeno' : 'čekají na vyřízení' }}</div>
    </div>
    <div class="stat">
      <div class="stat-label">Tréninků týdně</div>
      <div class="stat-num">{{ $weeklyTrainings }}</div>
      <div class="stat-sub">po — st · judo &amp; taijutsu</div>
    </div>
    <div class="stat">
      <div class="stat-label">Dní do hlavní akce</div>
      <div class="stat-num">{{ $mainEvent?->daysUntil() ?? '—' }}</div>
      <div class="stat-sub">{{ $mainEvent ? Str::lower($mainEvent->title) : 'žádná plánovaná akce' }}</div>
    </div>
  </div>

  <div class="dash-grid">
    <div>
      <div class="card reveal" style="--i: 4">
        <div class="card-head">
          <div class="card-title">Nejbližší tréninky</div>
          <a class="card-link" href="{{ route('admin.schedule') }}" wire:navigate>Celý rozvrh →</a>
        </div>
        <ul class="row-list">
          @forelse ($upcomingTrainings as $training)
            <li>
              <span class="row-time">{{ $training['isToday'] ? 'Dnes' : $this->dayShort($training['date']) }} {{ Str::before($training['time'], '–') }}</span>
              <div class="row-main">
                <div class="row-title">{{ $training['type'] }} — {{ $training['place'] }}</div>
                <div class="row-sub">{{ $training['loc'] }} · {{ $training['time'] }}</div>
              </div>
              <span class="tag {{ $training['isToday'] ? 'red' : 'faint' }}">{{ $training['isToday'] ? 'Dnes' : $this->dayLong($training['date']) }}</span>
            </li>
          @empty
            <li><div class="row-main"><div class="row-sub">Rozvrh je prázdný.</div></div></li>
          @endforelse
        </ul>
      </div>

      <div class="card reveal" style="--i: 5; margin-top: 26px;">
        <div class="card-head">
          <div class="card-title">Nové poptávky z webu</div>
          <a class="card-link" href="{{ route('admin.members') }}" wire:navigate>Všichni členové →</a>
        </div>
        @if ($newInquiries->isEmpty())
          <div class="empty-note" style="border: none;">Žádné nové poptávky — vše vyřízeno.</div>
        @else
          <ul class="row-list">
            @foreach ($newInquiries as $inquiry)
              <li wire:key="inq-{{ $inquiry->id }}">
                <span class="row-time">
                  @if ($inquiry->created_at->isToday()) Dnes
                  @elseif ($inquiry->created_at->isYesterday()) Včera
                  @else {{ $inquiry->created_at->day }}. {{ $inquiry->created_at->month }}.
                  @endif
                </span>
                <div class="row-main">
                  <div class="row-title">{{ $inquiry->name }}</div>
                  <div class="row-sub">
                    {{ $inquiry->training_type }}
                    @if ($inquiry->preferred_date) · {{ $inquiry->preferred_date->day }}. {{ $inquiry->preferred_date->month }}. {{ $inquiry->preferred_date->year }} @endif
                    @if ($inquiry->phone) · {{ $inquiry->phone }} @endif
                  </div>
                </div>
                <span class="tag red">Nová</span>
                <span class="doc-actions">
                  <a class="btn subtle" href="{{ route('admin.members', ['from_inquiry' => $inquiry->id]) }}" wire:navigate>Založit člena</a>
                  <button type="button" class="btn subtle" wire:click="markHandled({{ $inquiry->id }})">Vyřízeno</button>
                </span>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>

    <div class="card dark reveal" style="--i: 6">
      <div class="inner">
        <div class="eyebrow">Nejbližší akce</div>
        @if ($mainEvent)
          <h3>{!! nl2br(e(Str::of($mainEvent->title)->replace(' & ', " &\n"))) !!}</h3>
          <p>{{ $mainEvent->dateRange() }}@if ($mainEvent->place) · {{ $mainEvent->place }}@endif{{ $mainEvent->description ? ' — '.Str::limit($mainEvent->description, 120) : '' }}</p>
          <div class="count"><strong>{{ $mainEvent->daysUntil() }}</strong> dní zbývá</div>
          <div style="margin-top: 26px;">
            <a class="btn ghost" href="{{ route('admin.events') }}" wire:navigate style="border-color: rgba(255,255,255,.35); color: #fff;">Detail akce</a>
          </div>
        @else
          <h3>Žádná plánovaná akce</h3>
          <p>Přidejte první akci v sekci Akce — objeví se tady i na veřejném webu.</p>
          <div style="margin-top: 26px;">
            <a class="btn ghost" href="{{ route('admin.events') }}" wire:navigate style="border-color: rgba(255,255,255,.35); color: #fff;">Přidat akci</a>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
