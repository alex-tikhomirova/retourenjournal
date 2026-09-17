<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @param array{name: string, email: string, topic: string, subject?: string|null, message: string} $contact
     */
    public function __construct(
        public readonly array $contact,
        public readonly string $topicLabel,
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = '[RetourenJournal] '.$this->topicLabel;
        $customSubject = trim((string) ($this->contact['subject'] ?? ''));

        if ($customSubject !== '') {
            $subject .= ': '.$customSubject;
        }

        return new Envelope(
            replyTo: [
                new Address($this->contact['email'], $this->contact['name']),
            ],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'mail.contact',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
