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
        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openCreate')
            ->set('title', 'Testovací soustředění')
            ->set('year', 2026)
            ->set('month', 6)
            ->set('cats', ['soustredeni'])
            ->set('photos', [
                UploadedFile::fake()->image('Foto Jedna.jpg', 1200, 900),
                UploadedFile::fake()->image('foto2.png', 700, 500),
            ])
            ->call('save')
            ->assertHasNoErrors()
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
        $album = GalleryAlbum::where('slug', 'testovaci-soustredeni')->first();
        $this->assertNotNull($album);
        $this->assertSame(2, $album->photos);
        $this->assertSame(['soustredeni'], $album->cats);
        $this->assertSame($json['photos'][0]['t'], $album->cover);
    }

    public function test_images_are_downscaled_to_configured_widths(): void
    {
        config(['gallery.thumb_width' => 300, 'gallery.max_width' => 800]);

        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openCreate')
            ->set('title', 'Velké fotky')
            ->set('year', 2026)
            ->set('month', 1)
            ->set('cats', ['klub'])
            ->set('photos', [UploadedFile::fake()->image('velka.jpg', 1600, 1200)])
            ->call('save')
            ->assertHasNoErrors();

        $dir = $this->mediaDir.'/2026/velke-fotky';

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
            ->call('savePhotos')
            ->assertHasNoErrors();

        $this->assertSame(2, $album->fresh()->photos);

        $json = json_decode((string) file_get_contents($album->dirPath().'/album.json'), true);
        $this->assertCount(2, $json['photos']);
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

    public function test_validation_rejects_non_image_and_missing_cats(): void
    {
        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openCreate')
            ->set('title', 'Chybné album')
            ->set('cats', [])
            ->set('photos', [UploadedFile::fake()->create('dokument.pdf', 10, 'application/pdf')])
            ->call('save')
            ->assertHasErrors(['cats', 'photos.0']);

        $this->assertDatabaseCount('gallery_albums', 0);
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

    private function createAlbumViaAdmin(): GalleryAlbum
    {
        Livewire::actingAs($this->admin)
            ->test('pages.admin.galerie')
            ->call('openCreate')
            ->set('title', 'Původní album')
            ->set('year', 2026)
            ->set('month', 5)
            ->set('cats', ['klub'])
            ->set('photos', [UploadedFile::fake()->image('prvni.jpg', 640, 480)])
            ->call('save')
            ->assertHasNoErrors();

        return GalleryAlbum::where('title', 'Původní album')->firstOrFail();
    }
}
