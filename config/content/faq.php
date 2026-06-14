<?php

/*
|--------------------------------------------------------------------------
| Časté dotazy (FAQ)
|--------------------------------------------------------------------------
|
| Otázky a odpovědi pro nové zájemce (hlavně rodiče). Zobrazuje je komponenta
| <x-ui.faq> jako rozbalovací sekci na úvodní stránce a zároveň z nich staví
| strukturovaná data FAQPage (JSON-LD) pro vyhledávače.
|
| 'q' = otázka, 'a' = odpověď. V odpovědi je povolené jednoduché HTML (odkaz
| <a>); do JSON-LD se odkazy odstraní. Kotvy #rozvrh a #kontakt míří na sekce
| úvodní stránky, kde se FAQ zobrazuje.
|
*/

return [
    [
        'q' => 'Je první trénink zdarma?',
        'a' => 'Ano. První trénink je u nás zdarma a bez závazků — stačí přijít a vyzkoušet si to.',
    ],
    [
        'q' => 'Od kolika let mohou děti začít?',
        'a' => 'Děti přijímáme zpravidla od 5 let. U mladších se prosím předem domluvte s trenérem.',
    ],
    [
        'q' => 'Co si vzít na první trénink?',
        'a' => 'Stačí pohodlné sportovní oblečení a pití. Kimono (judogi) řešíme až po přihlášce — na první trénink ho nepotřebujete.',
    ],
    [
        'q' => 'Jak často se trénuje?',
        'a' => 'Trénuje se zpravidla 2× týdně. Konkrétní dny a časy najdete v <a href="#rozvrh">tréninkovém kalendáři</a>.',
    ],
    [
        'q' => 'Kde trénujete?',
        'a' => 'Máme dvě pobočky: Praha 8 (judo na adrese Za Invalidovnou 579/3, taijutsu v Dojo Kundratka 19) a Vodochody (Průběžná 50). Adresy i navigaci najdete v <a href="#kontakt">kontaktech</a>.',
    ],
    [
        'q' => 'Kolik tréninky stojí?',
        'a' => 'Aktuální ceník členských příspěvků najdete na stránce <a href="/cenik">Ceník</a>.',
    ],
    [
        'q' => 'Musí mít dítě předchozí průpravu?',
        'a' => 'Ne. Začínáme od úplných základů a vítáme úplné začátečníky — děti i dospělé.',
    ],
    [
        'q' => 'Jak se přihlásit?',
        'a' => 'Napište nám přes <a href="#inquiry">kontaktní formulář</a> na webu nebo se zeptejte přímo na tréninku. Rádi poradíme s výběrem vhodné skupiny.',
    ],
    [
        'q' => 'Trénujete i dospělé?',
        'a' => 'Ano. Tréninky máme pro děti, mládež i dospělé — začátečníky i pokročilé.',
    ],
];
