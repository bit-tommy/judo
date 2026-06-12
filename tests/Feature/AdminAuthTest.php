<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'email' => 'vedouci@raion-ryu.cz',
            'password' => 'tajne-heslo',
        ]);
    }

    public function test_guest_is_redirected_from_admin_to_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->get('/admin/clenove')->assertRedirect(route('admin.login'));
    }

    public function test_login_page_renders_for_guest(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Vstoupit do dojo')
            ->assertSee('Administrace klubu');
    }

    public function test_login_with_wrong_credentials_fails_with_czech_message(): void
    {
        $this->admin();

        Livewire::test('pages.admin.login')
            ->set('email', 'vedouci@raion-ryu.cz')
            ->set('password', 'spatne-heslo')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_login_with_correct_credentials_authenticates(): void
    {
        $user = $this->admin();

        Livewire::test('pages.admin.login')
            ->set('email', 'vedouci@raion-ryu.cz')
            ->set('password', 'tajne-heslo')
            ->call('login')
            ->assertHasNoErrors()
            ->assertDispatched('login-success');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_is_rate_limited_after_five_attempts(): void
    {
        $this->admin();
        RateLimiter::clear('vedouci@raion-ryu.cz|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            Livewire::test('pages.admin.login')
                ->set('email', 'vedouci@raion-ryu.cz')
                ->set('password', 'spatne-heslo')
                ->call('login');
        }

        Livewire::test('pages.admin.login')
            ->set('email', 'vedouci@raion-ryu.cz')
            ->set('password', 'tajne-heslo')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_authenticated_user_sees_dashboard(): void
    {
        $this->actingAs($this->admin());

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Přehled klubu')
            ->assertSee('Administrace');
    }

    public function test_logout_invalidates_session(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_logged_in_user_is_redirected_away_from_login_page(): void
    {
        $this->actingAs($this->admin());

        $this->get(route('admin.login'))->assertRedirect(route('admin.dashboard'));
    }
}
