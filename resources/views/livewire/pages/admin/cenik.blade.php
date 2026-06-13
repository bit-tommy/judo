<?php

use App\Models\Price;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.admin')]
#[Title('Ceník | Administrace JC Raion-Ryu')]
class extends Component {
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public ?int $amount = null;

    public string $period = '3 měsíce';

    public string $note = '';

    public ?int $sort = null;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $priceId): void
    {
        $price = Price::findOrFail($priceId);

        $this->resetForm();
        $this->editingId = $price->id;
        $this->title = $price->title;
        $this->amount = $price->amount;
        $this->period = $price->period;
        $this->note = (string) $price->note;
        $this->sort = $price->sort;
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
                'title' => 'required|string|max:120',
                'amount' => 'required|integer|min:0|max:100000',
                'period' => 'required|string|max:40',
                'note' => 'nullable|string|max:200',
                'sort' => 'nullable|integer|min:0|max:999',
            ],
            [
                'title.required' => 'Vyplňte prosím název položky.',
                'amount.required' => 'Vyplňte prosím cenu.',
                'amount.integer' => 'Cenu zadejte jako celé číslo v Kč.',
                'amount.min' => 'Cena nemůže být záporná.',
                'period.required' => 'Vyplňte prosím období (např. „3 měsíce").',
            ],
        );

        $data = [
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'period' => $validated['period'],
            'note' => $this->note !== '' ? $this->note : null,
            'sort' => $this->sort ?? ((int) Price::max('sort') + 1),
        ];

        if ($this->editingId !== null) {
            Price::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Změny ceníku byly uloženy.');
        } else {
            Price::create($data);
            $this->dispatch('toast', message: 'Položka byla přidána do ceníku.');
        }

        $this->closeModals();
        $this->resetForm();
    }

    public function toggleVisible(int $priceId): void
    {
        $price = Price::findOrFail($priceId);
        $price->update(['visible' => ! $price->visible]);

        $this->dispatch('toast', message: $price->visible
            ? 'Položka je znovu viditelná na webu.'
            : 'Položka byla skryta z webu.');
    }

    public function delete(int $priceId): void
    {
        Price::findOrFail($priceId)->delete();

        $this->closeModals();
        $this->dispatch('toast', message: 'Položka byla odstraněna z ceníku.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'amount', 'note', 'sort']);
        $this->period = '3 měsíce';
        $this->resetValidation();
    }

    public function with(): array
    {
        return [
            'prices' => Price::ordered()->get(),
        ];
    }
}; ?>

<section class="panel">
  <div class="main-head">
    <div>
      <div class="eyebrow reveal" style="--i: 0">Členské příspěvky</div>
      <h1 class="main-title reveal" style="--i: 1">Ceník</h1>
    </div>
    <div class="head-actions reveal" style="--i: 2">
      <a class="btn ghost" href="{{ route('pricing') }}" target="_blank" rel="noopener">Zobrazit na webu</a>
      <button type="button" class="btn" wire:click="openCreate">+ Přidat položku</button>
    </div>
  </div>

  @if ($prices->isEmpty())
    <div class="empty-note reveal" style="--i: 3">Ceník je prázdný — přidejte první položku.</div>
  @else
    <ul class="docs reveal" style="--i: 3">
      @foreach ($prices as $price)
        <li class="doc {{ $price->visible ? '' : 'is-hidden' }}" wire:key="price-{{ $price->id }}">
          <span class="doc-ext">Kč</span>
          <div class="doc-name">
            {{ $price->title }}
            @if (! $price->visible) <span class="tag line" style="margin-left: 8px;">Skrytý</span> @endif
            @if ($price->note)<span>{{ $price->note }}</span>@endif
          </div>
          <div class="doc-dl"><em>{{ $price->amountLabel() }}</em> / {{ $price->period }}</div>
          <div class="doc-actions">
            <button type="button" class="btn subtle" wire:click="openEdit({{ $price->id }})">Upravit</button>
            <button type="button" class="btn subtle" wire:click="toggleVisible({{ $price->id }})">{{ $price->visible ? 'Skrýt' : 'Zobrazit' }}</button>
            <button type="button" class="btn subtle" wire:click="delete({{ $price->id }})"
                    wire:confirm="Opravdu odstranit položku „{{ $price->title }}" z ceníku?">Smazat</button>
          </div>
        </li>
      @endforeach
    </ul>
  @endif

  <p class="reveal" style="--i: 4; margin-top: 28px; font-size: 12.5px; color: var(--ink-light); max-width: 560px; line-height: 1.7;">
    Položky se zobrazují na veřejné stránce /cenik v pořadí podle čísla „Pořadí".
    Skrytá položka na webu není vidět, ale zůstává tady.
  </p>

  {{-- ─── Přidání / úprava položky ─── --}}
  @if ($showForm)
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
          <div class="eyebrow">{{ $editingId ? 'Úprava položky' : 'Nová položka' }}</div>
          <h3>{{ $editingId ? 'Upravit položku' : 'Přidat do ceníku' }}</h3>
          <form wire:submit="save">
            <div class="field">
              <label for="p-title">Název</label>
              <input type="text" id="p-title" wire:model="title" placeholder="Judo — Praha">
              <div class="field-bar"></div>
              @error('title') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-row">
              <div class="field">
                <label for="p-amount">Cena (Kč)</label>
                <input type="number" id="p-amount" wire:model="amount" placeholder="3000" min="0" step="100">
                <div class="field-bar"></div>
                @error('amount') <span class="field-error">{{ $message }}</span> @enderror
              </div>
              <div class="field">
                <label for="p-period">Období</label>
                <input type="text" id="p-period" wire:model="period" placeholder="3 měsíce">
                <div class="field-bar"></div>
                @error('period') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="form-row">
              <div class="field">
                <label for="p-note">Poznámka <span style="text-transform: none; letter-spacing: 0;">(nepovinné)</span></label>
                <input type="text" id="p-note" wire:model="note" placeholder="např. sleva pro sourozence">
                <div class="field-bar"></div>
                @error('note') <span class="field-error">{{ $message }}</span> @enderror
              </div>
              <div class="field">
                <label for="p-sort">Pořadí</label>
                <input type="number" id="p-sort" wire:model="sort" min="0" placeholder="automaticky">
                <div class="field-bar"></div>
                @error('sort') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="modal-actions">
              <button type="submit" class="btn">{{ $editingId ? 'Uložit změny' : 'Přidat položku' }}</button>
              <button type="button" class="btn ghost" wire:click="closeModals">Zrušit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</section>
