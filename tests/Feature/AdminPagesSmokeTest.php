<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Event;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Každá sekce administrace se musí vyrenderovat přihlášenému uživateli
 * a nepřihlášeného přesměrovat na login.
 */
class AdminPagesSmokeTest extends TestCase
{
    use RefreshDatabase;

    public static function adminRoutes(): array
    {
        return [
            'přehled' => ['/admin', 'Přehled klubu'],
            'členové' => ['/admin/clenove', 'Členové klubu'],
            'rozvrh' => ['/admin/rozvrh', 'Týden na tatami'],
            'akce' => ['/admin/akce', 'Kalendář akcí'],
            'galerie' => ['/admin/galerie', 'Fotoalba klubu'],
            'dokumenty' => ['/admin/dokumenty', 'Soubory pro web'],
            'analytika' => ['/admin/analytika', 'Návštěvnost webu'],
        ];
    }

    #[DataProvider('adminRoutes')]
    public function test_section_renders_for_admin_and_redirects_guest(string $url, string $expectedText): void
    {
        Member::factory()->count(2)->create();
        Event::factory()->create();
        Document::factory()->create();

        $this->get($url)->assertRedirect(route('admin.login'));

        $this->actingAs(User::factory()->create())
            ->get($url)
            ->assertOk()
            ->assertSee($expectedText)
            ->assertSee('Administrace');
    }

    public function test_homepage_calendar_uses_shared_schedule_config(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Za Invalidovnou 579\/3', false)
            ->assertSee('Dojo Kundratka 19', false);
    }
}
