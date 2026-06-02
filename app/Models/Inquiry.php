<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'training_type',
        'preferred_date',
        'message',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'sent_at' => 'datetime',
        ];
    }

    /** Poptávky, které ještě nebyly e-mailem odeslány (čekají na SMTP). */
    public function scopePending($query)
    {
        return $query->whereNull('sent_at');
    }

    /** Mapování na pole očekávané mailem TrainingInquiry. */
    public function toMailData(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'trainingType' => $this->training_type,
            'date' => optional($this->preferred_date)->format('Y-m-d'),
            'message' => $this->message,
        ];
    }
}
