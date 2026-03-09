<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserAccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $temporaryPassword,
        public readonly string $activationUrl,
        public readonly ?string $siteUrl = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre compte Iroise — activation",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user_account_created',
            with: [
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'activationUrl' => $this->activationUrl,
                'siteUrl' => $this->siteUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
