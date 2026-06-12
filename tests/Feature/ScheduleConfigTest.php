<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Hlídá tvar config/content/schedule.php — sdílený zdroj pravdy pro homepage
 * kalendář, validaci poptávkového formuláře i admin rozvrh. Labely musí
 * zůstat byte-for-byte shodné (en-dash) s hodnotami ukládanými do poptávek.
 */
class ScheduleConfigTest extends TestCase
{
    public function test_form_options_match_expected_labels(): void
    {
        $this->assertSame(
            ['Obecný dotaz', 'Judo – Praha 8', 'Judo – Vodochody', 'Taijutsu – Praha 8'],
            config('content.schedule.form_options'),
        );
    }

    public function test_days_derive_expected_training_day_map(): void
    {
        $map = [];
        foreach (config('content.schedule.days') as $day => $sessions) {
            foreach ($sessions as $session) {
                $map[$session['form']][] = (int) $day;
            }
        }

        $this->assertSame([
            'Judo – Praha 8' => [1, 3],
            'Judo – Vodochody' => [1, 2],
            'Taijutsu – Praha 8' => [1, 3],
        ], array_map(fn ($d) => array_values(array_unique($d)), $map));
    }

    public function test_every_session_has_complete_shape(): void
    {
        foreach (config('content.schedule.days') as $day => $sessions) {
            $this->assertIsInt($day);
            $this->assertGreaterThanOrEqual(1, $day);
            $this->assertLessThanOrEqual(7, $day);

            foreach ($sessions as $session) {
                $this->assertSame(['type', 'place', 'loc', 'time', 'form'], array_keys($session));
                $this->assertContains($session['form'], config('content.schedule.form_options'));
            }
        }
    }

    public function test_form_options_only_contain_general_inquiry_beyond_sessions(): void
    {
        $sessionForms = collect(config('content.schedule.days'))->flatten(1)->pluck('form')->unique()->values();
        $extra = collect(config('content.schedule.form_options'))->diff($sessionForms)->values();

        $this->assertSame(['Obecný dotaz'], $extra->all());
    }
}
