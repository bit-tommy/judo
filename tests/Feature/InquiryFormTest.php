<?php

namespace Tests\Feature;

use App\Mail\TrainingInquiry;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Testy sdíleného poptávkového formuláře (resources/views/livewire/inquiry-form.blade.php).
 *
 * Pokrývají validaci, vždy-uložení do DB, podmíněné odeslání e-mailu, reset po
 * odeslání i předvyplnění z kalendáře přes událost `inquiry-prefill`.
 */
class InquiryFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Zafixujeme čas, aby byla nabídka termínů deterministická.
        // 2026-06-01 je pondělí (ISO 1) → platný den pro „Judo – Praha 8" [1, 3].
        Carbon::setTestNow('2026-06-01 09:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /** Nejbližší pondělí – platný tréninkový den pro „Judo – Praha 8". */
    private function nextTrainingDate(): string
    {
        return Carbon::today()->next(Carbon::MONDAY)->format('Y-m-d');
    }

    public function test_prazdny_formular_hlasi_povinna_pole(): void
    {
        Livewire::test('inquiry-form')
            ->call('save')
            ->assertHasErrors([
                'name' => 'required',
                'email' => 'required',
                'trainingType' => 'required',
                'consent' => 'accepted',
            ]);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_neplatny_email_hlasi_chybu(): void
    {
        Livewire::test('inquiry-form')
            ->set('name', 'Jan Novák')
            ->set('email', 'tohle-neni-email')
            ->set('trainingType', 'Obecný dotaz')
            ->set('consent', true)
            ->call('save')
            ->assertHasErrors(['email' => 'email']);
    }

    public function test_typ_treninku_musi_byt_z_nabidky(): void
    {
        Livewire::test('inquiry-form')
            ->set('name', 'Jan Novák')
            ->set('email', 'jan@example.com')
            ->set('trainingType', 'Něco vymyšleného')
            ->set('consent', true)
            ->call('save')
            ->assertHasErrors(['trainingType']);
    }

    public function test_termin_mimo_treninkove_dny_neprojde(): void
    {
        // 2026-06-02 je úterý – pro „Judo – Praha 8" [1, 3] neplatný den.
        Livewire::test('inquiry-form')
            ->set('name', 'Jan Novák')
            ->set('email', 'jan@example.com')
            ->set('trainingType', 'Judo – Praha 8')
            ->set('date', '2026-06-02')
            ->set('consent', true)
            ->call('save')
            ->assertHasErrors(['date']);
    }

    public function test_obecny_dotaz_se_ulozi_bez_terminu_a_bez_odeslani_emailu(): void
    {
        Config::set('mail.inquiries_enabled', false);
        Mail::fake();

        Livewire::test('inquiry-form')
            ->set('name', 'Jan Novák')
            ->set('email', 'jan@example.com')
            ->set('phone', '777111222')
            ->set('trainingType', 'Obecný dotaz')
            ->set('message', 'Dobrý den, mám dotaz.')
            ->set('consent', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('sent', true)
            ->assertSet('name', '')      // reset po odeslání
            ->assertSet('email', '');

        Mail::assertNothingSent();

        $this->assertDatabaseHas('inquiries', [
            'name' => 'Jan Novák',
            'email' => 'jan@example.com',
            'phone' => '777111222',
            'training_type' => 'Obecný dotaz',
            'preferred_date' => null,
            'sent_at' => null,           // bez SMTP zůstává nedoručené
        ]);
    }

    public function test_objednavka_s_platnym_terminem_ulozi_datum(): void
    {
        Config::set('mail.inquiries_enabled', false);

        $date = $this->nextTrainingDate();

        Livewire::test('inquiry-form')
            ->set('trainingType', 'Judo – Praha 8')
            ->set('date', $date)
            ->set('name', 'Eva Malá')
            ->set('email', 'eva@example.com')
            ->set('consent', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('sent', true);

        $inquiry = Inquiry::firstOrFail();

        $this->assertSame('Judo – Praha 8', $inquiry->training_type);
        $this->assertSame($date, $inquiry->preferred_date->format('Y-m-d'));
    }

    public function test_pri_zapnutem_doruceni_se_email_odesle_a_orazitkuje(): void
    {
        Config::set('mail.inquiries_enabled', true);
        Config::set('mail.inquiries_to', 'klub@example.com');
        Mail::fake();

        Livewire::test('inquiry-form')
            ->set('name', 'Jan Novák')
            ->set('email', 'jan@example.com')
            ->set('trainingType', 'Obecný dotaz')
            ->set('consent', true)
            ->call('save')
            ->assertHasNoErrors();

        Mail::assertSent(TrainingInquiry::class, function (TrainingInquiry $mail) {
            return $mail->hasTo('klub@example.com')
                && $mail->data['name'] === 'Jan Novák';
        });

        $this->assertNotNull(Inquiry::firstOrFail()->sent_at);
    }

    public function test_prefill_predvyplni_typ_a_termin_z_kalendare(): void
    {
        $date = $this->nextTrainingDate();

        Livewire::test('inquiry-form')
            ->dispatch('inquiry-prefill', trainingType: 'Judo – Praha 8', date: $date)
            ->assertSet('trainingType', 'Judo – Praha 8')
            ->assertSet('date', $date)
            ->assertSet('sent', false);
    }

    public function test_zmena_typu_treninku_zahodi_nevalidni_termin(): void
    {
        // 2026-06-02 (úterý) je platný pro „Judo – Vodochody" [1, 2],
        // ale ne pro „Judo – Praha 8" [1, 3] → po přepnutí typu se vyčistí.
        Livewire::test('inquiry-form')
            ->set('trainingType', 'Judo – Vodochody')
            ->set('date', '2026-06-02')
            ->assertSet('date', '2026-06-02')
            ->set('trainingType', 'Judo – Praha 8')
            ->assertSet('date', '');
    }
}
