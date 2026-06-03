<?php
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Mail\TrainingInquiry;
use App\Models\Inquiry;

/**
 * Sdílený poptávkový / objednávkový formulář.
 *
 * Používá ho rozvrh na úvodu (training-calendar) i popup na stránce „Tréninky
 * dětí". Kalendář do něj předvyplňuje typ tréninku a termín přes Livewire
 * událost `inquiry-prefill`.
 */
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
     * Musí odpovídat rozvrhu v kalendáři. „Obecný dotaz" zde záměrně chybí.
     */
    protected array $trainingDays = [
        'Judo – Praha 8'     => [1, 3],
        'Judo – Vodochody'   => [1, 2],
        'Taijutsu – Praha 8' => [1, 3],
    ];

    private const CZ_DAYS = ['pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota', 'neděle'];

    /**
     * Předvyplnění z kalendáře (klik na „Objednat na tento trénink").
     * Nejdřív typ → přepočítá se nabídka termínů → teprve pak datum.
     */
    #[On('inquiry-prefill')]
    public function prefill(string $trainingType = '', string $date = ''): void
    {
        $this->sent = false;

        if ($trainingType !== '') {
            $this->trainingType = $trainingType;
        }

        if ($date !== '' && isset($this->availableDates()[$date])) {
            $this->date = $date;
        }
    }

    /**
     * Nejbližší tréninkové termíny pro zvolený typ tréninku (na 8 týdnů dopředu).
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

<div id="inquiry" class="inquiry">
  <div class="inquiry-intro">
    <div class="section-eyebrow">Dotazy &amp; objednání</div>
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
