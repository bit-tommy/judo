<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EventCalendarTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_calendar_route_returns_ical_file(): void
    {
        $event = Event::factory()->create(['starts_on' => '2026-10-10', 'ends_on' => null]);

        $response = $this->get(route('events.calendar', $event));

        $response->assertOk();
        $this->assertStringContainsString('text/calendar', (string) $response->headers->get('content-type'));

        $disposition = (string) $response->headers->get('content-disposition');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString('.ics', $disposition);
    }

    public function test_single_day_event_has_exclusive_end_date(): void
    {
        $event = Event::factory()->create(['starts_on' => '2026-10-10', 'ends_on' => null]);

        $this->get(route('events.calendar', $event))
            ->assertOk()
            ->assertSee('DTSTART;VALUE=DATE:20261010', false)
            ->assertSee('DTEND;VALUE=DATE:20261011', false);
    }

    public function test_multi_day_event_end_is_day_after_last_day(): void
    {
        $event = Event::factory()->create(['starts_on' => '2026-08-17', 'ends_on' => '2026-08-23']);

        $this->get(route('events.calendar', $event))
            ->assertOk()
            ->assertSee('DTSTART;VALUE=DATE:20260817', false)
            ->assertSee('DTEND;VALUE=DATE:20260824', false);
    }

    public function test_summary_and_location_are_escaped(): void
    {
        $event = Event::factory()->create([
            'title' => 'Soustředění, Nebákov; léto',
            'place' => 'Sokolovna, Karlín',
            'starts_on' => '2026-08-17',
            'ends_on' => null,
        ]);

        $this->get(route('events.calendar', $event))
            ->assertOk()
            ->assertSee('SUMMARY:Soustředění\, Nebákov\; léto', false)
            ->assertSee('LOCATION:Sokolovna\, Karlín', false);
    }

    public function test_calendar_body_has_required_vcalendar_envelope(): void
    {
        $event = Event::factory()->create(['starts_on' => '2026-10-10']);

        $response = $this->get(route('events.calendar', $event))->assertOk();

        foreach (['BEGIN:VCALENDAR', 'VERSION:2.0', 'BEGIN:VEVENT', 'UID:', 'DTSTAMP:', 'END:VEVENT', 'END:VCALENDAR'] as $needle) {
            $response->assertSee($needle, false);
        }
    }

    public function test_public_akce_page_shows_calendar_controls(): void
    {
        Carbon::setTestNow('2026-06-12');

        $event = Event::factory()->create(['title' => 'Letní soustředění', 'starts_on' => '2026-08-01']);

        $this->get('/akce')
            ->assertOk()
            ->assertSee('Stáhnout .ics')
            ->assertSee('calendar.google.com')
            ->assertSee(route('events.calendar', $event), false);
    }
}
