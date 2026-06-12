<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

/**
 * Aktuální akce klubu na rok 2026 (podklad od klienta, červen 2026).
 * Idempotentní — páruje podle slugu, lze bezpečně spouštět opakovaně
 * (i na produkci: `php artisan db:seed --class=EventSeeder --force`).
 */
class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Úklid dřívějších ukázkových akcí (nahrazeny reálným seznamem).
        Event::whereIn('slug', [
            'letni-soustredeni-deti-2026',
            'pobyt-japonskych-mistru-2026',
            'podzimni-turnaj-pripravek-2026',
            'vanocni-randori-zkousky-2026',
        ])->delete();

        $events = [
            [
                'slug' => 'ukazka-hiko-ryu-botanicka-zahrada-2026',
                'title' => 'Ukázka Hiko-ryu Taijutsu — Botanická zahrada Troja',
                'starts_on' => '2026-06-06',
                'ends_on' => null,
                'place' => 'Botanická zahrada Praha, Troja',
                'note' => 'Ukázky ve 14:30 a 16:30 · pro všechny věkové skupiny',
                'description' => 'Ukázka bojového umění Hiko-ryu Taijutsu v areálu Botanické zahrady Praha v Troji. Dvě vystoupení — ve 14:30 a v 16:30. Vhodné pro všechny věkové skupiny.',
                'is_main' => false,
            ],
            [
                'slug' => 'pochod-na-rip-2026',
                'title' => 'Tradiční pochod na horu Říp',
                'starts_on' => '2026-06-21',
                'ends_on' => null,
                'place' => 'hora Říp',
                'note' => 'Pro všechny věkové skupiny',
                'description' => 'Tradiční klubový pochod na horu Říp. Vhodné pro všechny věkové skupiny — děti, rodiče i dospělí členové oddílu.',
                'is_main' => false,
            ],
            [
                'slug' => 'soustredeni-hiko-ryu-korenov-2026',
                'title' => 'Soustředění Hiko-ryu Taijutsu',
                'starts_on' => '2026-07-31',
                'ends_on' => '2026-08-05',
                'place' => 'Kořenov',
                'note' => 'Vhodné od 15 let',
                'description' => 'Letní soustředění Hiko-ryu Taijutsu v Kořenově. Vhodné pro cvičence od 15 let věku.',
                'is_main' => false,
            ],
            [
                'slug' => 'letni-tabor-judo-2026',
                'title' => 'Letní tábor JUDO',
                'starts_on' => '2026-08-15',
                'ends_on' => '2026-08-22',
                'place' => null,
                'note' => null,
                'description' => 'Letní judo tábor pro děti z oddílu — týden tréninků, her a táborového programu.',
                'is_main' => false,
            ],
            [
                'slug' => 'soustredeni-aikido-nebakov-2026',
                'title' => 'Soustředění s oddílem Aikido (Ronin dojo)',
                'starts_on' => '2026-09-25',
                'ends_on' => '2026-09-28',
                'place' => 'Nebákov',
                'note' => 'Děti i dospělí společně',
                'description' => 'Společné soustředění dětí a dospělých s oddílem Aikido — Ronin dojo na Nebákově.',
                'is_main' => false,
            ],
            [
                'slug' => 'soustredeni-hiko-ryu-budapest-2026',
                'title' => 'Soustředění Hiko-ryu Taijutsu',
                'starts_on' => '2026-10-16',
                'ends_on' => '2026-10-18',
                'place' => 'Budapešť, Maďarsko',
                'note' => null,
                'description' => 'Podzimní soustředění Hiko-ryu Taijutsu v Budapešti.',
                'is_main' => false,
            ],
            [
                'slug' => 'soustredeni-judo-teplice-2026',
                'title' => 'Soustředění JUDO s přátelskými oddíly',
                'starts_on' => '2026-11-20',
                'ends_on' => '2026-11-22',
                'place' => 'Teplice',
                'note' => null,
                'description' => 'Společné judo soustředění s přátelskými oddíly v Teplicích.',
                'is_main' => false,
            ],
            [
                'slug' => 'ukazka-bojovych-umeni-madrid-2026',
                'title' => 'Velká ukázka bojových umění',
                'starts_on' => '2026-11-27',
                'ends_on' => '2026-11-29',
                'place' => 'Madrid, Španělsko',
                'note' => null,
                'description' => 'Velká mezinárodní ukázka bojových umění v Madridu.',
                'is_main' => false,
            ],
            [
                'slug' => 'mcr-judo-kata-2026',
                'title' => 'Mistrovství ČR v Judo Kata',
                'starts_on' => '2026-11-29',
                'ends_on' => null,
                'place' => 'Vršovice, Praha',
                'note' => null,
                'description' => 'Mistrovství České republiky v Judo Kata ve Vršovicích.',
                'is_main' => false,
            ],
            [
                'slug' => 'vanocni-turnaj-judo-2026',
                'title' => 'Klubový vánoční turnaj JUDO',
                'starts_on' => '2026-12-19',
                'ends_on' => null,
                'place' => 'TJ Sokol Balkán',
                'note' => 'Pořádá náš oddíl',
                'description' => 'Tradiční klubový vánoční turnaj v judu. Pořádá náš oddíl v TJ Sokol Balkán.',
                'is_main' => false,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(['slug' => $event['slug']], $event);
        }
    }
}
