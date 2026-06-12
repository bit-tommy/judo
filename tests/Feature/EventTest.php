<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_date_range_formats_single_day_and_ranges(): void
    {
        $single = Event::factory()->make(['starts_on' => '2026-10-10', 'ends_on' => null]);
        $range = Event::factory()->make(['starts_on' => '2026-06-27', 'ends_on' => '2026-07-03']);
        $crossYear = Event::factory()->make(['starts_on' => '2026-12-28', 'ends_on' => '2027-01-02']);

        $this->assertSame('10. 10. 2026', $single->dateRange());
        $this->assertSame('27. 6. — 3. 7. 2026', $range->dateRange());
        $this->assertSame('28. 12. 2026 — 2. 1. 2027', $crossYear->dateRange());
    }

    public function test_month_abbreviations_are_czech(): void
    {
        $expected = [6 => 'Čer', 7 => 'Čvc', 8 => 'Srp', 10 => 'Říj', 12 => 'Pro'];

        foreach ($expected as $month => $abbr) {
            $event = Event::factory()->make(['starts_on' => sprintf('2026-%02d-05', $month)]);
            $this->assertSame($abbr, $event->monthAbbr());
        }
    }

    public function test_tag_logic(): void
    {
        Carbon::setTestNow('2026-06-12');

        $soon = Event::factory()->make(['starts_on' => '2026-06-27']);
        $tomorrow = Event::factory()->make(['starts_on' => '2026-06-13']);
        $main = Event::factory()->make(['starts_on' => '2026-08-17', 'is_main' => true]);
        $far = Event::factory()->make(['starts_on' => '2026-10-10']);
        $past = Event::factory()->make(['starts_on' => '2026-05-01']);
        $running = Event::factory()->make(['starts_on' => '2026-06-10', 'ends_on' => '2026-06-14']);

        $this->assertSame(['Za 15 dní', 'dark'], [$soon->tagLabel(), $soon->tagClass()]);
        $this->assertSame('Zítra', $tomorrow->tagLabel());
        $this->assertSame(['Hlavní akce', 'red'], [$main->tagLabel(), $main->tagClass()]);
        $this->assertSame(['Připravuje se', 'line'], [$far->tagLabel(), $far->tagClass()]);
        $this->assertSame(['Proběhlo', 'faint'], [$past->tagLabel(), $past->tagClass()]);
        $this->assertSame('Právě probíhá', $running->tagLabel());
    }

    public function test_scopes_split_upcoming_and_past(): void
    {
        Carbon::setTestNow('2026-06-12');

        Event::factory()->create(['title' => 'Budoucí akce', 'starts_on' => '2026-08-01']);
        Event::factory()->create(['title' => 'Probíhající akce', 'starts_on' => '2026-06-10', 'ends_on' => '2026-06-14']);
        Event::factory()->create(['title' => 'Minulá akce', 'starts_on' => '2026-05-01']);

        $this->assertSame(['Probíhající akce', 'Budoucí akce'], Event::upcoming()->pluck('title')->all());
        $this->assertSame(['Minulá akce'], Event::past()->pluck('title')->all());
    }

    public function test_public_events_page_renders_upcoming_and_past(): void
    {
        Carbon::setTestNow('2026-06-12');

        Event::factory()->create(['title' => 'Pobyt japonských mistrů', 'starts_on' => '2026-08-17', 'ends_on' => '2026-08-23', 'is_main' => true]);
        Event::factory()->create(['title' => 'Jarní turnaj', 'starts_on' => '2026-04-11', 'place' => 'Sokolovna Karlín']);

        $this->get('/akce')
            ->assertOk()
            ->assertSee('Akce klubu')
            ->assertSee('Pobyt japonských mistrů')
            ->assertSee('Hlavní akce')
            ->assertSee('Jarní turnaj')
            ->assertSee('Proběhlé akce');
    }

    public function test_public_events_page_shows_empty_states(): void
    {
        $this->get('/akce')
            ->assertOk()
            ->assertSee('Aktuálně nejsou naplánované žádné akce')
            ->assertSee('Archiv akcí se teprve plní');
    }

    public function test_admin_can_create_event(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test('pages.admin.akce')
            ->call('openCreate')
            ->set('title', 'Podzimní soustředění')
            ->set('startsOn', '2026-10-16')
            ->set('endsOn', '2026-10-18')
            ->set('place', 'Nebákov')
            ->set('isMain', false)
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $this->assertDatabaseHas('events', [
            'title' => 'Podzimní soustředění',
            'slug' => 'podzimni-soustredeni',
            'place' => 'Nebákov',
        ]);
    }

    public function test_event_validation_rejects_end_before_start(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test('pages.admin.akce')
            ->call('openCreate')
            ->set('title', 'Chybná akce')
            ->set('startsOn', '2026-10-16')
            ->set('endsOn', '2026-10-10')
            ->call('save')
            ->assertHasErrors(['endsOn']);
    }

    public function test_admin_can_edit_and_delete_event(): void
    {
        $admin = User::factory()->create();
        $event = Event::factory()->create(['title' => 'Stará akce']);

        Livewire::actingAs($admin)
            ->test('pages.admin.akce')
            ->call('openEdit', $event->id)
            ->assertSet('title', 'Stará akce')
            ->set('title', 'Nová akce')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Nová akce', $event->fresh()->title);

        Livewire::actingAs($admin)
            ->test('pages.admin.akce')
            ->call('delete', $event->id);

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_slug_is_unique_for_duplicate_titles(): void
    {
        $admin = User::factory()->create();

        foreach (range(1, 2) as $i) {
            Livewire::actingAs($admin)
                ->test('pages.admin.akce')
                ->call('openCreate')
                ->set('title', 'Vánoční randori')
                ->set('startsOn', '2026-12-1'.$i)
                ->call('save')
                ->assertHasNoErrors();
        }

        $this->assertSame(
            ['vanocni-randori', 'vanocni-randori-2'],
            Event::orderBy('id')->pluck('slug')->all(),
        );
    }

    public function test_sitemap_contains_events_page(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertSee('/akce');
    }

    public function test_old_news_urls_redirect_to_events(): void
    {
        $this->get('/aktuality/plan-akci')->assertRedirect('/akce');
        $this->get('/aktuality/probehle-akce')->assertRedirect('/akce');
    }
}
