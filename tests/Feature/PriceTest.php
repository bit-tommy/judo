<?php

namespace Tests\Feature;

use App\Models\Price;
use App\Models\User;
use Database\Seeders\PriceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PriceTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_client_pricing(): void
    {
        $this->seed(PriceSeeder::class);

        $this->assertDatabaseCount('prices', 3);
        $this->assertSame(3000, Price::where('title', 'Judo — Praha')->first()->amount);
        $this->assertSame(2000, Price::where('title', 'Judo — Vodochody')->first()->amount);
        $this->assertSame(3000, Price::where('title', 'Taijutsu')->first()->amount);
    }

    public function test_public_page_renders_prices(): void
    {
        $this->seed(PriceSeeder::class);

        $this->get('/cenik')
            ->assertOk()
            ->assertSee('Ceník')
            ->assertSee('Judo — Praha')
            ->assertSee('3 000 Kč', false)
            ->assertSee('Judo — Vodochody')
            ->assertSee('2 000 Kč', false)
            ->assertSee('Taijutsu')
            ->assertSee('3 měsíce');
    }

    public function test_hidden_price_is_not_on_public_page(): void
    {
        Price::factory()->create(['title' => 'Viditelná položka']);
        Price::factory()->hidden()->create(['title' => 'Skrytá položka']);

        $this->get('/cenik')
            ->assertOk()
            ->assertSee('Viditelná položka')
            ->assertDontSee('Skrytá položka');
    }

    public function test_public_page_shows_empty_state(): void
    {
        $this->get('/cenik')
            ->assertOk()
            ->assertSee('Ceník právě aktualizujeme');
    }

    public function test_old_pricing_url_redirects(): void
    {
        $this->get('/treninky/cenik')->assertRedirect('/cenik');
    }

    public function test_sitemap_contains_pricing_page(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertSee('/cenik');
    }

    public function test_guest_cannot_open_pricing_admin(): void
    {
        $this->get('/admin/cenik')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_price(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.cenik')
            ->call('openCreate')
            ->set('title', 'Judo — Praha')
            ->set('amount', 3000)
            ->set('period', '3 měsíce')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $this->assertDatabaseHas('prices', [
            'title' => 'Judo — Praha',
            'amount' => 3000,
            'period' => '3 měsíce',
        ]);
    }

    public function test_create_validates_required_fields(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.cenik')
            ->call('openCreate')
            ->set('period', '')
            ->call('save')
            ->assertHasErrors(['title', 'amount', 'period']);

        $this->assertDatabaseCount('prices', 0);
    }

    public function test_admin_can_edit_toggle_and_delete_price(): void
    {
        $admin = User::factory()->create();
        $price = Price::factory()->create(['title' => 'Judo — Praha', 'amount' => 3000]);

        Livewire::actingAs($admin)
            ->test('pages.admin.cenik')
            ->call('openEdit', $price->id)
            ->assertSet('title', 'Judo — Praha')
            ->assertSet('amount', 3000)
            ->set('amount', 3500)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(3500, $price->fresh()->amount);

        Livewire::actingAs($admin)
            ->test('pages.admin.cenik')
            ->call('toggleVisible', $price->id);

        $this->assertFalse($price->fresh()->visible);

        Livewire::actingAs($admin)
            ->test('pages.admin.cenik')
            ->call('delete', $price->id);

        $this->assertDatabaseMissing('prices', ['id' => $price->id]);
    }

    public function test_amount_label_formats_thousands(): void
    {
        $price = Price::factory()->make(['amount' => 3000]);

        $this->assertSame('3 000 Kč', $price->amountLabel());
    }
}
