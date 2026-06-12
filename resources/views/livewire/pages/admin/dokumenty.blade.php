<?php

use App\Enums\DocumentGroup;
use App\Models\Document;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('components.layouts.admin')]
#[Title('Dokumenty | Administrace JC Raion-Ryu')]
class extends Component {
    use WithFileUploads;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $meta = '';

    public string $group = 'prihlasky';

    public bool $isExternal = false;

    public string $url = '';

    /** Nahrávané PDF (jen při vytváření souborového dokumentu). */
    public $pdf = null;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $documentId): void
    {
        $document = Document::findOrFail($documentId);

        $this->resetForm();
        $this->editingId = $document->id;
        $this->title = $document->title;
        $this->meta = (string) $document->meta;
        $this->group = $document->group->value;
        $this->isExternal = $document->isExternal();
        $this->url = (string) $document->url;
        $this->showForm = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|string|max:160',
            'meta' => 'nullable|string|max:160',
            'group' => ['required', Rule::enum(DocumentGroup::class)],
        ];

        if ($this->isExternal) {
            $rules['url'] = 'required|url|max:500';
        } elseif ($this->editingId === null) {
            $rules['pdf'] = 'required|file|mimes:pdf|max:20480';
        }

        $validated = $this->validate($rules, [
            'title.required' => 'Vyplňte prosím název dokumentu.',
            'url.required' => 'Vyplňte prosím adresu odkazu.',
            'url.url' => 'Zadejte platnou adresu (včetně https://).',
            'pdf.required' => 'Vyberte prosím PDF soubor.',
            'pdf.mimes' => 'Nahrát lze pouze PDF.',
            'pdf.max' => 'Soubor může mít nejvýše 20 MB.',
        ]);

        $data = [
            'title' => $validated['title'],
            'meta' => $this->meta !== '' ? $this->meta : null,
            'group' => $validated['group'],
        ];

        if ($this->editingId !== null) {
            $document = Document::findOrFail($this->editingId);

            if ($document->isExternal()) {
                $data['url'] = $this->url;
            }

            $document->update($data);
            $this->dispatch('toast', message: 'Změny dokumentu byly uloženy.');
        } elseif ($this->isExternal) {
            Document::create($data + [
                'type' => 'external',
                'url' => $this->url,
                'sort' => $this->nextSort($validated['group']),
            ]);
            $this->dispatch('toast', message: 'Odkaz byl přidán na web.');
        } else {
            $filename = $this->storePdf($validated['title']);

            Document::create($data + [
                'type' => 'file',
                'filename' => $filename,
                'size_bytes' => filesize(rtrim(config('documents.path'), '/').'/'.$filename),
                'sort' => $this->nextSort($validated['group']),
            ]);
            $this->dispatch('toast', message: 'Soubor byl nahrán a zveřejněn.');
        }

        $this->closeModals();
        $this->resetForm();
    }

    public function toggleVisible(int $documentId): void
    {
        $document = Document::findOrFail($documentId);
        $document->update(['visible' => ! $document->visible]);

        $this->dispatch('toast', message: $document->visible
            ? 'Dokument je znovu viditelný na webu.'
            : 'Dokument byl skryt z webu.');
    }

    public function delete(int $documentId): void
    {
        $document = Document::findOrFail($documentId);

        // Soubor smažeme z disku jen pokud na něj neodkazuje jiný dokument
        // (např. obě přihlášky sdílejí jedno PDF).
        if (! $document->isExternal() && $document->filename !== null) {
            $sharedByOthers = Document::where('filename', $document->filename)
                ->where('id', '!=', $document->id)
                ->exists();

            if (! $sharedByOthers && is_file((string) $document->filePath())) {
                @unlink((string) $document->filePath());
            }
        }

        $document->delete();

        $this->closeModals();
        $this->dispatch('toast', message: 'Dokument byl odstraněn.');
    }

    private function storePdf(string $title): string
    {
        $dir = rtrim(config('documents.path'), '/');
        File::ensureDirectoryExists($dir);

        $base = Str::slug($title) ?: 'dokument';
        $filename = $base.'.pdf';
        $suffix = 2;

        while (is_file($dir.'/'.$filename)) {
            $filename = $base.'-'.$suffix++.'.pdf';
        }

        // TemporaryUploadedFile žije na Livewire temp disku — obsah
        // přeneseme přes get(), getRealPath() na něj nemusí ukazovat.
        file_put_contents($dir.'/'.$filename, $this->pdf->get());

        return $filename;
    }

    private function nextSort(string $group): int
    {
        return (int) Document::where('group', $group)->max('sort') + 1;
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'meta', 'isExternal', 'url', 'pdf']);
        $this->group = DocumentGroup::Prihlasky->value;
        $this->resetValidation();
    }

    public function with(): array
    {
        return [
            'groups' => collect(DocumentGroup::ordered())->map(fn (DocumentGroup $group) => [
                'enum' => $group,
                'documents' => Document::where('group', $group)->ordered()->get(),
            ]),
            'totalDownloads' => Document::sum('downloads'),
        ];
    }
}; ?>

<section class="panel">
  <div class="main-head">
    <div>
      <div class="eyebrow reveal" style="--i: 0">Dokumenty ke stažení</div>
      <h1 class="main-title reveal" style="--i: 1">Soubory pro web</h1>
    </div>
    <div class="head-actions reveal" style="--i: 2">
      <span class="main-date">{{ $totalDownloads }}× staženo celkem</span>
      <button type="button" class="btn" wire:click="openCreate">+ Nahrát soubor</button>
    </div>
  </div>

  @foreach ($groups as $i => $groupData)
    @if ($groupData['documents']->isNotEmpty())
      <h2 class="section-sub reveal" style="--i: {{ 3 + $i }}">
        {{ $groupData['enum']->number() }} · {{ $groupData['enum']->label() }}
        <span class="cnt">{{ $groupData['documents']->count() }} položek</span>
      </h2>
      <ul class="docs reveal" style="--i: {{ 3 + $i }}; margin-bottom: 26px;">
        @foreach ($groupData['documents'] as $document)
          <li class="doc {{ $document->visible ? '' : 'is-hidden' }}" wire:key="doc-{{ $document->id }}">
            <span class="doc-ext">{{ $document->isExternal() ? 'WWW' : 'PDF' }}</span>
            <div class="doc-name">
              {{ $document->title }}
              @if (! $document->visible) <span class="tag line" style="margin-left: 8px;">Skrytý</span> @endif
              <span>
                @if ($document->isExternal())
                  {{ Str::limit($document->url, 64) }}
                @else
                  {{ $document->filename }}@if ($document->sizeLabel()) · {{ $document->sizeLabel() }}@endif · aktualizováno {{ $document->updated_at->day }}. {{ $document->updated_at->month }}. {{ $document->updated_at->year }}
                @endif
              </span>
            </div>
            <div class="doc-dl"><em>{{ $document->downloads }}×</em> {{ $document->isExternal() ? 'otevřeno' : 'staženo' }}</div>
            <div class="doc-actions">
              <a class="btn subtle" href="{{ $document->isExternal() ? $document->url : route('documents.download', $document) }}" target="_blank" rel="noopener">{{ $document->isExternal() ? 'Otevřít' : 'Stáhnout' }}</a>
              <button type="button" class="btn subtle" wire:click="openEdit({{ $document->id }})">Upravit</button>
              <button type="button" class="btn subtle" wire:click="toggleVisible({{ $document->id }})">{{ $document->visible ? 'Skrýt' : 'Zobrazit' }}</button>
              <button type="button" class="btn subtle" wire:click="delete({{ $document->id }})"
                      wire:confirm="Opravdu odstranit „{{ $document->title }}"? {{ $document->isExternal() ? '' : 'Soubor se smaže i z disku.' }}">Smazat</button>
            </div>
          </li>
        @endforeach
      </ul>
    @endif
  @endforeach

  @if ($groups->every(fn ($g) => $g['documents']->isEmpty()))
    <div class="empty-note reveal" style="--i: 3">Zatím žádné dokumenty — nahrajte první tlačítkem „+ Nahrát soubor".</div>
  @endif

  {{-- ─── Nahrání / úprava ─── --}}
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
          <div class="eyebrow">{{ $editingId ? 'Úprava dokumentu' : 'Nový dokument' }}</div>
          <h3>{{ $editingId ? 'Upravit dokument' : 'Nahrát na web' }}</h3>
          <form wire:submit="save">
            @if (! $editingId)
              <label class="check">
                <input type="checkbox" wire:model.live="isExternal">
                Externí odkaz (místo PDF souboru)
              </label>
            @endif

            <div class="field">
              <label for="d-title">Název</label>
              <input type="text" id="d-title" wire:model="title" placeholder="Přihláška člena">
              <div class="field-bar"></div>
              @error('title') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
              <label for="d-meta">Popisek <span style="text-transform: none; letter-spacing: 0;">(zobrazí se pod názvem)</span></label>
              <input type="text" id="d-meta" wire:model="meta" placeholder="Přihláška do oddílu · Praha 8">
              <div class="field-bar"></div>
            </div>
            <div class="field">
              <label for="d-group">Skupina na stránce</label>
              <select id="d-group" wire:model="group">
                @foreach (\App\Enums\DocumentGroup::ordered() as $groupOption)
                  <option value="{{ $groupOption->value }}">{{ $groupOption->number() }} · {{ $groupOption->label() }}</option>
                @endforeach
              </select>
            </div>

            @if ($isExternal)
              <div class="field">
                <label for="d-url">Adresa odkazu</label>
                <input type="url" id="d-url" wire:model="url" placeholder="https://www.czechjudo.org/…">
                <div class="field-bar"></div>
                @error('url') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            @elseif (! $editingId)
              <div class="field">
                <label for="d-pdf">PDF soubor <span style="text-transform: none; letter-spacing: 0;">(max 20 MB)</span></label>
                <input type="file" id="d-pdf" wire:model="pdf" accept="application/pdf">
                <div wire:loading wire:target="pdf" class="field-error" style="color: var(--ink-light);">Nahrávám…</div>
                @error('pdf') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            @else
              <p style="margin: 0 0 22px; font-size: 12px; color: var(--ink-light);">Výměna souboru: smažte dokument a nahrajte nový. Název a popisek lze upravit kdykoli.</p>
            @endif

            <div class="modal-actions">
              <button type="submit" class="btn" wire:loading.attr="disabled" wire:target="pdf">{{ $editingId ? 'Uložit změny' : 'Nahrát a zveřejnit' }}</button>
              <button type="button" class="btn ghost" wire:click="closeModals">Zrušit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</section>
