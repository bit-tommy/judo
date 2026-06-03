<?php
use Livewire\Volt\Component;

// Kalendář je čistě prezentační (Alpine). Vlastní formulář žije v samostatné
// komponentě <livewire:inquiry-form />; kalendář do něj jen předvyplňuje výběr
// přes Livewire událost `inquiry-prefill` (viz book() v Alpine níže).
new class extends Component {}; ?>

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

  {{-- ─── INQUIRY FORM (sdílená komponenta) ─── --}}
  <livewire:inquiry-form />

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
