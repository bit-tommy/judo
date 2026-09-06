<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Tests\TestCase;

class GalleryImporterTest extends TestCase
{
    use RefreshDatabase;

    private string $mediaDir;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Média se nikdy nesmí zapisovat do public/ — testy běží v tmp.
        $this->mediaDir = sys_get_temp_dir().'/judo-test-media-'.uniqid();
        File::ensureDirectoryExists($this->mediaDir);
        config(['gallery.media_path' => $this->mediaDir]);

        $this->admin = User::factory()->create();
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->mediaDir);

        parent::tearDown();
    }

    public function test_admin_can_create_album_with_photos(): void
    {
        $test = Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openCreate')
            ->set('title', 'Testovací soustředění')
            ->set('year', 2026)
            ->set('month', 6)
            ->set('cats', ['soustredeni'])
            ->call('save')
            ->assertHasNoErrors();

        $albumId = GalleryAlbum::where('slug', 'testovaci-soustredeni')->value('id');
        $this->assertNotNull($albumId);

        $test->set('photos', [
            UploadedFile::fake()->image('Foto Jedna.jpg', 1200, 900),
            UploadedFile::fake()->image('foto2.png', 700, 500),
        ])
            ->call('appendChunk', $albumId)
            ->assertHasNoErrors()
            ->call('finishUpload', true)
            ->assertDispatched('toast');

        $albumDir = $this->mediaDir.'/2026/testovaci-soustredeni';

        // Soubory: plná velikost + náhled, vše normalizované na .jpg.
        $this->assertFileExists($albumDir.'/foto-jedna.jpg');
        $this->assertFileExists($albumDir.'/thumb/foto-jedna.jpg');
        $this->assertFileExists($albumDir.'/foto2.jpg');
        $this->assertFileExists($albumDir.'/thumb/foto2.jpg');

        // album.json má přesně tvar generovaný scraperem.
        $json = json_decode((string) file_get_contents($albumDir.'/album.json'), true);
        $this->assertSame('Testovací soustředění', $json['title']);
        $this->assertSame('Červen 2026', $json['date']);
        $this->assertCount(2, $json['photos']);
        $this->assertSame(['t', 'f', 'c'], array_keys($json['photos'][0]));
        $this->assertSame('/galerie-media/2026/testovaci-soustredeni/thumb/foto-jedna.jpg', $json['photos'][0]['t']);

        // DB záznam.
        $album = GalleryAlbum::find($albumId);
        $this->assertSame(2, $album->photos);
        $this->assertSame(['soustredeni'], $album->cats);
        $this->assertSame($json['photos'][0]['t'], $album->cover);
    }

    public function test_save_creates_empty_album_without_photos(): void
    {
        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openCreate')
            ->set('title', 'Prázdné album')
            ->set('year', 2026)
            ->set('month', 3)
            ->set('cats', ['klub'])
            ->call('save')
            ->assertHasNoErrors();

        $album = GalleryAlbum::where('slug', 'prazdne-album')->firstOrFail();
        $this->assertSame(0, $album->photos);
        $this->assertNull($album->cover);

        $json = json_decode((string) file_get_contents($album->dirPath().'/album.json'), true);
        $this->assertSame([], $json['photos']);
    }

    public function test_images_are_downscaled_to_configured_widths(): void
    {
        config(['gallery.thumb_width' => 300, 'gallery.max_width' => 800]);

        $album = $this->createAlbumViaAdmin('Velké fotky', []);

        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openAddPhotos', $album->id)
            ->set('photos', [UploadedFile::fake()->image('velka.jpg', 1600, 1200)])
            ->call('appendChunk', $album->id)
            ->assertHasNoErrors();

        $dir = $album->dirPath();

        [$fullWidth] = getimagesize($dir.'/velka.jpg');
        [$thumbWidth] = getimagesize($dir.'/thumb/velka.jpg');

        $this->assertSame(800, $fullWidth);
        $this->assertSame(300, $thumbWidth);
    }

    public function test_admin_can_append_photos_to_album(): void
    {
        $album = $this->createAlbumViaAdmin();

        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openAddPhotos', $album->id)
            ->set('photos', [UploadedFile::fake()->image('dalsi.jpg', 600, 400)])
            ->call('appendChunk', $album->id)
            ->assertHasNoErrors()
            ->call('finishUpload', false)
            ->assertDispatched('toast');

        $this->assertSame(2, $album->fresh()->photos);

        $json = json_decode((string) file_get_contents($album->dirPath().'/album.json'), true);
        $this->assertCount(2, $json['photos']);
    }

    public function test_photos_are_sorted_chronologically_by_filename(): void
    {
        $album = $this->createAlbumViaAdmin('Řazení', []);

        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openAddPhotos', $album->id)
            ->set('photos', [
                UploadedFile::fake()->image('b.jpg', 200, 200),
                UploadedFile::fake()->image('a.jpg', 200, 200),
            ])
            ->call('appendChunk', $album->id)
            ->assertHasNoErrors();

        $json = json_decode((string) file_get_contents($album->dirPath().'/album.json'), true);
        $this->assertStringContainsString('/a.jpg', $json['photos'][0]['f']);
        $this->assertStringContainsString('/b.jpg', $json['photos'][1]['f']);
    }

    public function test_admin_can_edit_album_title_and_cats(): void
    {
        $album = $this->createAlbumViaAdmin();

        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openEdit', $album->id)
            ->assertSet('title', 'Původní album')
            ->set('title', 'Přejmenované album')
            ->set('cats', ['klub', 'zavody'])
            ->call('updateAlbum')
            ->assertHasNoErrors();

        $fresh = $album->fresh();
        $this->assertSame('Přejmenované album', $fresh->title);
        $this->assertSame(['klub', 'zavody'], $fresh->cats);

        // Titul se propsal i do album.json (čte ho lightbox na webu).
        $json = json_decode((string) file_get_contents($album->dirPath().'/album.json'), true);
        $this->assertSame('Přejmenované album', $json['title']);
    }

    public function test_delete_removes_directory_and_row(): void
    {
        $album = $this->createAlbumViaAdmin();
        $dir = $album->dirPath();

        $this->assertDirectoryExists($dir);

        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('delete', $album->id);

        $this->assertDirectoryDoesNotExist($dir);
        $this->assertDatabaseMissing('gallery_albums', ['id' => $album->id]);
    }

    public function test_save_requires_cats_and_creates_no_album(): void
    {
        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openCreate')
            ->set('title', 'Chybné album')
            ->set('cats', [])
            ->call('save')
            ->assertHasErrors(['cats']);

        $this->assertDatabaseCount('gallery_albums', 0);
    }

    public function test_append_chunk_rejects_non_image(): void
    {
        $album = $this->createAlbumViaAdmin();

        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openAddPhotos', $album->id)
            ->set('photos', [UploadedFile::fake()->create('dokument.pdf', 10, 'application/pdf')])
            ->call('appendChunk', $album->id)
            ->assertHasErrors(['photos.0']);

        $this->assertSame(1, $album->fresh()->photos);
    }

    public function test_append_chunk_rejects_more_than_twenty_photos(): void
    {
        $album = $this->createAlbumViaAdmin();

        $photos = array_map(
            fn (int $i) => UploadedFile::fake()->image("foto-{$i}.jpg", 100, 100),
            range(1, 21),
        );

        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openAddPhotos', $album->id)
            ->set('photos', $photos)
            ->call('appendChunk', $album->id)
            ->assertHasErrors(['photos']);
    }

    public function test_public_gallery_shows_admin_album(): void
    {
        $album = $this->createAlbumViaAdmin();

        $this->get('/galerie')
            ->assertOk()
            ->assertSee('Původní album')
            ->assertSee($album->slug);
    }

    public function test_admin_gallery_page_lists_merged_albums(): void
    {
        $this->createAlbumViaAdmin();

        $this->actingAs($this->admin)
            ->get('/admin/galerie')
            ->assertOk()
            ->assertSee('Fotoalba klubu')
            ->assertSee('Původní album');
    }

    /** @param array<int, UploadedFile>|null $photos */
    public function test_exif_date_prefixes_filename_and_sorts_before_undated(): void
    {
        $album = $this->createAlbumViaAdmin('Exif album', [
            UploadedFile::fake()->image('aaa.jpg', 100, 100),
            \Illuminate\Http\Testing\File::createWithContent('IMG 0001.jpg', file_get_contents(base_path('tests/Fixtures/exif-datetime.jpg'))),
        ]);

        $json = json_decode(file_get_contents($album->dirPath().'/album.json'), true);
        $names = array_map(fn (array $photo) => basename($photo['f']), $json['photos']);

        $this->assertSame(['20260815-213906-img-0001.jpg', 'aaa.jpg'], $names);
        $this->assertFileExists($album->dirPath().'/thumb/20260815-213906-img-0001.jpg');
        $this->assertSame($json['photos'][0]['t'], $album->fresh()->cover);
    }

    public function test_placeholder_exif_date_falls_back_to_plain_name(): void
    {
        // Některé fotoaparáty zapisují nevyplněné datum jako 0000:00:00 00:00:00.
        $bytes = str_replace('2026:08:15 21:39:06', '0000:00:00 00:00:00', file_get_contents(base_path('tests/Fixtures/exif-datetime.jpg')));

        $album = $this->createAlbumViaAdmin('Exif nula', [
            \Illuminate\Http\Testing\File::createWithContent('IMG 0002.jpg', $bytes),
        ]);

        $this->assertFileExists($album->dirPath().'/img-0002.jpg');
    }

    /**
     * Alpine výraz v x-data je HTML atribut v uvozovkách – rovná uvozovka
     * uvnitř (třeba v komentáři) ho usekne a celý modal přestane fungovat
     * („Unexpected token“, „running is not defined“). Hlídáme, že se výraz
     * dostane až na konec funkce.
     */
    public function test_upload_modal_alpine_expressions_are_not_cut_by_quotes(): void
    {
        $component = Livewire::actingAs($this->admin)->test('pages.admin.galerie')->call('openCreate');
        $this->assertStringContainsString(
            'await this.run(this.albumId, true, files)',
            $this->alpineExpressionContaining($component->html(), 'async create()'),
        );

        $album = $this->createAlbumViaAdmin();
        $component->call('openAddPhotos', $album->id);
        $this->assertStringContainsString(
            'await this.run(albumId, false, files)',
            $this->alpineExpressionContaining($component->html(), 'async append('),
        );
    }

    /** Vrátí hodnotu atributu x-data (tak, jak ji vidí prohlížeč), která obsahuje daný úryvek. */
    private function alpineExpressionContaining(string $html, string $needle): string
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_NOERROR | LIBXML_NOWARNING);

        foreach ((new \DOMXPath($dom))->query('//*[@x-data]') as $element) {
            $expression = $element->getAttribute('x-data');

            if (str_contains($expression, $needle)) {
                return $expression;
            }
        }

        $this->fail("Žádný x-data výraz neobsahuje „{$needle}“.");
    }

    private function createAlbumViaAdmin(string $title = 'Původní album', ?array $photos = null): GalleryAlbum
    {
        $photos ??= [UploadedFile::fake()->image('prvni.jpg', 640, 480)];

        $test = Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openCreate')
            ->set('title', $title)
            ->set('year', 2026)
            ->set('month', 5)
            ->set('cats', ['klub'])
            ->call('save')
            ->assertHasNoErrors();

        $album = GalleryAlbum::where('title', $title)->firstOrFail();

        if ($photos !== []) {
            $test->set('photos', $photos)
                ->call('appendChunk', $album->id)
                ->assertHasNoErrors();
        }

        return $album->fresh();
    }
}
