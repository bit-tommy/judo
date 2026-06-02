<?php

namespace App\Console\Commands;

use App\Mail\TrainingInquiry;
use App\Models\Inquiry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPendingInquiries extends Command
{
    protected $signature = 'inquiries:send-pending {--force : Odeslat i když je mail.inquiries_enabled vypnuté}';

    protected $description = 'Odešle e-mailem všechny dosud neodeslané poptávky z formuláře (nahromaděné, než bylo nastavené SMTP).';

    public function handle(): int
    {
        if (! config('mail.inquiries_enabled') && ! $this->option('force')) {
            $this->warn('Doručování poptávek je vypnuté (MAIL_INQUIRIES_ENABLED=false).');
            $this->line('Nastav SMTP a MAIL_INQUIRIES_ENABLED=true, nebo spusť s --force.');

            return self::FAILURE;
        }

        $pending = Inquiry::pending()->orderBy('created_at')->get();

        if ($pending->isEmpty()) {
            $this->info('Žádné čekající poptávky.');

            return self::SUCCESS;
        }

        $this->info("Odesílám {$pending->count()} poptávek na ".config('mail.inquiries_to').' …');

        foreach ($pending as $inquiry) {
            Mail::to(config('mail.inquiries_to'))->send(new TrainingInquiry($inquiry->toMailData()));
            $inquiry->forceFill(['sent_at' => now()])->save();
            $this->line("  ✓ #{$inquiry->id} {$inquiry->name} <{$inquiry->email}>");
        }

        $this->info('Hotovo.');

        return self::SUCCESS;
    }
}
