<?php

namespace App\Models;

use Database\Factories\PriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Položka ceníku (členské příspěvky) — spravuje administrace,
 * veřejně se zobrazuje na /cenik.
 */
class Price extends Model
{
    /** @use HasFactory<PriceFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'period',
        'note',
        'visible',
        'sort',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'visible' => 'boolean',
            'sort' => 'integer',
        ];
    }

    /** „3 000 Kč" */
    public function amountLabel(): string
    {
        return number_format($this->amount, 0, ',', ' ').' Kč';
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
