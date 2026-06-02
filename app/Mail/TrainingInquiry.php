<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainingInquiry extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Web: '.($this->data['trainingType'] ?? 'dotaz').' – '.($this->data['name'] ?? ''),
            replyTo: [new Address($this->data['email'], $this->data['name'] ?? null)],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.training-inquiry');
    }
}
