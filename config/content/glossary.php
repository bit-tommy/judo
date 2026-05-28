<?php

/*
|--------------------------------------------------------------------------
| Slovníček odborných pojmů (glossary)
|--------------------------------------------------------------------------
|
| Cizí / odborné výrazy z oblasti juda a japonských bojových umění, kterým
| běžný návštěvník nemusí na první pohled rozumět. Klíč = pojem tak, jak se
| píše v textu (kanonický tvar), hodnota = vysvětlivka.
|
| Pojmy se na webu automaticky zvýrazní a po najetí myší / kliknutí / přesunu
| fokusu se zobrazí vysvětlivka. Zvýrazní se vždy jen PRVNÍ výskyt každého
| pojmu v souvislém textu, aby stránka nepůsobila přeplácaně.
|
| Vyhledávání je necitlivé na velikost písmen a u jednoslovných pojmů toleruje
| krátkou českou koncovku (např. "dan" zachytí i "danu", "Kódókan" i "Kódókanu").
| Delší pojmy mají přednost před kratšími ("Ne-waza" se spáruje dříve než "waza").
|
| Pořadí níže = pořadí, v jakém se pojmy nabízejí; samotné párování řadí podle
| délky, takže na pořadí v poli prakticky nezáleží.
|
*/

return [

    // ── Stupně, tituly, instituce ─────────────────────────────────────────
    'dan' => '6. dan (japonsky rokudan) je vysoký mistrovský stupeň v bojových uměních (např. judo, karate, taekwondo) nebo deskových hrách (go). Číslo udává úroveň – 1. dan je první mistrovský (černý) stupeň, vyšší dany značí rostoucí mistrovství. Od 6. danu se v judu nosí charakteristický červeno-bílý pásek namísto klasického černého, od 9.–10. danu pásek červený.',

    'Kódókan' => 'Ústřední škola a institut juda v Tokiu, kterou roku 1882 založil profesor Jigoró Kanó. Je celosvětovou autoritou pro tradiční judo – jeho techniky, kata i udělování mistrovských stupňů (danů).',

    'ČSJu' => 'Český svaz juda – národní sportovní svaz, který v České republice zastřešuje judo: pořádá soutěže, vede registr členů a uděluje trenérské licence a technické stupně.',

    'sensei' => 'Učitel či mistr (doslova „ten, kdo se narodil dříve“). Uctivé japonské oslovení toho, kdo v bojovém umění vyučuje a předává své zkušenosti.',

    'Renshi' => 'Nejnižší ze tří mistrovských učitelských titulů (tzv. shógó) – v překladu „zběhlý/cvičený učitel“. Uděluje se zkušeným nositelům danu jako uznání jejich pedagogických kvalit. Vyšší tituly jsou Kjóši (Kyoshi) a Hanši (Hanshi).',

    // ── Prostředí a základní pojmy ────────────────────────────────────────
    'judo' => 'Doslova „jemná cesta“ (jú = jemný/poddajný, dó = cesta). Moderní japonské bojové umění a olympijský sport, který roku 1882 založil profesor Jigoró Kanó. Stojí na principu, kdy technika a obratnost vítězí nad hrubou silou.',

    'ju-jutsu' => 'Starší japonské bojové umění samurajů – boj beze zbraně využívající hody, páky a údery. Právě z ju-jutsu vytvořil Jigoró Kanó moderní judo.',

    'tatami' => 'Tradiční japonská žíněnka, dnes tréninková rohož. Plocha pokrytá tatami tvoří cvičební prostor, na němž se judo trénuje i závodí.',

    'kata' => 'Přesně definovaná, předem nacvičená forma (sestava) technik, kterou předvádí dvojice cvičenců. Slouží k uchování a předávání principů juda v jejich nejčistší podobě.',

    'randori' => 'Volné cvičení neboli „volný boj“ – nácvik technik s aktivně se bránícím soupeřem. Opak předem dané kata; blíží se reálnému zápasu.',

    'Aikido' => 'Japonské bojové umění zaměřené na obranu – využívá sílu a pohyb útočníka proti němu samotnému pomocí pák, hodů a úhybů, bez vlastních útočných úderů.',

    'Taijutsu' => 'Doslova „technika těla“ – boj beze zbraně. Zde konkrétně Hiko-ryu Taijutsu, komplexní samurajský systém sebeobrany (údery, kopy, páky, boj na zemi i obrana proti zbraním).',

    // ── Skupiny technik (waza = technika) ─────────────────────────────────
    'Tachi-waza' => 'Techniky prováděné ve stoji – především hody. Tvoří základ jak soutěžního, tak tradičního juda.',

    'Te-waza' => 'Hody prováděné převážně rukama (paží a horní částí těla).',

    'Koshi-waza' => 'Hody prováděné bokem a kyčlí, kdy se soupeř přetáčí přes bok cvičence.',

    'Ashi-waza' => 'Hody a podmety prováděné převážně nohama (podmety, zákopy, zameteni nohou).',

    'Ne-waza' => 'Techniky boje na zemi – znehybnění (držení), páky a škrcení. Kompletní souboj v poloze na zemi.',

    'Osaekomi-waza' => 'Techniky znehybnění (držení) soupeře na zemi po stanovenou dobu.',

    'Kansetsu-waza' => 'Techniky pák na klouby. V soutěžním judu jsou z bezpečnostních důvodů povoleny pouze páky na loket.',

    'Shime-waza' => 'Techniky škrcení – kontrola soupeře tlakem na krk či krční tepny.',

    'Sutemi-waza' => 'Techniky strhů, při nichž cvičenec záměrně padá (obětuje vlastní stabilitu), aby využil pádu k hodu soupeře. Patří k pokročilejší škole juda.',

    'Atemi-waza' => 'Údery a kopy na citlivá místa těla. Součást tradičního juda – dnes se zachovávají pouze v katách, nikoli v zápase.',

    'Goshin Jutsu' => 'Kodokan Goshin Jutsu – moderní soubor 21 technik sebeobrany sestavený Kódókanem roku 1956. Zahrnuje i obranu proti noži, tyči a střelné zbrani.',

    'Nage no Kata' => '„Kata hodů“ – tradiční forma předvádějící reprezentativní hody juda ze všech základních skupin technik.',

    'Koshiki no Kata' => '„Kata starých forem“ – nejstarší kata juda, vycházející přímo ze samurajského ju-jutsu.',

    // ── Principy pohybu ───────────────────────────────────────────────────
    'Kuzushi' => 'Vychýlení – porušení rovnováhy soupeře. Klíčová první fáze hodu; bez správného vychýlení nelze techniku účinně provést.',

    'Tai-sabaki' => 'Práce a obraty těla – úhyby a natočení vlastního těla do správné pozice a směru pro provedení techniky.',

];
