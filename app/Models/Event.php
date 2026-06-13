<?php

namespace App\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;

    /** Zkratky měsíců pro datumový blok (index = číslo měsíce). */
    private const MONTH_ABBR = ['', 'Led', 'Úno', 'Bře', 'Dub', 'Kvě', 'Čer', 'Čvc', 'Srp', 'Zář', 'Říj', 'Lis', 'Pro'];

    protected $fillable = [
        'title',
        'slug',
        'starts_on',
        'ends_on',
        'place',
        'note',
        'description',
        'is_main',
        'attachment_path',
        'attachment_name',
        'attachment_size',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_main' => 'boolean',
            'attachment_size' => 'integer',
        ];
    }

    public function hasAttachment(): bool
    {
        return $this->attachment_path !== null;
    }

    /**
     * Adresář pro přílohy akcí. Fallback na storage chrání před tím, aby při
     * chybějící/zacachované konfiguraci (config('events.attachments_path') === null)
     * cesta zdegenerovala na „/soubor.pdf" a soubory se ztrácely.
     */
    public static function attachmentsDirectory(): string
    {
        return rtrim(config('events.attachments_path') ?: storage_path('app/akce-soubory'), '/');
    }

    /** Absolutní cesta k příloze na disku. */
    public function attachmentPath(): ?string
    {
        return $this->attachment_path === null
            ? null
            : self::attachmentsDirectory().'/'.$this->attachment_path;
    }

    /** Cíl odkazu ke stažení (výdej přes EventAttachmentController). */
    public function attachmentHref(): string
    {
        return route('events.attachment', $this);
    }

    /** Cíl pro zobrazení v prohlížeči (náhled PDF v modálu). */
    public function attachmentInlineHref(): string
    {
        return route('events.attachment', [$this, 'inline' => 1]);
    }

    public function attachmentIsPdf(): bool
    {
        return $this->attachmentExt() === 'PDF';
    }

    /** Lidská velikost přílohy: „240 kB", od 1 MB „2,3 MB". */
    public function attachmentSizeLabel(): ?string
    {
        if ($this->attachment_size === null) {
            return null;
        }

        if ($this->attachment_size >= 1024 * 1024) {
            return number_format($this->attachment_size / (1024 * 1024), 1, ',', ' ').' MB';
        }

        return number_format($this->attachment_size / 1024, 0, ',', ' ').' kB';
    }

    /** Přípona souboru velkými písmeny pro štítek („PDF", „DOCX"). */
    public function attachmentExt(): string
    {
        return strtoupper(pathinfo((string) $this->attachment_path, PATHINFO_EXTENSION));
    }

    /** Akce, které ještě neskončily (vícedenní počítáme do posledního dne). */
    public function scopeUpcoming($query)
    {
        return $query->where(function ($q) {
            $q->whereDate('starts_on', '>=', today())
                ->orWhere(fn ($w) => $w->whereNotNull('ends_on')->whereDate('ends_on', '>=', today()));
        })->orderBy('starts_on');
    }

    /** Akce, které už proběhly celé. */
    public function scopePast($query)
    {
        return $query->where(function ($q) {
            $q->where(fn ($w) => $w->whereNull('ends_on')->whereDate('starts_on', '<', today()))
                ->orWhere(fn ($w) => $w->whereNotNull('ends_on')->whereDate('ends_on', '<', today()));
        })->orderByDesc('starts_on');
    }

    public function isPast(): bool
    {
        return ($this->ends_on ?? $this->starts_on)->lt(today());
    }

    public function isRunning(): bool
    {
        return $this->starts_on->lte(today()) && ($this->ends_on ?? $this->starts_on)->gte(today());
    }

    public function daysUntil(): int
    {
        return max(0, (int) today()->diffInDays($this->starts_on, false));
    }

    public function day(): int
    {
        return $this->starts_on->day;
    }

    public function monthAbbr(): string
    {
        return self::MONTH_ABBR[$this->starts_on->month];
    }

    /** Český rozsah datumu: „27. 6. — 3. 7. 2026", jednodenní „10. 10. 2026". */
    public function dateRange(): string
    {
        $start = $this->starts_on;
        $end = $this->ends_on;

        if ($end === null || $end->isSameDay($start)) {
            return sprintf('%d. %d. %d', $start->day, $start->month, $start->year);
        }

        if ($start->year !== $end->year) {
            return sprintf('%d. %d. %d — %d. %d. %d', $start->day, $start->month, $start->year, $end->day, $end->month, $end->year);
        }

        return sprintf('%d. %d. — %d. %d. %d', $start->day, $start->month, $end->day, $end->month, $end->year);
    }

    /** Štítek do výpisu akcí (label + modifikátor `.tag` třídy). */
    public function tagLabel(): string
    {
        return $this->tag()[0];
    }

    public function tagClass(): string
    {
        return $this->tag()[1];
    }

    /** @return array{0: string, 1: string} */
    private function tag(): array
    {
        if ($this->isPast()) {
            return ['Proběhlo', 'faint'];
        }

        if ($this->isRunning()) {
            return ['Právě probíhá', 'red'];
        }

        if ($this->is_main) {
            return ['Hlavní akce', 'red'];
        }

        $days = $this->daysUntil();

        if ($days <= 30) {
            return [match (true) {
                $days === 1 => 'Zítra',
                $days >= 2 && $days <= 4 => "Za {$days} dny",
                default => "Za {$days} dní",
            }, 'dark'];
        }

        return ['Připravuje se', 'line'];
    }
}
