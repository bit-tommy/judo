<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Jednorázová odchylka od pravidelného rozvrhu (config/content/schedule.php)
 * na konkrétní datum:
 *
 *  - kind = 'zruseno' — zrušený pravidelný trénink; nese snapshot lekce
 *    (type/place/loc/time/form), na webu se ukáže přeškrtnutě a formulář
 *    daný termín nenabídne;
 *  - kind = 'extra' — mimořádný trénink navíc; pokud má vyplněný `form`,
 *    formulář termín nabídne k objednání.
 */
class ScheduleOverride extends Model
{
    public const KIND_CANCELLED = 'zruseno';

    public const KIND_EXTRA = 'extra';

    protected $fillable = [
        'date',
        'kind',
        'type',
        'place',
        'loc',
        'time',
        'form',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function isCancellation(): bool
    {
        return $this->kind === self::KIND_CANCELLED;
    }

    public function scopeCancellations($query)
    {
        return $query->where('kind', self::KIND_CANCELLED);
    }

    public function scopeExtras($query)
    {
        return $query->where('kind', self::KIND_EXTRA);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('date', '>=', today())->orderBy('date');
    }

    /** Popis lekce do výpisů („Judo — Praha 8 · 16:30–18:00"). */
    public function sessionLabel(): string
    {
        return $this->type
            .($this->place ? ' — '.$this->place : '')
            .' · '.$this->time;
    }
}
