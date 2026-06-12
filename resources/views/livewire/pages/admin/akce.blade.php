<?php

use App\Models\Event;
use Illuminate\Support\Str;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.admin')]
#[Title('Akce | Administrace JC Raion-Ryu')]
class extends Component {
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $startsOn = '';

    public string $endsOn = '';

    public string $place = '';

    public string $note = '';

    public string $description = '';

    public bool $isMain = false;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $eventId): void
    {
        $event = Event::findOrFail($eventId);

        $this->resetForm();
        $this->editingId = $event->id;
        $this->title = $event->title;
        $this->startsOn = $event->starts_on->format('Y-m-d');
        $this->endsOn = $event->ends_on?->format('Y-m-d') ?? '';
        $this->place = (string) $event->place;
        $this->note = (string) $event->note;
        $this->description = (string) $event->description;
        $this->isMain = $event->is_main;
        $this->showForm = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
    }

    public function save(): void
    {
        $validated = $this->validate(
            [
                'title' => 'required|string|max:160',
                'startsOn' => 'required|date',
                'endsOn' => 'nullable|date|after_or_equal:startsOn',
                'place' => 'nullable|string|max:160',
                'note' => 'nullable|string|max:180',
                'description' => 'nullable|string|max:5000',
            ],
            [
                'title.required' => 'Vyplňte prosím název akce.',
                'startsOn.required' => 'Vyplňte prosím datum začátku.',
                'startsOn.date' => 'Zadejte platné datum začátku.',
                'endsOn.date' => 'Zadejte platné datum konce.',
                'endsOn.after_or_equal' => 'Konec akce nemůže být před začátkem.',
            ],
        );

        $data = [
            'title' => $validated['title'],
            'starts_on' => $validated['startsOn'],
            'ends_on' => $this->endsOn !== '' ? $this->endsOn : null,
            'place' => $this->place !== '' ? $this->place : null,
            'note' => $this->note !== '' ? $this->note : null,
            'description' => $this->description !== '' ? $this->description : null,
            'is_main' => $this->isMain,
        ];

        if ($this->editingId !== null) {
            Event::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Změny akce byly uloženy.');
        } else {
            Event::create($data + ['slug' => $this->uniqueSlug($validated['title'])]);
            $this->dispatch('toast', message: 'Akce byla přidána do kalendáře.');
        }

        $this->closeModals();
        $this->resetForm();
    }

    public function delete(int $eventId): void
    {
        Event::findOrFail($eventId)->delete();

        $this->closeModals();
        $this->dispatch('toast', message: 'Akce byla smazána.');
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'akce';
        $slug = $base;
        $suffix = 2;

        while (Event::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'startsOn', 'endsOn', 'place', 'note', 'description', 'isMain']);
        $this->resetValidation();
    }

    public function with(): array
    {
        return [
            'upcoming' => Event::upcoming()->get(),
            'past' => Event::past()->get(),
        ];
    }
}; ?>

<section class="panel">
  <div class="main-head">
    <div>
      <div class="eyebrow reveal" style="--i: 0">Plánované akce</div>
      <h1 class="main-title reveal" style="--i: 1">Kalendář akcí</h1>
    </div>
    <div class="head-actions reveal" style="--i: 2">
      <button type="button" class="btn" wire:click="openCreate">+ Přidat akci</button>
    </div>
  </div>

  @if ($upcoming->isEmpty())
    <div class="empty-note reveal" style="--i: 3">Žádné nadcházející akce — přidejte první tlačítkem „+ Přidat akci".</div>
  @else
    <ul class="events reveal" style="--i: 3">
      @foreach ($upcoming as $event)
        <li class="event" wire:key="event-{{ $event->id }}" wire:click="openEdit({{ $event->id }})" style="cursor: pointer;">
          <div class="event-date"><div class="event-day">{{ $event->day() }}</div><div class="event-month">{{ $event->monthAbbr() }}</div></div>
          <div class="event-rule"></div>
          <div class="event-main">
            <div class="event-title">{{ $event->title }}</div>
            <div class="event-meta">
              <span>{{ $event->dateRange() }}</span>
              @if ($event->place)<span>{{ $event->place }}</span>@endif
              @if ($event->note)<span>{{ $event->note }}</span>@endif
            </div>
          </div>
          <span class="tag {{ $event->tagClass() }}">{{ $event->tagLabel() }}</span>
        </li>
      @endforeach
    </ul>
  @endif

  @if ($past->isNotEmpty())
    <h2 class="section-sub reveal" style="--i: 4">Proběhlé akce <span class="cnt">{{ $past->count() }} celkem</span></h2>
    <ul class="events reveal" style="--i: 5">
      @foreach ($past as $event)
        <li class="event" wire:key="event-past-{{ $event->id }}" wire:click="openEdit({{ $event->id }})" style="cursor: pointer; opacity: .7;">
          <div class="event-date"><div class="event-day">{{ $event->day() }}</div><div class="event-month" style="color: var(--ink-light);">{{ $event->monthAbbr() }} {{ $event->starts_on->year }}</div></div>
          <div class="event-rule"></div>
          <div class="event-main">
            <div class="event-title">{{ $event->title }}</div>
            <div class="event-meta">
              <span>{{ $event->dateRange() }}</span>
              @if ($event->place)<span>{{ $event->place }}</span>@endif
            </div>
          </div>
          <span class="tag faint">Proběhlo</span>
        </li>
      @endforeach
    </ul>
  @endif

  {{-- ─── Nová akce / úprava ─── --}}
  @if ($showForm)
    <div class="modal-bg"
         x-data
         x-init="requestAnimationFrame(() => $el.classList.add('open'))"
         @click.self="$wire.closeModals()"
         @keydown.escape.window="$wire.closeModals()">
      <div class="modal">
        <div class="modal-band"></div>
        <button type="button" class="modal-close" aria-label="Zavřít" wire:click="closeModals">×</button>
        <div class="modal-inner">
          <div class="eyebrow">{{ $editingId ? 'Úprava akce' : 'Nová akce' }}</div>
          <h3>{{ $editingId ? 'Upravit akci' : 'Přidat akci' }}</h3>
          <form wire:submit="save">
            <div class="field">
              <label for="e-title">Název akce</label>
              <input type="text" id="e-title" wire:model="title" placeholder="Podzimní soustředění">
              <div class="field-bar"></div>
              @error('title') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-row">
              <div class="field">
                <label for="e-from">Od</label>
                <input type="date" id="e-from" wire:model="startsOn">
                <div class="field-bar"></div>
                @error('startsOn') <span class="field-error">{{ $message }}</span> @enderror
              </div>
              <div class="field">
                <label for="e-to">Do <span style="text-transform: none; letter-spacing: 0;">(u jednodenní nechte prázdné)</span></label>
                <input type="date" id="e-to" wire:model="endsOn">
                <div class="field-bar"></div>
                @error('endsOn') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="field">
              <label for="e-place">Místo</label>
              <input type="text" id="e-place" wire:model="place" placeholder="Tělocvična ZŠ P. Strozziho">
              <div class="field-bar"></div>
            </div>
            <div class="field">
              <label for="e-note">Krátká poznámka <span style="text-transform: none; letter-spacing: 0;">(např. „Přihlášky od září")</span></label>
              <input type="text" id="e-note" wire:model="note" maxlength="180">
              <div class="field-bar"></div>
              @error('note') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
              <label for="e-desc">Popis pro veřejný web <span style="text-transform: none; letter-spacing: 0;">(nepovinné)</span></label>
              <textarea id="e-desc" wire:model="description" rows="3"></textarea>
              <div class="field-bar"></div>
            </div>
            <label class="check">
              <input type="checkbox" wire:model="isMain">
              Hlavní akce sezóny — zvýrazní se v přehledu i na webu
            </label>
            <div class="modal-actions">
              <button type="submit" class="btn">{{ $editingId ? 'Uložit změny' : 'Uložit akci' }}</button>
              <button type="button" class="btn ghost" wire:click="closeModals">Zrušit</button>
              @if ($editingId)
                <button type="button" class="btn subtle" wire:click="delete({{ $editingId }})"
                        wire:confirm="Opravdu smazat akci „{{ $title }}"? Zmizí i z veřejného webu.">Smazat akci</button>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</section>
