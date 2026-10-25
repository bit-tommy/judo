<?php

namespace Tests\Feature;

use App\Support\CookieConsent;
use App\Support\SiteVisitRecorder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cookie;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Cookie lišta (resources/views/livewire/cookie-banner.blade.php), uložení
 * volby do cookie, napojení na počítání návštěvnosti a stránka /cookies.
 */
class CookieConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_banner_shows_on_public_pages_until_visitor_decides(): void
    {
        foreach (['/', '/cenik', '/galerie'] as $url) {
            $this->get($url)->assertOk()
                ->assertSee('Přijmout vše')
                ->assertSee('Pouze nezbytné');
        }
    }

    public function test_banner_hides_after_decision(): void
    {
        $this->withUnencryptedCookie(CookieConsent::COOKIE, CookieConsent::ALL)
            ->get('/')->assertOk()->assertDontSee('Přijmout vše');

        $this->withUnencryptedCookie(CookieConsent::COOKIE, CookieConsent::NECESSARY)
            ->get('/')->assertOk()->assertDontSee('Přijmout vše');
    }

    public function test_unknown_consent_value_counts_as_no_decision(): void
    {
        $this->withUnencryptedCookie(CookieConsent::COOKIE, 'cokoli')
            ->get('/')->assertOk()->assertSee('Přijmout vše');
    }

    public function test_banner_is_not_in_admin(): void
    {
        $this->get('/admin/login')->assertOk()->assertDontSee('Přijmout vše');
    }

    public function test_accept_all_stores_choice_and_records_current_visit(): void
    {
        Livewire::test('cookie-banner')
            ->assertSet('decided', false)
            ->assertSee('Přijmout vše')
            ->call('acceptAll')
            ->assertSet('decided', true)
            ->assertDontSee('Přijmout vše')
            ->assertDispatched('cookie-consent-changed', choice: CookieConsent::ALL);

        $this->assertSame(CookieConsent::ALL, Cookie::queued(CookieConsent::COOKIE)?->getValue());
        $this->assertNotNull(Cookie::queued(SiteVisitRecorder::COOKIE));
        $this->assertDatabaseCount('site_visits', 1);
    }

    public function test_accept_necessary_stores_choice_and_drops_visitor_token(): void
    {
        Livewire::test('cookie-banner')
            ->call('acceptNecessary')
            ->assertSet('decided', true)
            ->assertDontSee('Přijmout vše')
            ->assertDispatched('cookie-consent-changed', choice: CookieConsent::NECESSARY);

        $this->assertSame(CookieConsent::NECESSARY, Cookie::queued(CookieConsent::COOKIE)?->getValue());

        $visitor = Cookie::queued(SiteVisitRecorder::COOKIE);
        $this->assertNotNull($visitor);
        $this->assertLessThan(time(), $visitor->getExpiresTime(), 'rr_visitor má být zneplatněna');
        $this->assertDatabaseCount('site_visits', 0);
    }

    public function test_reopen_event_shows_banner_again(): void
    {
        Livewire::withCookie(CookieConsent::COOKIE, CookieConsent::ALL)
            ->test('cookie-banner')
            ->assertSet('decided', true)
            ->assertDontSee('Přijmout vše')
            ->dispatch('cookie-consent-reopen')
            ->assertSet('decided', false)
            ->assertSee('Přijmout vše');
    }

    public function test_cookies_page_lists_cookies_and_current_choice(): void
    {
        $this->get('/cookies')->assertOk()
            ->assertSee('Jaké cookies používáme')
            ->assertSee(config('session.cookie'))
            ->assertSee('rr_visitor')
            ->assertSee('Zatím bez volby');

        $this->withUnencryptedCookie(CookieConsent::COOKIE, CookieConsent::ALL)
            ->get('/cookies')->assertOk()->assertSee('Web počítá vaši návštěvu');

        $this->withUnencryptedCookie(CookieConsent::COOKIE, CookieConsent::NECESSARY)
            ->get('/cookies')->assertOk()->assertSee('Web ukládá jen cookies potřebné');
    }

    public function test_cookies_page_follows_banner_choice_without_reload(): void
    {
        Livewire::test('pages.cookies')
            ->assertSee('Zatím bez volby')
            ->dispatch('cookie-consent-changed', choice: CookieConsent::ALL)
            ->assertSet('choice', CookieConsent::ALL)
            ->assertSee('Web počítá vaši návštěvu')
            ->dispatch('cookie-consent-changed', choice: CookieConsent::NECESSARY)
            ->assertSee('Web ukládá jen cookies potřebné');
    }

    public function test_cookies_page_is_linked_from_footer_and_sitemap(): void
    {
        $this->get('/')->assertOk()->assertSee(route('cookies'));
        $this->get('/sitemap.xml')->assertOk()->assertSee(route('cookies'));
    }
}
