<?php

namespace App\Models;

use App\Enums\MemberGroup;
use App\Enums\MemberStatus;
use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Member extends Model
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'group',
        'parent_name',
        'phone',
        'email',
        'member_since',
        'belt',
        'status',
        'note',
        'inquiry_id',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'group' => MemberGroup::class,
            'status' => MemberStatus::class,
            'member_since' => 'date',
        ];
    }

    /** Iniciály do avataru („Jakub Novák" → „JN"). */
    public function initials(): string
    {
        return Str::of($this->name)
            ->squish()
            ->explode(' ')
            ->take(2)
            ->map(fn (string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }

    /** Členem od — česky („Září 2025"). */
    public function memberSinceLabel(): ?string
    {
        if ($this->member_since === null) {
            return null;
        }

        $months = ['', 'Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen',
            'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];

        return $months[$this->member_since->month].' '.$this->member_since->year;
    }

    public function scopeActive($query)
    {
        return $query->where('status', MemberStatus::Aktivni);
    }

    public function scopePending($query)
    {
        return $query->where('status', MemberStatus::Nova);
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }
}
