<?php

namespace Tests\Feature;

use App\Mail\TrainingInquiry;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Testy sdíleného poptávkového formuláře (resources/views/livewire/inquiry-form.blade.php).
 *
 * Pokrývají validaci, vždy-uložení do DB, podmíněné odeslání e-mailu, reset po
 * odeslání, antispamovou ochranu (honeypot + časová past) i předvyplnění
 * z kalendáře přes událost `inquiry-prefill`.
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

    /**
     * Namountuje formulář a posune čas, aby submit nespadl do antispamové
     * časové pasti (mount a save by jinak proběhly ve stejný zamrzlý
     * okamžik, což past vyhodnotí jako podezřele rychlé odeslání).
     */
    private function freshForm(): Testable
    {
        $component = Livewire::test('inquiry-form');
        Carbon::setTestNow(Carbon::now()->addSeconds(30));

        return $component;
    }

    public function test_prazdny_formular_hlasi_povinna_pole(): void
    {
        $this->freshForm()
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
        $this->freshForm()
            ->set('name', 'Jan Novák')
            ->set('email', 'tohle-neni-email')
            ->set('trainingType', 'Obecný dotaz')
            ->set('consent', true)
            ->call('save')
            ->assertHasErrors(['email' => 'email']);
    }

    public function test_typ_treninku_musi_byt_z_nabidky(): void
    {
        $this->freshForm()
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
        $this->freshForm()
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

        $this->freshForm()
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

        $this->freshForm()
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

        $this->freshForm()
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

    public function test_vyplneny_honeypot_tise_zahodi_zpravu(): void
    {
        Config::set('mail.inquiries_enabled', true);
        Mail::fake();

        $this->freshForm()
            ->set('name', 'Jan Novák')
            ->set('email', 'jan@example.com')
            ->set('trainingType', 'Obecný dotaz')
            ->set('consent', true)
            ->set('website', 'http://spam.example')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('sent', true);

        Mail::assertNothingSent();

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_prilis_rychle_odeslani_se_tise_zahodi(): void
    {
        // Žádný posun času – save() proběhne ve stejném okamžiku jako mount(),
        // takže past vyhodnotí odeslání jako příliš rychlé na to, aby ho
        // stihl vyplnit člověk.
        Livewire::test('inquiry-form')
            ->set('name', 'Jan Novák')
            ->set('email', 'jan@example.com')
            ->set('trainingType', 'Obecný dotaz')
            ->set('consent', true)
            ->call('save')
            ->assertSet('sent', true);

        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_casovou_past_nelze_obejit_z_klienta(): void
    {
        // #[Locked] musí zabránit i pokusu nastavit formLoadedAt přímo
        // z klienta (např. upraveným požadavkem) – Livewire takový
        // požadavek odmítne výjimkou, ne tichým přepsáním hodnoty.
        $this->expectException(CannotUpdateLockedPropertyException::class);

        Livewire::test('inquiry-form')->set('formLoadedAt', 0);
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

    public function test_neparsovatelne_datum_neshodi_render_a_zahodi_se(): void
    {
        // Pojistka na `catch (Throwable)` v availableDates(): nevalidní datum
        // z klienta nesmí shodit render a při změně typu tréninku se zahodí.
        Livewire::test('inquiry-form')
            ->set('trainingType', 'Judo – Praha 8')
            ->set('date', 'tohle-neni-datum')
            ->set('trainingType', 'Judo – Vodochody')
            ->assertSet('date', '');
    }
}
