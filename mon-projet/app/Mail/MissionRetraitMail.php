<?php

namespace App\Mail;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MissionRetraitMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $benevole,
        public readonly Mission $mission,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Un bénévole s'est retiré d'une mission",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mission_retrait',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
