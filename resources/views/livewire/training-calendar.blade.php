<?php

use App\Models\Event;
use App\Models\ScheduleOverride;
use Livewire\Volt\Component;

/**
 * Kalendář na úvodní stránce. Pravidelný rozvrh čte ze sdíleného
 * config/content/schedule.php, k němu přidává:
 *  - akce klubu z databáze (zlatá tečka, detail s odkazem na /akce),
 *  - jednorázové výjimky rozvrhu (zrušený trénink = přeškrtnutě,
 *    mimořádný trénink = navíc; spravuje administrace v sekci Rozvrh).
 *
 * Vlastní formulář žije v samostatné komponentě <livewire:inquiry-form />;
 * kalendář do něj jen předvyplňuje výběr přes Livewire událost
 * `inquiry-prefill` (viz book() v Alpine níže).
 */
new class extends Component {
    public function with(): array
    {
        return [
            'calendarEvents' => Event::orderBy('starts_on')->get()
                ->map(fn (Event $event) => [
                    'from' => $event->starts_on->toDateString(),
                    'to' => ($event->ends_on ?? $event->starts_on)->toDateString(),
                    'title' => $event->title,
                    'place' => $event->place,
                    'dates' => $event->dateRange(),
                ])->values(),
            'cancellations' => ScheduleOverride::cancellations()->get()
                ->map(fn (ScheduleOverride $o) => [
                    'date' => $o->date->toDateString(),
                    'form' => $o->form,
                    'time' => $o->time,
                ])->values(),
            'extras' => ScheduleOverride::extras()->get()
                ->map(fn (ScheduleOverride $o) => [
                    'date' => $o->date->toDateString(),
                    'type' => $o->type,
                    'place' => $o->place,
                    'loc' => $o->loc,
                    'time' => $o->time,
                    'form' => $o->form,
                ])->values(),
        ];
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
                    'has-training': cell.training && cell.inMonth && !cell.allCancelled,
                    'has-event': cell.hasEvent && cell.inMonth,
                    'is-picked': isPicked(cell),
                  }"
                  :disabled="(!cell.training && !cell.hasEvent) || !cell.inMonth"
                  :aria-pressed="isPicked(cell)"
                  @mouseenter="hover(cell)" @focus="hover(cell)"
                  @mouseleave="unhover()" @blur="unhover()"
                  @click="pick(cell)">
                  <span class="calendar-num" x-text="cell.day"></span>
                  <span class="calendar-dots" x-show="cell.inMonth && (cell.training || cell.hasEvent)">
                    <span class="calendar-dot" :class="{ 'cancelled': cell.allCancelled }" x-show="cell.training"></span>
                    <span class="calendar-dot event" x-show="cell.hasEvent"></span>
                  </span>
                </button>
              </template>
            </div>
          </div>
        </template>
      </div>
      <div class="calendar-legend">
        <span class="calendar-legend-item"><span class="calendar-legend-dot"></span> Trénink</span>
        <span class="calendar-legend-item"><span class="calendar-legend-dot event"></span> Akce klubu</span>
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
        <div class="detail-eyebrow">Program v den</div>
        <div class="detail-date" x-text="detail?.label"></div>

        {{-- Akce klubu v daný den --}}
        <template x-for="(ev, ei) in (detail?.events || [])" :key="'ev-' + ei">
          <div class="detail-event">
            <div class="detail-train-head">
              <span class="detail-train-type" x-text="ev.title"></span>
              <span class="detail-flag event">Akce</span>
            </div>
            <div class="detail-train-loc">
              <span x-text="ev.dates"></span><span x-show="ev.place"> · <span x-text="ev.place"></span></span>
            </div>
            <a class="detail-book" href="{{ route('events') }}" wire:navigate>Více o akcích &rarr;</a>
          </div>
        </template>

        {{-- Tréninky (vč. zrušených a mimořádných) --}}
        <template x-for="(t, ti) in (detail?.trainings || [])" :key="'t-' + ti">
          <div class="detail-train" :class="{ 'is-cancelled': t.cancelled }">
            <div class="detail-train-head">
              <span class="detail-train-type" x-text="t.type"></span>
              <span class="detail-train-time" x-text="t.time"></span>
            </div>
            <div class="detail-train-place">
              <span x-text="t.place"></span>
              <span class="detail-flag cancelled" x-show="t.cancelled">Zrušeno</span>
              <span class="detail-flag extra" x-show="t.extra && !t.cancelled">Mimořádný</span>
            </div>
            <div class="detail-train-loc" x-text="t.loc"></div>
            <button type="button" class="detail-book" x-show="!t.cancelled && t.form" @click="book(detail.iso, t)">
              Objednat na tento trénink &rarr;
            </button>
          </div>
        </template>
      </div>
    </aside>
  </div>

  {{-- ─── INQUIRY FORM (sdílená komponenta) ─── --}}
  <livewire:inquiry-form />

  @assets
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('trainingCalendar', () => ({
        monthNames: ['Leden','Únor','Březen','Duben','Květen','Červen','Červenec','Srpen','Září','Říjen','Listopad','Prosinec'],
        dayNames: ['neděle','pondělí','úterý','středa','čtvrtek','pátek','sobota'],
        // Týdenní rozvrh z config/content/schedule.php; klíče 1–3 (po–st)
        // sedí pro JS getDay() — viz poznámka v configu.
        schedule: @json(config('content.schedule.days', [])),
        // Akce klubu + jednorázové výjimky rozvrhu (spravuje administrace).
        events: @json($calendarEvents),
        cancellations: @json($cancellations),
        extras: @json($extras),
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

        // Tréninky pro den: pravidelné (s příznakem zrušení) + mimořádné.
        trainingsFor(d, isoStr) {
          const regular = (this.schedule[d.getDay()] || []).map((t) => ({
            ...t,
            cancelled: this.cancellations.some((c) => c.date === isoStr && c.form === t.form && c.time === t.time),
            extra: false,
          }));
          const added = this.extras
            .filter((e) => e.date === isoStr)
            .map((e) => ({ ...e, cancelled: false, extra: true }));
          return regular.concat(added);
        },

        eventsFor(isoStr) {
          return this.events.filter((e) => isoStr >= e.from && isoStr <= e.to);
        },

        buildMonth(year, month) {
          const first = new Date(year, month, 1);
          const offset = (first.getDay() + 6) % 7; // Monday-first
          const start = new Date(year, month, 1 - offset);
          const cells = [];
          for (let i = 0; i < 42; i++) {
            const d = new Date(start.getFullYear(), start.getMonth(), start.getDate() + i);
            const isoStr = this.iso(d);
            const trainings = this.trainingsFor(d, isoStr);
            const events = this.eventsFor(isoStr);
            cells.push({
              iso: isoStr,
              day: d.getDate(),
              inMonth: d.getMonth() === month,
              isToday: isoStr === this.todayIso,
              training: trainings.length > 0,
              allCancelled: trainings.length > 0 && trainings.every((t) => t.cancelled),
              trainings: trainings.length > 0 ? trainings : null,
              hasEvent: events.length > 0,
              events: events,
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

        active(cell) { return (cell.training || cell.hasEvent) && cell.inMonth; },
        hover(cell) { if (this.active(cell)) this.hovered = cell; },
        unhover() { this.hovered = null; },
        pick(cell) { if (this.active(cell)) this.picked = cell; },

        book(iso, t) {
          // Předvyplnění řeší samostatná komponenta formuláře – pošleme jí
          // typ tréninku i termín jednou Livewire událostí (server doplní
          // datum jen pokud je pro daný typ platné).
          Livewire.dispatch('inquiry-prefill', { trainingType: t.form, date: iso });
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
