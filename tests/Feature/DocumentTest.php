<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Database\Seeders\DocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private string $documentsDir;

    protected function setUp(): void
    {
        parent::setUp();

        // Soubory se nikdy nesmí zapisovat do public/ — testy běží v tmp.
        $this->documentsDir = sys_get_temp_dir().'/judo-test-docs-'.uniqid();
        File::ensureDirectoryExists($this->documentsDir);
        config(['documents.path' => $this->documentsDir]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->documentsDir);

        parent::tearDown();
    }

    public function test_seeder_creates_all_rows_from_old_page(): void
    {
        file_put_contents($this->documentsDir.'/GOkyo.pdf', str_repeat('x', 2048));

        $this->seed(DocumentSeeder::class);

        $this->assertDatabaseCount('documents', 12);
        $this->assertSame(10, Document::where('type', 'file')->count());
        $this->assertSame(2, Document::where('type', 'external')->count());
        $this->assertSame(2048, Document::where('title', 'Go-Kyo')->first()->size_bytes);
    }

    public function test_public_page_renders_documents_from_database(): void
    {
        Document::factory()->create(['title' => 'Přihláška člena', 'meta' => 'Přihláška do oddílu · Praha 8']);
        Document::factory()->external()->create(['title' => 'Informace GDPR ČSJu']);

        $this->get('/ke-stazeni')
            ->assertOk()
            ->assertSee('Přihláška člena')
            ->assertSee('Přihláška do oddílu · Praha 8')
            ->assertSee('Informace GDPR ČSJu')
            ->assertSee('Externí odkazy');
    }

    public function test_hidden_document_is_not_on_public_page(): void
    {
        Document::factory()->hidden()->create(['title' => 'Skrytý dokument']);

        $this->get('/ke-stazeni')
            ->assertOk()
            ->assertDontSee('Skrytý dokument');
    }

    public function test_download_streams_file_and_increments_counter(): void
    {
        file_put_contents($this->documentsDir.'/test.pdf', '%PDF-1.4 test');
        $document = Document::factory()->create(['filename' => 'test.pdf']);

        $this->get(route('documents.download', $document))->assertOk();

        $this->assertSame(1, $document->fresh()->downloads);
    }

    public function test_download_redirects_external_and_increments_counter(): void
    {
        $document = Document::factory()->external()->create(['url' => 'https://www.czechjudo.org/test']);

        $this->get(route('documents.download', $document))
            ->assertRedirect('https://www.czechjudo.org/test');

        $this->assertSame(1, $document->fresh()->downloads);
    }

    public function test_download_of_hidden_document_returns_404(): void
    {
        $document = Document::factory()->hidden()->create();

        $this->get(route('documents.download', $document))->assertNotFound();

        $this->assertSame(0, $document->fresh()->downloads);
    }

    public function test_admin_can_upload_pdf(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test('pages.admin.dokumenty')
            ->call('openCreate')
            ->set('title', 'Nový řád klubu')
            ->set('meta', 'Vnitřní řád')
            ->set('group', 'prihlasky')
            ->set('pdf', UploadedFile::fake()->createWithContent('rad.pdf', '%PDF-1.4 '.str_repeat('obsah ', 200)))
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $document = Document::where('title', 'Nový řád klubu')->first();
        $this->assertNotNull($document);
        $this->assertSame('novy-rad-klubu.pdf', $document->filename);
        $this->assertFileExists($this->documentsDir.'/novy-rad-klubu.pdf');
        $this->assertGreaterThan(0, $document->size_bytes);
    }

    public function test_upload_rejects_non_pdf(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test('pages.admin.dokumenty')
            ->call('openCreate')
            ->set('title', 'Obrázek')
            ->set('pdf', UploadedFile::fake()->image('fotka.jpg'))
            ->call('save')
            ->assertHasErrors(['pdf']);

        $this->assertDatabaseCount('documents', 0);
    }

    public function test_admin_can_add_external_link(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test('pages.admin.dokumenty')
            ->call('openCreate')
            ->set('isExternal', true)
            ->set('title', 'Pravidla juda')
            ->set('group', 'externi')
            ->set('url', 'https://www.czechjudo.org/pravidla')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('documents', [
            'title' => 'Pravidla juda',
            'type' => 'external',
            'url' => 'https://www.czechjudo.org/pravidla',
        ]);
    }

    public function test_toggle_visible_hides_document(): void
    {
        $admin = User::factory()->create();
        $document = Document::factory()->create();

        Livewire::actingAs($admin)
            ->test('pages.admin.dokumenty')
            ->call('toggleVisible', $document->id);

        $this->assertFalse($document->fresh()->visible);
    }

    public function test_delete_removes_file_unless_shared(): void
    {
        $admin = User::factory()->create();
        file_put_contents($this->documentsDir.'/sdileny.pdf', 'pdf');

        $first = Document::factory()->create(['title' => 'První', 'filename' => 'sdileny.pdf']);
        $second = Document::factory()->create(['title' => 'Druhý', 'filename' => 'sdileny.pdf']);

        Livewire::actingAs($admin)
            ->test('pages.admin.dokumenty')
            ->call('delete', $first->id);

        // Soubor sdílí druhý dokument → zůstává.
        $this->assertFileExists($this->documentsDir.'/sdileny.pdf');

        Livewire::actingAs($admin)
            ->test('pages.admin.dokumenty')
            ->call('delete', $second->id);

        $this->assertFileDoesNotExist($this->documentsDir.'/sdileny.pdf');
        $this->assertDatabaseCount('documents', 0);
    }

    public function test_guest_cannot_open_documents_admin(): void
    {
        $this->get('/admin/dokumenty')->assertRedirect(route('admin.login'));
    }
}
