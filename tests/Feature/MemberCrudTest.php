<?php

namespace Tests\Feature;

use App\Enums\MemberStatus;
use App\Models\Inquiry;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MemberCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
    }

    public function test_guest_cannot_open_members_page(): void
    {
        $this->get('/admin/clenove')->assertRedirect(route('admin.login'));
    }

    public function test_members_page_lists_members(): void
    {
        Member::factory()->create(['name' => 'Jakub Novák']);

        $this->actingAs($this->admin)
            ->get('/admin/clenove')
            ->assertOk()
            ->assertSee('Jakub Novák')
            ->assertSee('Členové klubu');
    }

    public function test_admin_can_create_member(): void
    {
        Livewire::actingAs($this->admin)
            ->test('pages.admin.clenove')
            ->call('openCreate')
            ->set('name', 'Jan Veselý')
            ->set('age', 7)
            ->set('group', 'pripravka')
            ->set('parentName', 'Pavel Veselý')
            ->set('phone', '+420 600 000 000')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $this->assertDatabaseHas('members', [
            'name' => 'Jan Veselý',
            'age' => 7,
            'group' => 'pripravka',
            'parent_name' => 'Pavel Veselý',
        ]);
    }

    public function test_create_validates_required_fields_in_czech(): void
    {
        Livewire::actingAs($this->admin)
            ->test('pages.admin.clenove')
            ->call('openCreate')
            ->call('save')
            ->assertHasErrors(['name', 'age', 'parentName']);

        $this->assertDatabaseCount('members', 0);
    }

    public function test_admin_can_edit_member(): void
    {
        $member = Member::factory()->create(['name' => 'Eliška Dvořáková', 'age' => 9]);

        Livewire::actingAs($this->admin)
            ->test('pages.admin.clenove')
            ->call('openEdit', $member->id)
            ->assertSet('name', 'Eliška Dvořáková')
            ->set('age', 10)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(10, $member->fresh()->age);
    }

    public function test_admin_can_delete_member(): void
    {
        $member = Member::factory()->create();

        Livewire::actingAs($this->admin)
            ->test('pages.admin.clenove')
            ->call('delete', $member->id);

        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    }

    public function test_search_filters_by_name_and_parent(): void
    {
        Member::factory()->create(['name' => 'Jakub Novák', 'parent_name' => 'Petr Novák']);
        Member::factory()->create(['name' => 'Anna Procházková', 'parent_name' => 'Karel Procházka']);

        Livewire::actingAs($this->admin)
            ->test('pages.admin.clenove')
            ->set('search', 'Novák')
            ->assertSee('Jakub Novák')
            ->assertDontSee('Anna Procházková');
    }

    public function test_filter_shows_only_new_applications(): void
    {
        Member::factory()->create(['name' => 'Jakub Novák']);
        Member::factory()->pending()->create(['name' => 'Matěj Svoboda']);

        Livewire::actingAs($this->admin)
            ->test('pages.admin.clenove')
            ->set('filter', 'nove')
            ->assertSee('Matěj Svoboda')
            ->assertDontSee('Jakub Novák');
    }

    public function test_approve_activates_member_and_marks_inquiry_handled(): void
    {
        $inquiry = Inquiry::create([
            'name' => 'Matěj Svoboda',
            'email' => 'svobodova@email.cz',
            'training_type' => 'Judo – Praha 8',
        ]);
        $member = Member::factory()->pending()->create(['inquiry_id' => $inquiry->id]);

        Livewire::actingAs($this->admin)
            ->test('pages.admin.clenove')
            ->call('approve', $member->id)
            ->assertDispatched('toast');

        $this->assertSame(MemberStatus::Aktivni, $member->fresh()->status);
        $this->assertNotNull($member->fresh()->member_since);
        $this->assertNotNull($inquiry->fresh()->handled_at);
    }

    public function test_creating_member_from_inquiry_prefills_and_marks_handled(): void
    {
        $inquiry = Inquiry::create([
            'name' => 'Lucie Svobodová',
            'email' => 'svobodova@email.cz',
            'phone' => '+420 777 808 121',
            'training_type' => 'Judo – Praha 8',
        ]);

        Livewire::withQueryParams(['from_inquiry' => $inquiry->id])
            ->actingAs($this->admin)
            ->test('pages.admin.clenove')
            ->assertSet('showForm', true)
            ->assertSet('name', 'Lucie Svobodová')
            ->assertSet('phone', '+420 777 808 121')
            ->set('age', 6)
            ->set('parentName', 'Lucie Svobodová')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertNotNull($inquiry->fresh()->handled_at);
        $this->assertDatabaseHas('members', [
            'name' => 'Lucie Svobodová',
            'inquiry_id' => $inquiry->id,
            'status' => 'nova',
        ]);
    }

    public function test_dashboard_mark_handled_clears_inquiry(): void
    {
        $inquiry = Inquiry::create([
            'name' => 'Test Rodič',
            'email' => 'test@email.cz',
            'training_type' => 'Obecný dotaz',
        ]);

        Livewire::actingAs($this->admin)
            ->test('pages.admin.prehled')
            ->call('markHandled', $inquiry->id)
            ->assertDispatched('toast');

        $this->assertNotNull($inquiry->fresh()->handled_at);
    }
}
