<?php

namespace App\Enums;

enum MemberGroup: string
{
    case Pripravka = 'pripravka';
    case Pokrocili = 'pokrocili';

    public function label(): string
    {
        return match ($this) {
            self::Pripravka => 'Přípravka',
            self::Pokrocili => 'Pokročilí',
        };
    }

    /** Popisek do formulářového selectu (viz design administrace). */
    public function formLabel(): string
    {
        return match ($this) {
            self::Pripravka => 'Přípravka — děti 5–8 let',
            self::Pokrocili => 'Pokročilí — děti 8+',
        };
    }
}
