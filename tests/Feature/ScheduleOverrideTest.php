<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\ScheduleOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class ScheduleOverrideTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-06-12'); // pátek
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_admin_can_cancel_regular_session(): void
    {
        $monday = '2026-06-15';

        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.rozvrh')
            ->call('openCancel')
            ->set('cancelDate', $monday)
            ->set('cancelIndex', 0)
            ->call('saveCancel')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $this->assertDatabaseHas('schedule_overrides', [
            'date' => $monday.' 00:00:00',
            'kind' => 'zruseno',
            'form' => 'Judo – Praha 8',
            'time' => '16:30–18:00',
        ]);
    }

    public function test_cancel_requires_choosing_a_session(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.rozvrh')
            ->call('openCancel')
            ->set('cancelDate', '2026-06-15')
            ->call('saveCancel')
            ->assertHasErrors(['cancelIndex']);

        $this->assertDatabaseCount('schedule_overrides', 0);
    }

    public function test_cancel_offers_no_sessions_on_free_day(): void
    {
        $component = Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.rozvrh')
            ->set('cancelDate', '2026-06-13'); // sobota — volno

        $this->assertSame([], $component->instance()->sessionsForCancelDate());
    }

    public function test_admin_can_add_extra_training(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.rozvrh')
            ->call('openExtra')
            ->set('extraDate', '2026-06-13')
            ->set('extraType', 'Randori')
            ->set('extraPlace', 'Praha 8')
            ->set('timeFrom', '10:00')
            ->set('timeTo', '11:30')
            ->call('saveExtra')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $this->assertDatabaseHas('schedule_overrides', [
            'kind' => 'extra',
            'type' => 'Randori',
            'time' => '10:00–11:30',
            'form' => null,
        ]);
    }

    public function test_extra_validates_time_order(): void
    {
        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.rozvrh')
            ->call('openExtra')
            ->set('extraDate', '2026-06-13')
            ->set('extraType', 'Judo')
            ->set('timeFrom', '11:00')
            ->set('timeTo', '10:00')
            ->call('saveExtra')
            ->assertHasErrors(['timeTo']);
    }

    public function test_delete_override_restores_session(): void
    {
        $override = ScheduleOverride::create([
            'date' => '2026-06-15', 'kind' => 'zruseno', 'type' => 'Judo',
            'place' => 'Praha 8', 'loc' => 'Za Invalidovnou 579/3',
            'time' => '16:30–18:00', 'form' => 'Judo – Praha 8',
        ]);

        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.rozvrh')
            ->call('deleteOverride', $override->id)
            ->assertDispatched('toast');

        $this->assertDatabaseCount('schedule_overrides', 0);
    }

    public function test_cancelled_date_is_not_bookable_in_inquiry_form(): void
    {
        ScheduleOverride::create([
            'date' => '2026-06-15', 'kind' => 'zruseno', 'type' => 'Judo',
            'place' => 'Praha 8', 'loc' => 'Za Invalidovnou 579/3',
            'time' => '16:30–18:00', 'form' => 'Judo – Praha 8',
        ]);

        $component = Livewire::test('inquiry-form')->set('trainingType', 'Judo – Praha 8');
        $dates = $component->instance()->availableDates();

        $this->assertArrayNotHasKey('2026-06-15', $dates);
        $this->assertArrayHasKey('2026-06-17', $dates); // středa beze změny

        // Zrušený termín neprojde ani validací.
        Livewire::test('inquiry-form')
            ->set('trainingType', 'Judo – Praha 8')
            ->set('name', 'Test Rodič')
            ->set('email', 'test@email.cz')
            ->set('date', '2026-06-15')
            ->set('consent', true)
            ->call('save')
            ->assertHasErrors(['date']);
    }

    public function test_extra_training_with_form_is_bookable(): void
    {
        ScheduleOverride::create([
            'date' => '2026-06-13', 'kind' => 'extra', 'type' => 'Judo',
            'place' => 'Praha 8', 'loc' => 'Za Invalidovnou 579/3',
            'time' => '10:00–11:30', 'form' => 'Judo – Praha 8',
        ]);

        $dates = Livewire::test('inquiry-form')
            ->set('trainingType', 'Judo – Praha 8')
            ->instance()->availableDates();

        $this->assertArrayHasKey('2026-06-13', $dates); // sobota navíc
    }

    public function test_dashboard_skips_cancelled_and_includes_extra(): void
    {
        // Pondělní Judo – Praha 8 zrušeno, v sobotu mimořádný trénink.
        ScheduleOverride::create([
            'date' => '2026-06-15', 'kind' => 'zruseno', 'type' => 'Judo',
            'place' => 'Praha 8', 'loc' => 'Za Invalidovnou 579/3',
            'time' => '16:30–18:00', 'form' => 'Judo – Praha 8',
        ]);
        ScheduleOverride::create([
            'date' => '2026-06-13', 'kind' => 'extra', 'type' => 'Randori',
            'place' => 'Praha 8', 'loc' => null, 'time' => '10:00–11:30', 'form' => null,
        ]);

        Livewire::actingAs(User::factory()->create())
            ->test('pages.admin.prehled')
            ->assertViewHas('upcomingTrainings', function (array $trainings) {
                $first = $trainings[0];
                $noCancelled = collect($trainings)->doesntContain(
                    fn ($t) => ($t['form'] ?? null) === 'Judo – Praha 8' && $t['date']->isSameDay('2026-06-15'),
                );

                return $first['type'] === 'Randori' && $noCancelled && count($trainings) === 3;
            });
    }

    public function test_homepage_calendar_receives_events_and_overrides(): void
    {
        Event::factory()->create(['starts_on' => '2026-06-21', 'title' => 'Pochod na Rip']);
        ScheduleOverride::create([
            'date' => '2026-06-15', 'kind' => 'zruseno', 'type' => 'Judo',
            'place' => 'Praha 8', 'loc' => 'Za Invalidovnou 579/3',
            'time' => '16:30–18:00', 'form' => 'Judo – Praha 8',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('"from":"2026-06-21"', false)
            ->assertSee('"date":"2026-06-15"', false)
            ->assertSee('Akce klubu'); // legenda kalendáře
    }

    public function test_event_contact_prefills_inquiry_message(): void
    {
        Livewire::test('inquiry-form')
            ->dispatch('inquiry-prefill', trainingType: 'Obecný dotaz', message: 'Dotaz k akci „Letní tábor JUDO" (15. 8. — 22. 8. 2026): ')
            ->assertSet('trainingType', 'Obecný dotaz')
            ->assertSet('message', 'Dotaz k akci „Letní tábor JUDO" (15. 8. — 22. 8. 2026): ');

        // Rozepsanou zprávu uživatele nepřepíše…
        Livewire::test('inquiry-form')
            ->set('message', 'Moje vlastní zpráva')
            ->dispatch('inquiry-prefill', message: 'Dotaz k akci „X": ')
            ->assertSet('message', 'Moje vlastní zpráva');

        // …ale předchozí předvyplnění jiné akce ano.
        Livewire::test('inquiry-form')
            ->set('message', 'Dotaz k akci „A": ')
            ->dispatch('inquiry-prefill', message: 'Dotaz k akci „B": ')
            ->assertSet('message', 'Dotaz k akci „B": ');
    }

    public function test_events_page_has_contact_buttons(): void
    {
        Event::factory()->create(['starts_on' => '2026-08-15', 'title' => 'Letní tábor JUDO']);

        $this->get('/akce')
            ->assertOk()
            ->assertSee('Zeptat se na akci')
            ->assertSee('Napište nám'); // formulář v popupu
    }
}
