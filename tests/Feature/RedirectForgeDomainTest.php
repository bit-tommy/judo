<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectForgeDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_on_forge_host_redirects_permanently_to_app_url(): void
    {
        config(['app.url' => 'https://www.raion-ryu.cz']);

        $this->get('https://judo-eeh.on-forge.com/akce?x=1')
            ->assertRedirect('https://www.raion-ryu.cz/akce?x=1')
            ->assertStatus(301);
    }

    public function test_real_domain_is_not_redirected(): void
    {
        config(['app.url' => 'https://www.raion-ryu.cz']);

        $this->get('https://www.raion-ryu.cz/akce')->assertOk();
    }

    public function test_no_redirect_when_app_url_is_itself_on_forge(): void
    {
        config(['app.url' => 'https://judo-eeh.on-forge.com']);

        $this->get('https://judo-eeh.on-forge.com/akce')->assertOk();
    }
}
