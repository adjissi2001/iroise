<?php

namespace App\Mail;

use App\Support\NewUserActivationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $tempPassword,
        public readonly string $activationUrl,
        public readonly string $loginUrl,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: NewUserActivationMessage::subject(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-user-activation',
            with: [
                'tempPassword' => $this->tempPassword,
                'activationUrl' => $this->activationUrl,
                'loginUrl' => $this->loginUrl,
            ],
        );
    }
}
