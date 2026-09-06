<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CampPopupTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_camp_popup_shows_on_homepage_before_cutoff(): void
    {
        Carbon::setTestNow('2026-09-10');

        $this->get('/')->assertSee('Víkendový kemp Nebákov');
    }

    public function test_camp_popup_still_shows_on_last_day_before_camp(): void
    {
        Carbon::setTestNow('2026-09-24 23:59:00');

        $this->get('/')->assertSee('Víkendový kemp Nebákov');
    }

    public function test_camp_popup_hides_from_camp_departure_day(): void
    {
        Carbon::setTestNow('2026-09-25 00:00:00');

        $this->get('/')->assertDontSee('Víkendový kemp Nebákov');
    }

    public function test_camp_popup_does_not_show_on_other_pages(): void
    {
        Carbon::setTestNow('2026-09-10');

        $this->get('/treninky-deti')->assertDontSee('Víkendový kemp Nebákov');
    }
}
