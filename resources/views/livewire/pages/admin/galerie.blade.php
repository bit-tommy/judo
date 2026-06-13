<?php

use App\Models\GalleryAlbum;
use App\Support\GalleryImporter;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('components.layouts.admin')]
#[Title('Galerie | Administrace JC Raion-Ryu')]
class extends Component {
    use WithFileUploads;

    private const CZ_MONTHS = ['', 'Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen',
        'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];

    public bool $showForm = false;

    /** Album, do kterého se přidávají fotky (modal „Přidat fotky"). */
    public ?int $addingToId = null;

    /** Album v úpravě (jen titul + kategorie). */
    public ?int $editingId = null;

    public string $title = '';

    public int $year = 2026;

    public int $month = 1;

    /** @var array<int, string> */
    public array $cats = [];

    /** @var array<int, \Illuminate\Http\UploadedFile> */
    public array $photos = [];

    public function mount(): void
    {
        $this->year = (int) now()->year;
        $this->month = (int) now()->month;
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openAddPhotos(int $albumId): void
    {
        $this->resetForm();
        $this->addingToId = GalleryAlbum::findOrFail($albumId)->id;
    }

    public function openEdit(int $albumId): void
    {
        $album = GalleryAlbum::findOrFail($albumId);

        $this->resetForm();
        $this->editingId = $album->id;
        $this->title = $album->title;
        $this->cats = $album->cats ?? [];
    }

    public function closeModals(): void
    {
        $this->reset(['showForm', 'addingToId', 'editingId']);
    }

    /** Založení nového alba (s fotkami). */
    public function save(): void
    {
        $validated = $this->validate(
            [
                'title' => 'required|string|max:160',
                'year' => 'required|integer|min:2000|max:2100',
                'month' => 'required|integer|min:1|max:12',
                'cats' => 'required|array|min:1',
                'cats.*' => 'string|in:'.implode(',', array_keys(config('content.gallery.categories', []))),
                'photos' => 'required|array|min:1|max:80',
                'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:20480',
            ],
            $this->validationMessages(),
        );

        $dateLabel = self::CZ_MONTHS[$this->month].' '.$this->year;

        try {
            $result = app(GalleryImporter::class)->create(
                $validated['title'],
                $this->year,
                $dateLabel,
                array_values($this->cats),
                $this->photos,
            );
        } catch (\RuntimeException $e) {
            $this->addError('photos', $e->getMessage());

            return;
        }

        GalleryAlbum::create([
            'slug' => $result['slug'],
            'title' => $validated['title'],
            'date_label' => $dateLabel,
            'year' => $this->year,
            'cats' => array_values($this->cats),
            'photos' => $result['photos'],
            'cover' => $result['cover'],
        ]);

        $this->closeModals();
        $this->resetForm();
        $this->dispatch('toast', message: 'Album bylo vytvořeno a je viditelné v galerii.');
    }

    /** Přidání fotek do existujícího alba. */
    public function savePhotos(): void
    {
        $this->validate(
            [
                'photos' => 'required|array|min:1|max:80',
                'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:20480',
            ],
            $this->validationMessages(),
        );

        $album = GalleryAlbum::findOrFail($this->addingToId);

        try {
            $result = app(GalleryImporter::class)->append($album, $this->photos);
        } catch (\RuntimeException $e) {
            $this->addError('photos', $e->getMessage());

            return;
        }

        $album->update([
            'photos' => $result['photos'],
            'cover' => $album->cover ?? $result['cover'],
        ]);

        $this->closeModals();
        $this->resetForm();
        $this->dispatch('toast', message: 'Fotky byly přidány do alba.');
    }

    /** Úprava titulu a kategorií alba. */
    public function updateAlbum(): void
    {
        $validated = $this->validate(
            [
                'title' => 'required|string|max:160',
                'cats' => 'required|array|min:1',
                'cats.*' => 'string|in:'.implode(',', array_keys(config('content.gallery.categories', []))),
            ],
            $this->validationMessages(),
        );

        $album = GalleryAlbum::findOrFail($this->editingId);
        $album->update([
            'title' => $validated['title'],
            'cats' => array_values($this->cats),
        ]);

        app(GalleryImporter::class)->rewriteMeta($album->fresh());

        $this->closeModals();
        $this->resetForm();
        $this->dispatch('toast', message: 'Změny alba byly uloženy.');
    }

    public function delete(int $albumId): void
    {
        $album = GalleryAlbum::findOrFail($albumId);

        app(GalleryImporter::class)->delete($album);
        $album->delete();

        $this->closeModals();
        $this->dispatch('toast', message: 'Album bylo smazáno včetně fotek.');
    }

    /** @return array<string, string> */
    private function validationMessages(): array
    {
        return [
            'title.required' => 'Vyplňte prosím název alba.',
            'cats.required' => 'Vyberte alespoň jednu kategorii.',
            'cats.min' => 'Vyberte alespoň jednu kategorii.',
            'photos.required' => 'Vyberte prosím fotky k nahrání.',
            'photos.max' => 'Najednou lze nahrát nejvýše 80 fotek.',
            'photos.*.image' => 'Nahrát lze jen obrázky (JPG, PNG, WEBP).',
            'photos.*.mimes' => 'Nahrát lze jen obrázky JPG, PNG nebo WEBP.',
            'photos.*.max' => 'Každá fotka může mít nejvýše 20 MB.',
        ];
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'cats', 'photos', 'showForm', 'addingToId', 'editingId']);
        $this->year = (int) now()->year;
        $this->month = (int) now()->month;
        $this->resetValidation();
    }

    public function with(): array
    {
        $scraperAlbums = collect(config('content.gallery.albums', []))
            ->map(fn (array $album) => $album + ['source' => 'scraper', 'id' => null]);

        $adminAlbums = GalleryAlbum::orderByDesc('year')->orderByDesc('id')->get()
            ->map(fn (GalleryAlbum $album) => $album->toPublicArray() + ['source' => 'admin', 'id' => $album->id]);

        $albums = $adminAlbums->concat($scraperAlbums)
            ->sortByDesc(fn (array $album) => [$album['year'], $album['source'] === 'admin' ? 1 : 0])
            ->values();

        return [
            'albums' => $albums,
            'totalAlbums' => $albums->count(),
            'totalPhotos' => $albums->sum('photos'),
            'categories' => config('content.gallery.categories', []),
            'editingAlbum' => $this->editingId !== null ? GalleryAlbum::find($this->editingId) : null,
            'addingToAlbum' => $this->addingToId !== null ? GalleryAlbum::find($this->addingToId) : null,
            'czMonths' => self::CZ_MONTHS,
        ];
    }
}; ?>

<section class="panel">
  <div class="main-head">
    <div>
      <div class="eyebrow reveal" style="--i: 0">Galerie</div>
      <h1 class="main-title reveal" style="--i: 1">Fotoalba klubu</h1>
    </div>
    <div class="main-date reveal" style="--i: 2">{{ $totalAlbums }} alb · {{ $totalPhotos }} fotek</div>
  </div>

  <div class="gal-grid">
    <button type="button" class="album upload reveal" style="--i: 3" wire:click="openCreate">
      <span class="plus">+</span>
      <span class="lbl">Nové album</span>
    </button>

    @foreach ($albums as $i => $album)
      <div class="album reveal" style="--i: {{ min(3 + $i, 9) }}" wire:key="album-{{ $album['source'] }}-{{ $album['slug'] }}">
        <a class="album-cover {{ $album['cover'] ? '' : 'ph' }}" href="{{ route('gallery') }}" target="_blank" rel="noopener" style="text-decoration: none;">
          @if ($album['cover'])
            <img src="{{ $album['cover'] }}" alt="{{ $album['title'] }}" loading="lazy">
          @else
            <span class="ph-tag">bez náhledu</span>
          @endif
          @if ($album['source'] === 'scraper')
            <span class="album-src">Rajče</span>
          @endif
          <span class="album-count">{{ $album['photos'] }} fotek{{ ($album['videos'] ?? 0) > 0 ? ' · '.$album['videos'].' videí' : '' }}</span>
        </a>
        <div class="album-info">
          <div>
            <div class="album-name">{{ $album['title'] }}</div>
            <div class="album-date">{{ $album['date'] }}</div>
          </div>
        </div>
        @if ($album['source'] === 'admin')
          <div class="album-tools">
            <button type="button" class="btn subtle" wire:click="openAddPhotos({{ $album['id'] }})">+ Fotky</button>
            <button type="button" class="btn subtle" wire:click="openEdit({{ $album['id'] }})">Upravit</button>
            <button type="button" class="btn subtle" wire:click="delete({{ $album['id'] }})"
                    wire:confirm="Opravdu smazat album „{{ $album['title'] }}" včetně všech fotek na disku?">Smazat</button>
          </div>
        @endif
      </div>
    @endforeach
  </div>

  {{-- ─── Nové album ─── --}}
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
          <div class="eyebrow">Nové album</div>
          <h3>Vytvořit album</h3>
          <form wire:submit="save">
            <div class="field">
              <label for="g-title">Název alba</label>
              <input type="text" id="g-title" wire:model="title" placeholder="Letní soustředění 2026">
              <div class="field-bar"></div>
              @error('title') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-row">
              <div class="field">
                <label for="g-month">Měsíc</label>
                <select id="g-month" wire:model="month">
                  @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">{{ $czMonths[$m] }}</option>
                  @endforeach
                </select>
              </div>
              <div class="field">
                <label for="g-year">Rok</label>
                <input type="number" id="g-year" wire:model="year" min="2000" max="2100">
                <div class="field-bar"></div>
                @error('year') <span class="field-error">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="field">
              <label>Kategorie</label>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px 16px; padding-top: 6px;">
                @foreach ($categories as $catSlug => $catLabel)
                  <label class="check" style="margin-bottom: 4px;">
                    <input type="checkbox" wire:model="cats" value="{{ $catSlug }}">
                    {{ $catLabel }}
                  </label>
                @endforeach
              </div>
              @error('cats') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
              <label for="g-photos">Fotky <span style="text-transform: none; letter-spacing: 0;">(JPG/PNG/WEBP, max 80 najednou)</span></label>
              <input type="file" id="g-photos" wire:model="photos" multiple accept="image/jpeg,image/png,image/webp">
              <div wire:loading wire:target="photos" class="field-error" style="color: var(--ink-light);">Nahrávám fotky…</div>
              @error('photos') <span class="field-error">{{ $message }}</span> @enderror
              @error('photos.*') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="modal-actions">
              <button type="submit" class="btn" wire:loading.attr="disabled" wire:target="photos, save">
                <span wire:loading.remove wire:target="save">Vytvořit album</span>
                <span wire:loading wire:target="save">Zpracovávám fotky…</span>
              </button>
              <button type="button" class="btn ghost" wire:click="closeModals">Zrušit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  {{-- ─── Přidat fotky ─── --}}
  @if ($addingToAlbum)
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
          <div class="eyebrow">Přidat fotky</div>
          <h3>{{ $addingToAlbum->title }}</h3>
          <form wire:submit="savePhotos">
            <div class="field">
              <label for="g-add-photos">Fotky <span style="text-transform: none; letter-spacing: 0;">(JPG/PNG/WEBP, max 80 najednou)</span></label>
              <input type="file" id="g-add-photos" wire:model="photos" multiple accept="image/jpeg,image/png,image/webp">
              <div wire:loading wire:target="photos" class="field-error" style="color: var(--ink-light);">Nahrávám fotky…</div>
              @error('photos') <span class="field-error">{{ $message }}</span> @enderror
              @error('photos.*') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="modal-actions">
              <button type="submit" class="btn" wire:loading.attr="disabled" wire:target="photos, savePhotos">
                <span wire:loading.remove wire:target="savePhotos">Přidat do alba</span>
                <span wire:loading wire:target="savePhotos">Zpracovávám fotky…</span>
              </button>
              <button type="button" class="btn ghost" wire:click="closeModals">Zrušit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  {{-- ─── Úprava alba ─── --}}
  @if ($editingAlbum)
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
          <div class="eyebrow">Úprava alba</div>
          <h3>Upravit album</h3>
          <form wire:submit="updateAlbum">
            <div class="field">
              <label for="g-edit-title">Název alba</label>
              <input type="text" id="g-edit-title" wire:model="title">
              <div class="field-bar"></div>
              @error('title') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="field">
              <label>Kategorie</label>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px 16px; padding-top: 6px;">
                @foreach ($categories as $catSlug => $catLabel)
                  <label class="check" style="margin-bottom: 4px;">
                    <input type="checkbox" wire:model="cats" value="{{ $catSlug }}">
                    {{ $catLabel }}
                  </label>
                @endforeach
              </div>
              @error('cats') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <div class="modal-actions">
              <button type="submit" class="btn">Uložit změny</button>
              <button type="button" class="btn ghost" wire:click="closeModals">Zrušit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
</section>
