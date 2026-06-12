<?php

use App\Enums\MemberGroup;
use App\Enums\MemberStatus;
use App\Models\Inquiry;
use App\Models\Member;
use Illuminate\Validation\Rule;
use Livewire\Attributes\{Layout, Title, Url};
use Livewire\Volt\Component;

new #[Layout('components.layouts.admin')]
#[Title('Členové | Administrace JC Raion-Ryu')]
class extends Component {
    #[Url(as: 'hledat', except: '')]
    public string $search = '';

    public string $filter = 'vse';

    /** Detail člena (karta). */
    public ?int $detailId = null;

    /** Formulář (vytvoření / úprava). */
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public ?int $age = null;

    public string $group = 'pripravka';

    public string $parentName = '';

    public string $phone = '';

    public string $email = '';

    public string $memberSince = '';

    public string $belt = '';

    public string $status = 'aktivni';

    public string $note = '';

    /** Poptávka, ze které člena zakládáme (předvyplnění z dashboardu). */
    public ?int $inquiryId = null;

    public function mount(): void
    {
        $inquiryId = (int) request()->query('from_inquiry', 0);

        if ($inquiryId > 0 && ($inquiry = Inquiry::find($inquiryId)) !== null) {
            $this->openCreateFromInquiry($inquiry);
        }
    }

    public function openDetail(int $memberId): void
    {
        $this->detailId = $memberId;
        $this->showForm = false;
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $memberId): void
    {
        $member = Member::findOrFail($memberId);

        $this->resetForm();
        $this->editingId = $member->id;
        $this->name = $member->name;
        $this->age = $member->age;
        $this->group = $member->group->value;
        $this->parentName = $member->parent_name;
        $this->phone = (string) $member->phone;
        $this->email = (string) $member->email;
        $this->memberSince = $member->member_since?->format('Y-m-d') ?? '';
        $this->belt = (string) $member->belt;
        $this->status = $member->status->value;
        $this->note = (string) $member->note;

        $this->detailId = null;
        $this->showForm = true;
    }

    public function closeModals(): void
    {
        $this->detailId = null;
        $this->showForm = false;
    }

    public function save(): void
    {
        $validated = $this->validate(
            [
                'name' => 'required|string|max:120',
                'age' => 'required|integer|min:4|max:99',
                'group' => ['required', Rule::enum(MemberGroup::class)],
                'parentName' => 'required|string|max:120',
                'phone' => 'nullable|string|max:40',
                'email' => 'nullable|email|max:160',
                'memberSince' => 'nullable|date',
                'belt' => 'nullable|string|max:80',
                'status' => ['required', Rule::enum(MemberStatus::class)],
                'note' => 'nullable|string|max:1000',
            ],
            [
                'name.required' => 'Vyplňte prosím jméno dítěte.',
                'age.required' => 'Vyplňte prosím věk.',
                'age.integer' => 'Věk zadejte jako číslo.',
                'age.min' => 'Věk musí být alespoň :min let.',
                'age.max' => 'Věk může být nejvýše :max let.',
                'parentName.required' => 'Vyplňte prosím rodiče či zástupce.',
                'email.email' => 'Zadejte platnou e-mailovou adresu.',
                'memberSince.date' => 'Zadejte platné datum.',
            ],
        );

        $data = [
            'name' => $validated['name'],
            'age' => $validated['age'],
            'group' => $validated['group'],
            'parent_name' => $validated['parentName'],
            'phone' => $this->phone !== '' ? $this->phone : null,
            'email' => $this->email !== '' ? $this->email : null,
            'member_since' => $this->memberSince !== '' ? $this->memberSince : null,
            'belt' => $this->belt !== '' ? $this->belt : null,
            'status' => $validated['status'],
            'note' => $this->note !== '' ? $this->note : null,
        ];

        if ($this->editingId !== null) {
            Member::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Změny byly uloženy.');
        } else {
            $data['inquiry_id'] = $this->inquiryId;
            Member::create($data);

            if ($this->inquiryId !== null) {
                Inquiry::find($this->inquiryId)?->update(['handled_at' => now()]);
            }

            $this->dispatch('toast', message: 'Člen byl přidán do seznamu.');
        }

        $this->closeModals();
        $this->resetForm();
    }

    /** Schválení nové přihlášky (nova → aktivní). */
    public function approve(int $memberId): void
    {
        $member = Member::findOrFail($memberId);
        $member->update([
            'status' => MemberStatus::Aktivni,
            'member_since' => $member->member_since ?? today(),
        ]);

        $member->inquiry?->update(['handled_at' => now()]);

        $this->dispatch('toast', message: 'Přihláška byla schválena.');
    }

    public function delete(int $memberId): void
    {
        Member::findOrFail($memberId)->delete();

        $this->closeModals();
        $this->dispatch('toast', message: 'Člen byl odebrán ze seznamu.');
    }

    private function openCreateFromInquiry(Inquiry $inquiry): void
    {
        $this->resetForm();
        $this->name = $inquiry->name;
        $this->phone = (string) $inquiry->phone;
        $this->email = $inquiry->email;
        $this->status = MemberStatus::Nova->value;
        $this->inquiryId = $inquiry->id;
        $this->showForm = true;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'age', 'parentName', 'phone', 'email', 'memberSince', 'belt', 'note', 'inquiryId']);
        $this->group = MemberGroup::Pripravka->value;
        $this->status = MemberStatus::Aktivni->value;
        $this->resetValidation();
    }

    public function with(): array
    {
        $members = Member::query()
            ->when($this->search !== '', function ($query) {
                $query->where(fn ($q) => $q
                    ->where('name', 'like', "%{$this->search}%")
                    ->orWhere('parent_name', 'like', "%{$this->search}%"));
            })
            ->when($this->filter === 'nove', fn ($q) => $q->where('status', MemberStatus::Nova))
            ->when(in_array($this->filter, ['pripravka', 'pokrocili'], true), fn ($q) => $q->where('group', $this->filter))
            ->orderBy('name')
            ->get();

        return [
            'members' => $members,
            'detailMember' => $this->detailId !== null ? Member::find($this->detailId) : null,
            'activeCount' => Member::active()->count(),
            'newCount' => Member::pending()->count(),
        ];
    }
}; ?>

<section class="panel">
  <div class="main-head">
    <div>
      <div class="eyebrow reveal" style="--i: 0">Členové &amp; přihlášky</div>
      <h1 class="main-title reveal" style="--i: 1">Členové klubu</h1>
    </div>
    <div class="main-date reveal" style="--i: 2">{{ $activeCount }} aktivních · {{ $newCount }} nových přihlášek</div>
  </div>

  <div class="toolbar reveal" style="--i: 3">
    <div class="search">
      <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><circle cx="6" cy="6" r="4.5" stroke="currentColor" stroke-width="1.3"></circle><path d="M9.5 9.5L13 13" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"></path></svg>
      <input type="text" wire:model.live.debounce.250ms="search" placeholder="Hledat jméno, rodiče…">
    </div>
    <div class="filters">
      @foreach (['vse' => 'Vše', 'pripravka' => 'Přípravka', 'pokrocili' => 'Pokročilí', 'nove' => 'Nové'] as $value => $label)
        <button type="button" class="filter {{ $filter === $value ? 'active' : '' }}" wire:click="$set('filter', '{{ $value }}')">{{ $label }}</button>
      @endforeach
    </div>
    <button type="button" class="btn" wire:click="openCreate">+ Nový člen</button>
  </div>

  @if ($members->isEmpty())
    <div class="empty-note reveal" style="--i: 4">
      {{ $search !== '' || $filter !== 'vse' ? 'Hledání nic nenašlo — zkuste upravit filtr.' : 'Zatím žádní členové. Přidejte prvního tlačítkem „+ Nový člen".' }}
    </div>
  @else
    <table class="table reveal" style="--i: 4">
      <thead>
        <tr>
          <th>Jméno</th><th>Věk</th><th>Skupina</th><th>Rodič</th><th>Stav</th><th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($members as $member)
          <tr wire:key="member-{{ $member->id }}" wire:click="openDetail({{ $member->id }})">
            <td><span class="member-name"><span class="member-ini">{{ $member->initials() }}</span>{{ $member->name }}</span></td>
            <td class="num">{{ $member->age }}</td>
            <td><span class="tag faint">{{ $member->group->label() }}</span></td>
            <td class="contact">{{ $member->parent_name }}</td>
            <td><span class="tag {{ $member->status->tagClass() }}">{{ $member->status->label() }}</span></td>
            <td><span class="row-arrow">→</span></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  {{-- ─── Karta člena ─── --}}
  @if ($detailMember)
    <div class="modal-bg"
         x-data
         x-init="requestAnimationFrame(() => $el.classList.add('open'))"
         @click.self="$wire.closeModals()"
         @keydown.escape.window="$wire.closeModals()">
      <div class="modal">
        <div class="modal-band"></div>
        <button type="button" class="modal-close" aria-label="Zavřít" wire:click="closeModals">×</button>
        <div class="modal-inner">
          <div class="eyebrow">Karta člena</div>
          <h3 style="display: flex; align-items: center; gap: 16px;">
            <span class="member-ini" style="width: 42px; height: 42px; font-size: 15px;">{{ $detailMember->initials() }}</span>
            <span>{{ $detailMember->name }}</span>
          </h3>
          <div class="detail-grid">
            <div class="detail-item"><div class="k">Věk</div><div class="v">{{ $detailMember->age }} let</div></div>
            <div class="detail-item"><div class="k">Skupina</div><div class="v">{{ $detailMember->group->label() }}</div></div>
            <div class="detail-item"><div class="k">Rodič</div><div class="v">{{ $detailMember->parent_name }}</div></div>
            <div class="detail-item"><div class="k">Telefon</div><div class="v mono">{{ $detailMember->phone ?? '—' }}</div></div>
            <div class="detail-item"><div class="k">Členem od</div><div class="v">{{ $detailMember->memberSinceLabel() ?? '—' }}</div></div>
            <div class="detail-item"><div class="k">Pás</div><div class="v">{{ $detailMember->belt ?? 'Zatím bez pásu' }}</div></div>
            <div class="detail-item"><div class="k">Stav</div><div class="v"><span class="tag {{ $detailMember->status->tagClass() }}">{{ $detailMember->status->label() }}</span></div></div>
            @if ($detailMember->email)
              <div class="detail-item"><div class="k">E-mail</div><div class="v mono">{{ $detailMember->email }}</div></div>
            @endif
          </div>
          @if ($detailMember->note)
            <div class="detail-item" style="margin: -10px 0 26px;"><div class="k">Poznámka</div><div class="v">{{ $detailMember->note }}</div></div>
          @endif
          <div class="modal-actions">
            <button type="button" class="btn" wire:click="openEdit({{ $detailMember->id }})">Upravit údaje</button>
            @if ($detailMember->status === \App\Enums\MemberStatus::Nova)
              <button type="button" class="btn ghost" wire:click="approve({{ $detailMember->id }})">Schválit přihlášku</button>
            @endif
            <button type="button" class="btn subtle" wire:click="delete({{ $detailMember->id }})"
                    wire:confirm="Opravdu odebrat člena {{ $detailMember->name }}? Tuto akci nelze vrátit.">Odebrat</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  {{-- ─── Nový člen / úprava ─── --}}
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
          <div class="eyebrow">{{ $editingId ? 'Úprava člena' : 'Nový člen' }}</div>
          <h3>{{ $editingId ? 'Upravit údaje' : 'Přidat člena' }}</h3>
          @if ($inquiryId)
            <p style="margin: -14px 0 22px; font-size: 12px; color: var(--ink-light);">Předvyplněno z poptávky z webu — po uložení se poptávka označí jako vyřízená.</p>
          @endif
          <form wire:submit="save">
            <div class="form-row">
              <div class="field">
                <label for="m-name">Jméno dítěte</label>
                <input type="text" id="m-name" wire:model="name" placeholder="Jan Veselý">
                <div class="field-bar"></div>
                @error('name') <span class="field-error">{{ $message }}</span> @enderror
              </div>
              <div class="field">
                <label for="m-age">Věk</label>
                <input type="number" id="m-age" wire:model="age" placeholder="7" min="4" max="99">
                <div class="field-bar"></div>
                @error('age') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="form-row">
              <div class="field">
                <label for="m-parent">Rodič / zástupce</label>
                <input type="text" id="m-parent" wire:model="parentName" placeholder="Pavel Veselý">
                <div class="field-bar"></div>
                @error('parentName') <span class="field-error">{{ $message }}</span> @enderror
              </div>
              <div class="field">
                <label for="m-phone">Telefon</label>
                <input type="tel" id="m-phone" wire:model="phone" placeholder="+420 600 000 000">
                <div class="field-bar"></div>
              </div>
            </div>
            <div class="form-row">
              <div class="field">
                <label for="m-group">Skupina</label>
                <select id="m-group" wire:model="group">
                  @foreach (\App\Enums\MemberGroup::cases() as $groupOption)
                    <option value="{{ $groupOption->value }}">{{ $groupOption->formLabel() }}</option>
                  @endforeach
                </select>
              </div>
              <div class="field">
                <label for="m-status">Stav</label>
                <select id="m-status" wire:model="status">
                  @foreach (\App\Enums\MemberStatus::cases() as $statusOption)
                    <option value="{{ $statusOption->value }}">{{ $statusOption->label() }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="field">
                <label for="m-belt">Pás</label>
                <input type="text" id="m-belt" wire:model="belt" placeholder="Bílý pás · 6. kyu">
                <div class="field-bar"></div>
              </div>
              <div class="field">
                <label for="m-since">Členem od</label>
                <input type="date" id="m-since" wire:model="memberSince">
                <div class="field-bar"></div>
                @error('memberSince') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="field">
              <label for="m-email">E-mail rodiče <span style="text-transform: none; letter-spacing: 0;">(nepovinné)</span></label>
              <input type="email" id="m-email" wire:model="email" placeholder="rodic@email.cz">
              <div class="field-bar"></div>
              @error('email') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
              <label for="m-note">Poznámka <span style="text-transform: none; letter-spacing: 0;">(nepovinné)</span></label>
              <textarea id="m-note" wire:model="note" rows="2"></textarea>
              <div class="field-bar"></div>
            </div>
            <div class="modal-actions">
              <button type="submit" class="btn">{{ $editingId ? 'Uložit změny' : 'Uložit člena' }}</button>
              <button type="button" class="btn ghost" wire:click="closeModals">Zrušit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</section>
