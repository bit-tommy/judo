<?php

namespace App\Enums;

enum DocumentGroup: string
{
    case Prihlasky = 'prihlasky';
    case Studijni = 'studijni';
    case Externi = 'externi';

    public function label(): string
    {
        return match ($this) {
            self::Prihlasky => 'Přihlášky & klubové dokumenty',
            self::Studijni => 'Studijní materiály — techniky',
            self::Externi => 'Externí odkazy — ČSJu',
        };
    }

    public function number(): string
    {
        return match ($this) {
            self::Prihlasky => '01',
            self::Studijni => '02',
            self::Externi => '03',
        };
    }

    /**
     * Pořadí skupin na stránce „Ke stažení" i v administraci.
     *
     * @return array<int, self>
     */
    public static function ordered(): array
    {
        return [self::Prihlasky, self::Studijni, self::Externi];
    }
}
