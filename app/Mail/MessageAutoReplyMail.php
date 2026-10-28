<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageAutoReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Message $messageRecord
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesan Anda Telah Diterima: '.$this->messageRecord->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.messages.auto-reply',
            with: [
                'messageRecord' => $this->messageRecord,
            ],
        );
    }

    /**
     * @return array<int, mixed>
     */
    public function attachments(): array
    {
        return [];
    }
}
