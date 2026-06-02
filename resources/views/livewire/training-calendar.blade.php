<?php
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Mail\TrainingInquiry;
use App\Models\Inquiry;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $trainingType = '';
    public string $date = '';
    public string $message = '';
    public bool $consent = false;
    public bool $sent = false;

    /** Bookable training types offered in the form select. */
    public array $trainingOptions = [
        'Obecný dotaz',
        'Judo – Praha 8',
        'Judo – Vodochody',
        'Taijutsu – Praha 8',
    ];

    /**
     * Dny v týdnu (ISO: 1 = pondělí … 7 = neděle), kdy daný trénink probíhá.
     * Musí odpovídat rozvrhu v Alpine kalendáři níže (schedule).
     * „Obecný dotaz“ zde záměrně chybí → nemá výběr termínu.
     */
    protected array $trainingDays = [
        'Judo – Praha 8'     => [1, 3],
        'Judo – Vodochody'   => [1, 2],
        'Taijutsu – Praha 8' => [1, 3],
    ];

    private const CZ_DAYS = ['pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota', 'neděle'];

    /**
     * Nejbližší tréninkové termíny pro zvolený typ tréninku (na 8 týdnů dopředu).
     * Klíč = Y-m-d (hodnota selectu), hodnota = český popisek.
     *
     * @return array<string, string>
     */
    public function availableDates(): array
    {
        $days = $this->trainingDays[$this->trainingType] ?? [];

        if (empty($days)) {
            return [];
        }

        $dates = [];
        $cursor = Carbon::today();
        $end = Carbon::today()->addWeeks(8);

        while ($cursor <= $end) {
            if (in_array($cursor->dayOfWeekIso, $days, true)) {
                $dates[$cursor->format('Y-m-d')] = $this->czDate($cursor);
            }
            $cursor->addDay();
        }

        // Termín vybraný z kalendáře může být i za horizontem – doplníme ho.
        if ($this->date !== '' && ! isset($dates[$this->date])) {
            try {
                $picked = Carbon::parse($this->date);
                if (in_array($picked->dayOfWeekIso, $days, true)) {
                    $dates[$this->date] = $this->czDate($picked);
                    ksort($dates);
                }
            } catch (\Throwable) {
                // neplatné datum ignorujeme
            }
        }

        return $dates;
    }

    private function czDate(Carbon $d): string
    {
        return self::CZ_DAYS[$d->dayOfWeekIso - 1].' '.$d->day.'. '.$d->month.'. '.$d->year;
    }

    /** Při změně typu tréninku zahodíme termín, který už pro nový typ neplatí. */
    public function updatedTrainingType(): void
    {
        if ($this->date !== '' && ! isset($this->availableDates()[$this->date])) {
            $this->date = '';
        }
    }

    public function save(): void
    {
        $validated = $this->validate(
            [
                'name'         => 'required|string|max:120',
                'email'        => 'required|email|max:160',
                'phone'        => 'nullable|string|max:40',
                'trainingType' => ['required', 'string', Rule::in($this->trainingOptions)],
                'date'         => ['nullable', 'date', Rule::in(array_keys($this->availableDates()))],
                'message'      => 'nullable|string|max:2000',
                'consent'      => 'accepted',
            ],
            [
                'name.required'     => 'Vyplňte prosím jméno.',
                'email.required'    => 'Vyplňte prosím e-mail.',
                'email.email'       => 'Zadejte platnou e-mailovou adresu.',
                'trainingType.required' => 'Vyberte prosím typ tréninku nebo dotazu.',
                'trainingType.in'   => 'Vyberte prosím typ tréninku ze seznamu.',
                'date.date'         => 'Zadejte prosím platné datum.',
                'date.in'           => 'Vyberte prosím termín z nabízených tréninkových dnů.',
                'consent.accepted'  => 'Bez souhlasu se zpracováním údajů nemůžeme zprávu odeslat.',
            ],
        );

        // Poptávku vždy uložíme do databáze – nic se neztratí, i když SMTP
        // zatím není nastavené. Viz config 'mail.inquiries_enabled'.
        $inquiry = Inquiry::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'phone'          => filled($validated['phone'] ?? null) ? $validated['phone'] : null,
            'training_type'  => $validated['trainingType'],
            'preferred_date' => filled($validated['date'] ?? null) ? $validated['date'] : null,
            'message'        => filled($validated['message'] ?? null) ? $validated['message'] : null,
        ]);

        // Odešleme jen pokud je doručování zapnuté (tj. máme funkční SMTP).
        // Mailable je ShouldQueue → zařadí se do fronty (jobs), neblokuje formulář.
        // Případnou chybu odeslání (SMTP/fronta) jen zalogujeme – poptávka už je
        // bezpečně v DB a odešle se později přes `inquiries:send-pending`.
        if (config('mail.inquiries_enabled')) {
            try {
                Mail::to(config('mail.inquiries_to'))->send(new TrainingInquiry($inquiry->toMailData()));
                $inquiry->forceFill(['sent_at' => now()])->save();
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $this->reset(['name', 'email', 'phone', 'trainingType', 'date', 'message', 'consent']);
        $this->sent = true;
    }
}; ?>

<section id="rozvrh" class="schedule">
  <div class="section-eyebrow">Rozvrh tréninků</div>
  <h2 class="section-title">Kdy a kde trénujeme</h2>
  <p class="schedule-intro">
    Tréninky probíhají pravidelně každý týden. Najeďte myší nebo klikněte na zvýrazněný den
    a uvidíte, kde a co se ten den trénuje. Z detailu se můžete rovnou objednat na trénink.
  </p>

  <div class="schedule-layout" x-data="trainingCalendar()">
    {{-- ─── CALENDAR ─── --}}
    <div class="calendar">
      <div class="calendar-head">
        <button type="button" class="calendar-arrow" @click="prev()" aria-label="Předchozí měsíc">&lsaquo;</button>
        <button type="button" class="calendar-arrow" @click="next()" aria-label="Další měsíc">&rsaquo;</button>
      </div>
      <div class="calendar-months">
        <template x-for="m in months" :key="m.label">
          <div class="calendar-month">
            <h3 class="calendar-month-title" x-text="m.label"></h3>
            <div class="calendar-dow">
              <span>Po</span><span>Út</span><span>St</span><span>Čt</span><span>Pá</span><span>So</span><span>Ne</span>
            </div>
            <div class="calendar-grid">
              <template x-for="(cell, ci) in m.cells" :key="m.label + '-' + ci">
                <button type="button" class="calendar-cell"
                  :class="{
                    'is-out': !cell.inMonth,
                    'is-today': cell.isToday,
                    'has-training': cell.training && cell.inMonth,
                    'is-picked': isPicked(cell),
                  }"
                  :disabled="!cell.training || !cell.inMonth"
                  :aria-pressed="isPicked(cell)"
                  @mouseenter="hover(cell)" @focus="hover(cell)"
                  @mouseleave="unhover()" @blur="unhover()"
                  @click="pick(cell)">
                  <span class="calendar-num" x-text="cell.day"></span>
                  <span class="calendar-dot" x-show="cell.training && cell.inMonth"></span>
                </button>
              </template>
            </div>
          </div>
        </template>
      </div>
      <div class="calendar-legend">
        <span class="calendar-legend-item"><span class="calendar-legend-dot"></span> Den s tréninkem</span>
        <span class="calendar-legend-item"><span class="calendar-legend-today"></span> Dnes</span>
      </div>
    </div>

    {{-- ─── DETAIL / WEEKLY OVERVIEW ─── --}}
    <aside class="schedule-detail">
      {{-- Default state: weekly overview --}}
      <div x-show="!detail" class="detail-week">
        <div class="detail-eyebrow">Týdenní rozvrh</div>
        <div class="week-row">
          <span class="week-day">Pondělí</span>
          <div class="week-items">
            <span class="week-item"><strong>Judo</strong> · Praha 8 · 16:30–18:00</span>
            <span class="week-item"><strong>Judo</strong> · Vodochody · 16:30–18:00</span>
            <span class="week-item"><strong>Taijutsu</strong> · Praha 8 · 18:45–20:30</span>
          </div>
        </div>
        <div class="week-row">
          <span class="week-day">Úterý</span>
          <div class="week-items">
            <span class="week-item"><strong>Judo</strong> · Vodochody · 16:30–18:00</span>
          </div>
        </div>
        <div class="week-row">
          <span class="week-day">Středa</span>
          <div class="week-items">
            <span class="week-item"><strong>Judo</strong> · Praha 8 · 16:30–18:00</span>
            <span class="week-item"><strong>Taijutsu</strong> · Praha 8 · 18:45–20:30</span>
          </div>
        </div>
        <p class="detail-hint">Tip: klikněte na konkrétní den v kalendáři a objednejte se na trénink.</p>
      </div>

      {{-- Active state: selected/hovered day --}}
      <div x-show="detail" x-cloak class="detail-active">
        <div class="detail-eyebrow">Trénink v den</div>
        <div class="detail-date" x-text="detail?.label"></div>
        <template x-for="(t, ti) in (detail?.trainings || [])" :key="ti">
          <div class="detail-train">
            <div class="detail-train-head">
              <span class="detail-train-type" x-text="t.type"></span>
              <span class="detail-train-time" x-text="t.time"></span>
            </div>
            <div class="detail-train-place" x-text="t.place"></div>
            <div class="detail-train-loc" x-text="t.loc"></div>
            <button type="button" class="detail-book" @click="book(detail.iso, t)">
              Objednat na tento trénink &rarr;
            </button>
          </div>
        </template>
      </div>
    </aside>
  </div>

  {{-- ─── INQUIRY FORM ─── --}}
  <div id="inquiry" class="inquiry">
    <div class="inquiry-intro">
      <div class="section-eyebrow">Dotazy & objednání</div>
      <h3 class="inquiry-title">Napište nám</h3>
      <p class="inquiry-lead">
        Máte dotaz nebo se chcete přijít podívat na trénink? Napište nám — ozveme se vám.
        Můžete také rovnou zavolat na <a href="tel:+420777166156" class="contact-link">777&nbsp;166&nbsp;156</a>.
      </p>
    </div>

    @if ($sent)
      <div class="inquiry-success">
        <div class="inquiry-success-mark">✓</div>
        <div class="inquiry-success-title">Děkujeme, zpráva odešla.</div>
        <p class="inquiry-success-body">Ozveme se vám co nejdříve. Těšíme se na vás na tatami.</p>
        <button type="button" class="btn-ghost" wire:click="$set('sent', false)">Odeslat další zprávu</button>
      </div>
    @else
      <form id="inquiry-form" class="inquiry-form" wire:submit="save">
        <div class="inquiry-grid">
          <label class="inquiry-field">
            <span class="inquiry-label">Typ tréninku / dotaz</span>
            <select wire:model.live="trainingType" class="inquiry-input">
              <option value="">— vyberte —</option>
              @foreach ($trainingOptions as $option)
                <option value="{{ $option }}">{{ $option }}</option>
              @endforeach
            </select>
            @error('trainingType') <span class="inquiry-error">{{ $message }}</span> @enderror
          </label>

          @php
            $dateOptions = $this->availableDates();
            $dateDisabled = empty($dateOptions);
            $datePlaceholder = $trainingType === '' ? 'Nejdřív vyberte typ tréninku'
              : ($trainingType === 'Obecný dotaz' ? 'U obecného dotazu není termín potřeba' : '— bez preference —');
          @endphp
          <label class="inquiry-field">
            <span class="inquiry-label">Preferovaný termín <em>(nepovinné)</em></span>
            <select wire:model="date" id="inq-date" class="inquiry-input" @disabled($dateDisabled)>
              <option value="">{{ $datePlaceholder }}</option>
              @foreach ($dateOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
              @endforeach
            </select>
            @error('date') <span class="inquiry-error">{{ $message }}</span> @enderror
          </label>

          <label class="inquiry-field">
            <span class="inquiry-label">Jméno a příjmení</span>
            <input type="text" wire:model="name" id="inq-name" class="inquiry-input" autocomplete="name">
            @error('name') <span class="inquiry-error">{{ $message }}</span> @enderror
          </label>

          <label class="inquiry-field">
            <span class="inquiry-label">E-mail</span>
            <input type="email" wire:model="email" class="inquiry-input" autocomplete="email">
            @error('email') <span class="inquiry-error">{{ $message }}</span> @enderror
          </label>

          <label class="inquiry-field">
            <span class="inquiry-label">Telefon <em>(nepovinné)</em></span>
            <input type="tel" wire:model="phone" class="inquiry-input" autocomplete="tel">
            @error('phone') <span class="inquiry-error">{{ $message }}</span> @enderror
          </label>

          <label class="inquiry-field inquiry-field-full">
            <span class="inquiry-label">Zpráva <em>(nepovinné)</em></span>
            <textarea wire:model="message" rows="4" class="inquiry-input" placeholder="Např. věk dítěte, zkušenosti, na co se chcete zeptat…"></textarea>
            @error('message') <span class="inquiry-error">{{ $message }}</span> @enderror
          </label>
        </div>

        <label class="inquiry-consent">
          <input type="checkbox" wire:model="consent">
          <span>Souhlasím se zpracováním osobních údajů za účelem vyřízení mého dotazu či objednání na trénink.</span>
        </label>
        @error('consent') <span class="inquiry-error">{{ $message }}</span> @enderror

        <button type="submit" class="btn-primary inquiry-submit">
          <span wire:loading.remove wire:target="save">Odeslat zprávu</span>
          <span wire:loading wire:target="save">Odesílám…</span>
        </button>
      </form>
    @endif
  </div>

  @assets
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('trainingCalendar', () => ({
        monthNames: ['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
        dayNames: ['neděle','pondělí','úterý','středa','čtvrtek','pátek','sobota'],
        // Weekly schedule keyed by JS getDay(): 1=Mon, 2=Tue, 3=Wed
        schedule: {
          1: [
            { type: 'Judo', place: 'Praha 8', loc: 'Za Invalidovnou 579/3', time: '16:30–18:00', form: 'Judo – Praha 8' },
            { type: 'Judo', place: 'Vodochody', loc: 'Průběžná 50', time: '16:30–18:00', form: 'Judo – Vodochody' },
            { type: 'Taijutsu', place: 'Praha 8', loc: 'Dojo Kundratka 19', time: '18:45–20:30', form: 'Taijutsu – Praha 8' },
          ],
          2: [
            { type: 'Judo', place: 'Vodochody', loc: 'Průběžná 50', time: '16:30–18:00', form: 'Judo – Vodochody' },
          ],
          3: [
            { type: 'Judo', place: 'Praha 8', loc: 'Za Invalidovnou 579/3', time: '16:30–18:00', form: 'Judo – Praha 8' },
            { type: 'Taijutsu', place: 'Praha 8', loc: 'Dojo Kundratka 19', time: '18:45–20:30', form: 'Taijutsu – Praha 8' },
          ],
        },
        view: { year: 2025, month: 0 },
        hovered: null,
        picked: null,
        todayIso: '',

        init() {
          const now = new Date();
          this.view = { year: now.getFullYear(), month: now.getMonth() };
          this.todayIso = this.iso(now);
        },

        iso(d) {
          return d.getFullYear() + '-' +
            String(d.getMonth() + 1).padStart(2, '0') + '-' +
            String(d.getDate()).padStart(2, '0');
        },

        czLabel(d) {
          return this.dayNames[d.getDay()] + ' ' + d.getDate() + '. ' + (d.getMonth() + 1) + '. ' + d.getFullYear();
        },

        buildMonth(year, month) {
          const first = new Date(year, month, 1);
          const offset = (first.getDay() + 6) % 7; // Monday-first
          const start = new Date(year, month, 1 - offset);
          const cells = [];
          for (let i = 0; i < 42; i++) {
            const d = new Date(start.getFullYear(), start.getMonth(), start.getDate() + i);
            const trainings = this.schedule[d.getDay()] || null;
            cells.push({
              iso: this.iso(d),
              day: d.getDate(),
              inMonth: d.getMonth() === month,
              isToday: this.iso(d) === this.todayIso,
              training: !!trainings,
              trainings: trainings,
              label: this.czLabel(d),
            });
          }
          return { label: this.monthNames[month] + ' ' + year, cells };
        },

        get months() {
          const nextM = this.view.month === 11 ? 0 : this.view.month + 1;
          const nextY = this.view.month === 11 ? this.view.year + 1 : this.view.year;
          return [
            this.buildMonth(this.view.year, this.view.month),
            this.buildMonth(nextY, nextM),
          ];
        },

        get detail() {
          return this.hovered || this.picked || null;
        },

        isPicked(cell) {
          return this.picked && this.picked.iso === cell.iso;
        },

        prev() {
          this.view = this.view.month === 0
            ? { year: this.view.year - 1, month: 11 }
            : { year: this.view.year, month: this.view.month - 1 };
        },
        next() {
          this.view = this.view.month === 11
            ? { year: this.view.year + 1, month: 0 }
            : { year: this.view.year, month: this.view.month + 1 };
        },

        hover(cell) { if (cell.training && cell.inMonth) this.hovered = cell; },
        unhover() { this.hovered = null; },
        pick(cell) { if (cell.training && cell.inMonth) this.picked = cell; },

        book(iso, t) {
          // Nejdřív nastavíme typ tréninku (live) → server přepočítá nabídku
          // termínů, teprve pak doplníme konkrétní datum, aby bylo v selectu.
          this.$wire.set('trainingType', t.form).then(() => {
            this.$wire.set('date', iso);
          });
          const target = document.getElementById('inquiry');
          if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          const name = document.getElementById('inq-name');
          if (name) name.focus({ preventScroll: true });
        },
      }));
    });
  </script>
  @endassets
</section>
