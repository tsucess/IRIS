<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $code,
        public readonly string $firstname,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Email Verification Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.verification-code',
            with: [
                'code'      => $this->code,
                'firstname' => $this->firstname,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
