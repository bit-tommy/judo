<?php

namespace Tests\Feature;

use App\Models\SiteVisit;
use App\Models\User;
use App\Support\BotUserAgent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private const BROWSER_UA = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0 Safari/537.36';

    public function test_visit_is_recorded_once_per_day_for_same_visitor(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/akce')->assertOk();

        $this->assertDatabaseCount('site_visits', 1);

        $token = 'testovaci-token';
        $hash = hash('sha256', $token);

        // Stejný návštěvník (cookie) tentýž den → žádný další záznam.
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])
            ->withUnencryptedCookie('rr_visitor', $token)
            ->get('/akce')->assertOk();
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])
            ->withUnencryptedCookie('rr_visitor', $token)
            ->get('/ke-stazeni')->assertOk();

        $this->assertDatabaseCount('site_visits', 2);
        $this->assertDatabaseHas('site_visits', [
            'visitor_hash' => $hash,
            'visit_date' => today()->toDateString(),
        ]);
    }

    public function test_new_visitor_gets_cookie(): void
    {
        $response = $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/akce');

        $response->assertOk()->assertCookie('rr_visitor');
    }

    public function test_bots_are_not_recorded(): void
    {
        $this->withHeaders(['User-Agent' => 'Googlebot/2.1 (+http://www.google.com/bot.html)'])->get('/akce')->assertOk();
        $this->withHeaders(['User-Agent' => 'curl/8.4.0'])->get('/akce')->assertOk();
        $this->withHeaders(['User-Agent' => 'Symfony'])->get('/akce')->assertOk(); // výchozí UA testovacího klienta

        $this->assertDatabaseCount('site_visits', 0);
    }

    public function test_admin_pages_are_not_recorded(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->withHeaders(['User-Agent' => self::BROWSER_UA])
            ->get('/admin')->assertOk();

        $this->assertDatabaseCount('site_visits', 0);
    }

    public function test_non_200_responses_are_not_recorded(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/neexistujici-stranka')->assertNotFound();
        $this->withHeaders(['User-Agent' => self::BROWSER_UA])->get('/aktuality/plan-akci')->assertRedirect();

        $this->assertDatabaseCount('site_visits', 0);
    }

    public function test_bot_user_agent_detection(): void
    {
        $this->assertTrue(BotUserAgent::matches(null));
        $this->assertTrue(BotUserAgent::matches(''));
        $this->assertTrue(BotUserAgent::matches('Googlebot/2.1'));
        $this->assertTrue(BotUserAgent::matches('Mozilla/5.0 (compatible; AhrefsBot/7.0)'));
        $this->assertTrue(BotUserAgent::matches('python-requests/2.31'));

        $this->assertFalse(BotUserAgent::matches(self::BROWSER_UA));
        $this->assertFalse(BotUserAgent::matches('Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) Safari/604.1'));
    }

    public function test_analytics_page_shows_stats(): void
    {
        $admin = User::factory()->create();

        SiteVisit::insert([
            ['visitor_hash' => hash('sha256', 'a'), 'visit_date' => today()->toDateString(), 'created_at' => now()],
            ['visitor_hash' => hash('sha256', 'b'), 'visit_date' => today()->toDateString(), 'created_at' => now()],
            ['visitor_hash' => hash('sha256', 'a'), 'visit_date' => today()->subDays(3)->toDateString(), 'created_at' => now()],
        ]);

        $this->actingAs($admin)
            ->get('/admin/analytika')
            ->assertOk()
            ->assertSee('Návštěvnost webu')
            ->assertSee('Unikátní návštěvníci po dnech')
            ->assertSee('Nejstahovanější dokumenty');
    }

    public function test_guest_cannot_open_analytics(): void
    {
        $this->get('/admin/analytika')->assertRedirect(route('admin.login'));
    }
}
