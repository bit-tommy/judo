<?php

/*
|--------------------------------------------------------------------------
| Týdenní rozvrh tréninků — jediný zdroj pravdy
|--------------------------------------------------------------------------
| Konzumují ho tři místa:
|   1. kalendář na homepage (livewire/training-calendar.blade.php — Alpine),
|   2. validace poptávkového formuláře (livewire/inquiry-form.blade.php),
|   3. admin panel „Rozvrh" (livewire/pages/admin/rozvrh.blade.php).
|
| Klíče v `days` jsou dny v týdnu 1–7 (pondělí = 1). Trénuje se jen po–st,
| takže hodnoty 1/2/3 sedí zároveň pro JS getDay() (kalendář) i pro
| dayOfWeekIso (formulář). Při rozšíření na čt–ne je potřeba kalendářní
| mapování zkontrolovat (getDay(): neděle = 0).
|
| `form` musí odpovídat položce ve `form_options` — formulář podle něj
| validuje vybraný termín proti skutečným tréninkovým dnům.
*/

return [
    'days' => [
        1 => [
            ['type' => 'Judo',     'place' => 'Praha 8',   'loc' => 'Za Invalidovnou 579/3', 'time' => '16:30–18:00', 'form' => 'Judo – Praha 8'],
            ['type' => 'Judo',     'place' => 'Vodochody', 'loc' => 'Průběžná 50',           'time' => '16:30–18:00', 'form' => 'Judo – Vodochody'],
            ['type' => 'Taijutsu', 'place' => 'Praha 8',   'loc' => 'Dojo Kundratka 19',     'time' => '18:45–20:30', 'form' => 'Taijutsu – Praha 8'],
        ],
        2 => [
            ['type' => 'Judo',     'place' => 'Vodochody', 'loc' => 'Průběžná 50',           'time' => '16:30–18:00', 'form' => 'Judo – Vodochody'],
        ],
        3 => [
            ['type' => 'Judo',     'place' => 'Praha 8',   'loc' => 'Za Invalidovnou 579/3', 'time' => '16:30–18:00', 'form' => 'Judo – Praha 8'],
            ['type' => 'Taijutsu', 'place' => 'Praha 8',   'loc' => 'Dojo Kundratka 19',     'time' => '18:45–20:30', 'form' => 'Taijutsu – Praha 8'],
        ],
    ],

    // Nabídka typů ve formuláři; „Obecný dotaz" je záměrně bez termínů.
    'form_options' => [
        'Obecný dotaz',
        'Judo – Praha 8',
        'Judo – Vodochody',
        'Taijutsu – Praha 8',
    ],
];
