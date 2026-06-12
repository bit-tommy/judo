<?php

namespace Database\Seeders;

use App\Enums\DocumentGroup;
use App\Models\Document;
use Illuminate\Database\Seeder;

/**
 * Převádí dosavadní natvrdo psaný obsah stránky „Ke stažení" do databáze
 * (tituly, popisky, skupiny i pořadí 1:1). Velikosti souborů se dopočítají
 * z disku, pokud soubor existuje. Idempotentní — páruje podle titulu.
 */
class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            // ─── 01 Přihlášky & klubové dokumenty ───
            ['Přihláška na judo — Praha', 'Přihláška do oddílu · Praha 8', DocumentGroup::Prihlasky, 'prihlaska_Judo_club.pdf', null],
            ['Přihláška na judo — Vodochody', 'Přihláška do oddílu · Vodochody', DocumentGroup::Prihlasky, 'prihlaska_Judo_club.pdf', null],
            ['GDPR JC Raion-Ryu', 'Ochrana osobních údajů klubu', DocumentGroup::Prihlasky, 'JCRR-GDPR.pdf', null],
            ['Zásady chování v dojo', 'Etika a pravidla tréninku', DocumentGroup::Prihlasky, 'etika.pdf', null],

            // ─── 02 Studijní materiály — techniky ───
            ['Go-Kyo', 'Soubor základních technik v postoji', DocumentGroup::Studijni, 'GOkyo.pdf', null],
            ['Techniky na zemi — rozdělení', 'Základní rozdělení Ne-waza', DocumentGroup::Studijni, 'osaekomiwaza.pdf', null],
            ['Techniky znehybnění', 'Katame-waza · znehybnění soupeře', DocumentGroup::Studijni, 'katamewaza.pdf', null],
            ['Odváděcí techniky (policejní)', 'Renkoho-waza', DocumentGroup::Studijni, 'renkohowaza.pdf', null],
            ['Kompletní přehled technik 1.–5. kyu', 'Nage-waza · zkušební řád', DocumentGroup::Studijni, 'nagewaza.pdf', null],
            ['Slovníček pojmů', 'Japonské výrazy používané v dojo', DocumentGroup::Studijni, 'slovnicek.pdf', null],

            // ─── 03 Externí odkazy — ČSJu ───
            ['Informace GDPR ČSJu', 'czechjudo.org · informační memorandum', DocumentGroup::Externi, null, 'http://www.czechjudo.org/gdpr-informacni-memorandum'],
            ['Směrnice o zdravotní způsobilosti — důležité!', 'ČSJu · zdravotní způsobilost aktivních členů', DocumentGroup::Externi, null, 'http://www.czechjudo.org/Files/1/Documents/lexikon/Sm%C4%9Brnice%20%C4%8CSJu%20o%20zdravotn%C3%AD%20zp%C5%AFsobilosti%20aktivn%C3%ADch%20%C4%8Dlen%C5%AF%20%C4%8CSJu.pdf'],
        ];

        foreach ($rows as $sort => [$title, $meta, $group, $filename, $url]) {
            $path = $filename !== null
                ? rtrim(config('documents.path'), '/').'/'.$filename
                : null;

            Document::updateOrCreate(['title' => $title], [
                'meta' => $meta,
                'group' => $group,
                'type' => $filename !== null ? 'file' : 'external',
                'filename' => $filename,
                'url' => $url,
                'size_bytes' => ($path !== null && is_file($path)) ? filesize($path) : null,
                'visible' => true,
                'sort' => $sort,
            ]);
        }
    }
}
