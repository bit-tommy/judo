<?php

namespace App\Enums;

enum MemberStatus: string
{
    case Aktivni = 'aktivni';
    case Nova = 'nova';
    case Plat = 'plat';

    public function label(): string
    {
        return match ($this) {
            self::Aktivni => 'Aktivní',
            self::Nova => 'Nová přihláška',
            self::Plat => 'Čeká na platbu',
        };
    }

    /** Modifikátor `.tag` třídy v admin designu. */
    public function tagClass(): string
    {
        return match ($this) {
            self::Aktivni => 'dark',
            self::Nova => 'red',
            self::Plat => 'line',
        };
    }
}
