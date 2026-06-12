<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Denní návštěva — jeden řádek na návštěvníka a den. Ukládá se jen sha256
 * hash anonymního cookie tokenu, žádná IP ani user-agent.
 */
class SiteVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'visitor_hash',
        'visit_date',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
        ];
    }
}
