<?php

return [

    'title'          => 'Tréninky a ceník',
    'schedule_title' => 'ROZPIS TRÉNINKŮ',
    'main_dojo'      => 'HONBU DOJO INVALIDOVNA (HLAVNÍ DOJO) - PRAHA 8',

    'schedule' => [
        [
            'name'  => 'JUDO PŘÍPRAVKA',
            'days'  => 'Po, St',
            'time'  => '16:00–17:00',
            'note'  => 'děti od 5 let',
        ],
        [
            'name'  => 'JUDO POKROČILÍ',
            'days'  => 'Po, St',
            'time'  => '17:00–18:30',
            'note'  => 'děti a mládež',
        ],
        [
            'name'  => 'JUDO DOSPĚLÍ',
            'days'  => 'Út, Čt',
            'time'  => '18:30–20:00',
            'note'  => '',
        ],
        [
            'name'     => 'JUDO VODOCHODY',
            'days'     => 'Pá',
            'time'     => '16:00–17:00',
            'note'     => '',
        ],
        [
            'name'   => 'HIKO-RYU TAIJUTSU',
            'status' => 'DOČASNĚ POZASTAVENO!!!',
        ],
        [
            'name'   => 'KONDIČNÍ CVIČENÍ - RANDORI',
            'status' => 'TYTO LEKCE DOČASNĚ POZASTAVENO!!!',
        ],
    ],

    'free_trial'    => 'První trénink zdarma! Přijďte si vyzkoušet atmosféru našeho dojo bez jakýchkoliv závazků.',
    'kimono_note'   => 'Pro první trénink není nutné mít vlastní kimono (judogi). Kimono vám na zkušební trénink zapůjčíme zdarma. Pro pravidelné tréninky doporučujeme pořízení vlastního kimona.',

    'pricing_title' => 'Ceník',

    'pricing' => [
        [
            'name'    => 'Přípravka',
            'payment' => 'Platba na čtvrtletí předem.',
            'amount'  => 'Výše příspěvku sdělena na základě přihlášení.',
        ],
        [
            'name'    => 'Pokročilí',
            'payment' => 'Platba na čtvrtletí předem.',
            'amount'  => 'Výše příspěvku sdělena na základě přihlášení.',
        ],
        [
            'name'    => 'Dospělí',
            'payment' => 'Platba na čtvrtletí předem.',
            'amount'  => 'Výše příspěvku sdělena na základě přihlášení.',
        ],
        [
            'note' => 'Platba musí být připsána na účet klubu nejpozději do 15. dne prvního měsíce daného čtvrtletí.',
        ],
    ],

    'payment_rules' => 'Platby se provádějí vždy na celé čtvrtletí předem. Platba musí být připsána na účet klubu nejpozději do 15. dne prvního měsíce daného čtvrtletí.',
    'bank_account'  => 'XXXX/XXXX',

    'preparatory' => [
        'name'        => 'JUDO PŘÍPRAVKA',
        'age'         => 'děti od 5 let',
        'days'        => 'Po, St',
        'time'        => '16:00–17:00',
        'description' => 'Přípravka je určena pro nejmenší závodníky od 5 let. Tréninky jsou přizpůsobeny věku a rozvíjejí pohybové dovednosti, koordinaci a základy juda hravou formou.',
    ],

    'advanced' => [
        'name'        => 'JUDO POKROČILÍ',
        'age'         => 'děti a mládež',
        'days'        => 'Po, St',
        'time'        => '17:00–18:30',
        'description' => 'Lekce pro pokročilé děti a mládež se zaměřuje na rozvoj techniky, taktiky a přípravy na soutěže.',
    ],

    'adults' => [
        'name'        => 'JUDO DOSPĚLÍ',
        'days'        => 'Út, Čt',
        'time'        => '18:30–20:00',
        'description' => 'Tréninky pro dospělé jsou zaměřeny na techniku, kondici a volný zápas (randori). Vhodné pro začátečníky i pokročilé.',
    ],

    'hikoryu' => [
        'name'        => 'HIKO-RYU TAIJUTSU',
        'status'      => 'DOČASNĚ POZASTAVENO!!!',
        'description' => 'Tréninky Hiko-ryu Taijutsu jsou v současné době dočasně pozastaveny. Sledujte aktuality pro informace o obnovení.',
    ],

    'randori' => [
        'name'        => 'KONDIČNÍ CVIČENÍ - RANDORI',
        'status'      => 'TYTO LEKCE DOČASNĚ POZASTAVENO!!!',
        'description' => 'Kondiční cvičení zaměřené na randori je v současné době dočasně pozastaveno. Sledujte aktuality pro informace o obnovení.',
    ],

];
