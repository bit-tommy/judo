<?php

use App\Models\ScheduleOverride;
use Illuminate\Support\Carbon;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.admin')]
#[Title('Rozvrh | Administrace JC Raion-Ryu')]
class extends Component {
    private const CZ_DAYS = [1 => 'Pondělí', 2 => 'Úterý', 3 => 'Středa', 4 => 'Čtvrtek', 5 => 'Pátek', 6 => 'Sobota', 7 => 'Neděle'];

    /** Modal „Zrušit trénink". */
    public bool $showCancel = false;

    public string $cancelDate = '';

    public ?int $cancelIndex = null;

    /** Modal „Přidat trénink". */
    public bool $showExtra = false;

    public string $extraDate = '';

    public string $extraType = 'Judo';

    public string $extraPlace = '';

    public string $extraLoc = '';

    public string $timeFrom = '';

    public string $timeTo = '';

    public string $extraForm = '';

    public function openCancel(): void
    {
        $this->resetForms();
        $this->showCancel = true;
    }

    public function openExtra(): void
    {
        $this->resetForms();
        $this->showExtra = true;
    }

    public function closeModals(): void
    {
        $this->showCancel = false;
        $this->showExtra = false;
    }

    /** Pravidelné lekce pro zvolené datum (k výběru při rušení). */
    public function sessionsForCancelDate(): array
    {
        if ($this->cancelDate === '') {
            return [];
        }

        try {
            $date = Carbon::parse($this->cancelDate);
        } catch (\Throwable) {
            return [];
        }

        $sessions = config('content.schedule.days.'.$date->dayOfWeekIso, []);

        // Už zrušené lekce v daný den znovu nenabízíme.
        $cancelled = ScheduleOverride::cancellations()
            ->whereDate('date', $this->cancelDate)->get();

        return collect($sessions)
            ->reject(fn (array $s) => $cancelled->contains(
                fn ($o) => $o->form === $s['form'] && $o->time === $s['time'],
            ))
            ->all();
    }

    public function saveCancel(): void
    {
        $this->validate(
            ['cancelDate' => 'required|date|after_or_equal:today', 'cancelIndex' => 'required|integer'],
            [
                'cancelDate.required' => 'Vyberte prosím datum.',
                'cancelDate.after_or_equal' => 'Rušit lze jen dnešní a budoucí tréninky.',
                'cancelIndex.required' => 'Vyberte prosím, který trénink se ruší.',
            ],
        );

        $session = $this->sessionsForCancelDate()[$this->cancelIndex] ?? null;

        if ($session === null) {
            $this->addError('cancelIndex', 'V tento den žádný takový trénink není.');

            return;
        }

        ScheduleOverride::create([
            'date' => $this->cancelDate,
            'kind' => ScheduleOverride::KIND_CANCELLED,
            ...$session,
        ]);

        $this->closeModals();
        $this->resetForms();
        $this->dispatch('toast', message: 'Trénink byl zrušen — na webu se zobrazí přeškrtnutě.');
    }

    public function saveExtra(): void
    {
        $this->validate(
            [
                'extraDate' => 'required|date|after_or_equal:today',
                'extraType' => 'required|string|max:60',
                'extraPlace' => 'nullable|string|max:120',
                'extraLoc' => 'nullable|string|max:160',
                'timeFrom' => 'required|date_format:H:i',
                'timeTo' => 'required|date_format:H:i|after:timeFrom',
                'extraForm' => 'nullable|string',
            ],
            [
                'extraDate.required' => 'Vyberte prosím datum.',
                'extraDate.after_or_equal' => 'Přidat lze jen dnešní a budoucí tréninky.',
                'extraType.required' => 'Vyplňte prosím typ tréninku.',
                'timeFrom.required' => 'Vyplňte prosím začátek.',
                'timeFrom.date_format' => 'Začátek zadejte ve formátu HH:MM.',
                'timeTo.required' => 'Vyplňte prosím konec.',
                'timeTo.date_format' => 'Konec zadejte ve formátu HH:MM.',
                'timeTo.after' => 'Konec musí být po začátku.',
            ],
        );

        ScheduleOverride::create([
            'date' => $this->extraDate,
            'kind' => ScheduleOverride::KIND_EXTRA,
            'type' => $this->extraType,
            'place' => $this->extraPlace !== '' ? $this->extraPlace : null,
            'loc' => $this->extraLoc !== '' ? $this->extraLoc : null,
            'time' => $this->timeFrom.'–'.$this->timeTo,
            'form' => $this->extraForm !== '' ? $this->extraForm : null,
        ]);

        $this->closeModals();
        $this->resetForms();
        $this->dispatch('toast', message: 'Mimořádný trénink byl přidán do kalendáře.');
    }

    public function deleteOverride(int $overrideId): void
    {
        $override = ScheduleOverride::findOrFail($overrideId);
        $wasCancellation = $override->isCancellation();
        $override->delete();

        $this->dispatch('toast', message: $wasCancellation
            ? 'Zrušení bylo odvoláno — trénink zase platí.'
            : 'Mimořádný trénink byl odebrán.');
    }

    public function czDate(Carbon $date): string
    {
        return self::CZ_DAYS[$date->dayOfWeekIso].' '.$date->day.'. '.$date->month.'. '.$date->year;
    }

    private function resetForms(): void
    {
        $this->reset(['cancelDate', 'cancelIndex', 'extraDate', 'extraPlace', 'extraLoc', 'timeFrom', 'timeTo', 'extraForm']);
        $this->extraType = 'Judo';
        $this->resetValidation();
    }

    public function with(): array
    {
        // „Obecný dotaz" není trénink — do mapování mimořádného tréninku nepatří.
        $formOptions = array_values(array_filter(
            config('content.schedule.form_options', []),
            fn (string $option) => $option !== 'Obecný dotaz',
        ));

        return [
            'schedule' => config('content.schedule.days', []),
            'dayNames' => array_slice(self::CZ_DAYS, 0, 5, true),
            'overrides' => ScheduleOverride::upcoming()->orderBy('time')->get(),
            'formOptions' => $formOptions,
        ];
    }
}; ?>

<section class="panel">
  <div class="main-head">
    <div>
      <div class="eyebrow reveal" style="--i: 0">Rozvrh tréninků</div>
      <h1 class="main-title reveal" style="--i: 1">Týden na tatami</h1>
    </div>
    <div class="head-actions reveal" style="--i: 2">
      <button type="button" class="btn ghost" wire:click="openCancel">Zrušit trénink</button>
      <button type="button" class="btn" wire:click="openExtra">+ Přidat trénink</button>
    </div>
  </div>

  <div class="week reveal" style="--i: 3">
    @foreach ($dayNames as $iso => $dayName)
      <div class="day">
        <div class="day-head"><span class="day-name">{{ $dayName }}</span></div>
        <div class="day-body">
          @forelse ($schedule[$iso] ?? [] as $session)
            <div class="slot {{ $session['type'] === 'Judo' ? 'r' : 'd' }}">
              <div class="slot-time">{{ $session['time'] }}</div>
              <div class="slot-name">{{ $session['type'] }} — {{ $session['place'] }}</div>
              <div class="slot-meta">{{ $session['loc'] }}</div>
            </div>
          @empty
            <div class="day-empty">— volno —</div>
          @endforelse
        </div>
      </div>
    @endforeach
  </div>

  <div class="sched-legend reveal" style="--i: 4">
    <span class="leg"><i style="background: var(--red);"></i>Judo</span>
    <span class="leg"><i style="background: var(--ink);"></i>Taijutsu</span>
  </div>

  {{-- ─── Jednorázové změny ─── --}}
  <h2 class="section-sub reveal" style="--i: 5">Jednorázové změny <span class="cnt">zrušené a mimořádné tréninky</span></h2>

  @if ($overrides->isEmpty())
    <div class="empty-note reveal" style="--i: 6">Žádné nadcházející změny — platí pravidelný rozvrh.</div>
  @else
    <ul class="docs reveal" style="--i: 6">
      @foreach ($overrides as $override)
        <li class="doc" wire:key="override-{{ $override->id }}">
          <span class="doc-ext">{{ $override->isCancellation() ? '✕' : '+' }}</span>
          <div class="doc-name">
            {{ $this->czDate($override->date) }}
            <span>{{ $override->sessionLabel() }}{{ $override->loc ? ' · '.$override->loc : '' }}</span>
          </div>
          <span class="tag {{ $override->isCancellation() ? 'red' : 'dark' }}">{{ $override->isCancellation() ? 'Zrušeno' : 'Mimořádný' }}</span>
          <div class="doc-actions">
            <button type="button" class="btn subtle" wire:click="deleteOverride({{ $override->id }})"
                    wire:confirm="{{ $override->isCancellation() ? 'Odvolat zrušení? Trénink bude zase platit.' : 'Odebrat mimořádný trénink z kalendáře?' }}">
              {{ $override->isCancellation() ? 'Odvolat zrušení' : 'Odebrat' }}
            </button>
          </div>
        </li>
      @endforeach
    </ul>
  @endif

  <p class="reveal" style="--i: 7; margin-top: 28px; font-size: 12.5px; color: var(--ink-light); max-width: 640px; line-height: 1.7;">
    Zrušený trénink zůstane v kalendáři na webu vidět přeškrtnutě a nepůjde na něj objednat.
    Mimořádný trénink se v kalendáři objeví navíc. Pravidelný týdenní rozvrh
    je jednotný zdroj pro web i formulář — jeho trvalou změnu zajistí správce webu.
  </p>

  {{-- ─── Zrušit trénink ─── --}}
  @if ($showCancel)
    <div class="modal-bg"
         x-data="{ open: false }"
         x-init="setTimeout(() => $data.open = true)"
         :class="{ open }"
         @click.self="$wire.closeModals()"
         @keydown.escape.window="$wire.closeModals()">
      <div class="modal">
        <div class="modal-band"></div>
        <button type="button" class="modal-close" aria-label="Zavřít" wire:click="closeModals">×</button>
        <div class="modal-inner">
          <div class="eyebrow">Zrušit trénink</div>
          <h3>Jednorázové zrušení</h3>
          <form wire:submit="saveCancel">
            <div class="field">
              <label for="c-date">Datum</label>
              <input type="date" id="c-date" wire:model.live="cancelDate" min="{{ today()->toDateString() }}">
              <div class="field-bar"></div>
              @error('cancelDate') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            @if ($cancelDate !== '')
              @php $sessions = $this->sessionsForCancelDate(); @endphp
              @if (empty($sessions))
                <p style="margin: 0 0 22px; font-size: 12.5px; color: var(--ink-light);">V tento den není žádný pravidelný trénink, který by šlo zrušit.</p>
              @else
                <div class="field">
                  <label>Který trénink se ruší?</label>
                  <div style="display: flex; flex-direction: column; gap: 4px; padding-top: 6px;">
                    @foreach ($sessions as $index => $session)
                      <label class="check" style="margin-bottom: 2px;">
                        <input type="radio" name="cancel-session" wire:model="cancelIndex" value="{{ $index }}">
                        {{ $session['type'] }} — {{ $session['place'] }} · {{ $session['time'] }}
                      </label>
                    @endforeach
                  </div>
                  @error('cancelIndex') <span class="field-error">{{ $message }}</span> @enderror
                </div>
              @endif
            @endif

            <div class="modal-actions">
              <button type="submit" class="btn">Zrušit trénink</button>
              <button type="button" class="btn ghost" wire:click="closeModals">Zavřít</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  {{-- ─── Přidat trénink ─── --}}
  @if ($showExtra)
    <div class="modal-bg"
         x-data="{ open: false }"
         x-init="setTimeout(() => $data.open = true)"
         :class="{ open }"
         @click.self="$wire.closeModals()"
         @keydown.escape.window="$wire.closeModals()">
      <div class="modal">
        <div class="modal-band"></div>
        <button type="button" class="modal-close" aria-label="Zavřít" wire:click="closeModals">×</button>
        <div class="modal-inner">
          <div class="eyebrow">Mimořádný trénink</div>
          <h3>Přidat trénink</h3>
          <form wire:submit="saveExtra">
            <div class="form-row">
              <div class="field">
                <label for="x-date">Datum</label>
                <input type="date" id="x-date" wire:model="extraDate" min="{{ today()->toDateString() }}">
                <div class="field-bar"></div>
                @error('extraDate') <span class="field-error">{{ $message }}</span> @enderror
              </div>
              <div class="field">
                <label for="x-type">Typ</label>
                <input type="text" id="x-type" wire:model="extraType" placeholder="Judo / Taijutsu / Randori…" list="x-type-list">
                <datalist id="x-type-list">
                  <option value="Judo"></option>
                  <option value="Taijutsu"></option>
                  <option value="Randori"></option>
                </datalist>
                <div class="field-bar"></div>
                @error('extraType') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="form-row">
              <div class="field">
                <label for="x-from">Od</label>
                <input type="time" id="x-from" wire:model="timeFrom">
                <div class="field-bar"></div>
                @error('timeFrom') <span class="field-error">{{ $message }}</span> @enderror
              </div>
              <div class="field">
                <label for="x-to">Do</label>
                <input type="time" id="x-to" wire:model="timeTo">
                <div class="field-bar"></div>
                @error('timeTo') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="form-row">
              <div class="field">
                <label for="x-place">Místo</label>
                <input type="text" id="x-place" wire:model="extraPlace" placeholder="Praha 8">
                <div class="field-bar"></div>
              </div>
              <div class="field">
                <label for="x-loc">Adresa / upřesnění</label>
                <input type="text" id="x-loc" wire:model="extraLoc" placeholder="Za Invalidovnou 579/3">
                <div class="field-bar"></div>
              </div>
            </div>
            <div class="field">
              <label for="x-form">Objednávání ve formuláři</label>
              <select id="x-form" wire:model="extraForm">
                <option value="">— bez objednávání —</option>
                @foreach ($formOptions as $option)
                  <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
              </select>
            </div>
            <div class="modal-actions">
              <button type="submit" class="btn">Přidat trénink</button>
              <button type="button" class="btn ghost" wire:click="closeModals">Zrušit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</section>
