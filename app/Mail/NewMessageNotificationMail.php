<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NewMessageNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Message $messageRecord
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Portofolio] Pesan Baru dari '.$this->messageRecord->name.': '.$this->messageRecord->subject,
            replyTo: [
                new Address($this->messageRecord->email, $this->messageRecord->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.messages.new-notification',
            with: [
                'messageRecord' => $this->messageRecord,
                'adminUrl' => url('/admin/messages/'.$this->messageRecord->id.'/edit'),
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        if (
            $this->messageRecord->hasAttachment()
            && $this->messageRecord->attachment_path
            && Storage::disk('public')->exists($this->messageRecord->attachment_path)
        ) {
            return [
                Attachment::fromStorageDisk('public', $this->messageRecord->attachment_path)
                    ->as($this->messageRecord->attachment_name ?? 'lampiran')
                    ->withMime($this->messageRecord->attachment_mime ?? 'application/octet-stream'),
            ];
        }

        return [];
    }
}
