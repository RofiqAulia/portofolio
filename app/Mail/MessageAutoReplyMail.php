<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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

    public const MAX_ATTACHMENT_BYTES = 15728640; // 15 MB (Batas aman Gmail SMTP)

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        // Jika total berkas melebihi 15 MB, jangan membebani socket SMTP agar tidak timeout/ditolak Gmail (limit 25MB).
        // Berkas tetap dapat diunduh langsung via tombol unduh di badan email.
        if ($this->messageRecord->totalAttachmentSize() > self::MAX_ATTACHMENT_BYTES) {
            return [];
        }

        $attachments = [];

        foreach ($this->messageRecord->getAttachments() as $item) {
            $path = $item['path'] ?? null;
            if ($path && Storage::disk('public')->exists($path)) {
                $attachments[] = Attachment::fromStorageDisk('public', $path)
                    ->as($item['name'] ?? basename($path))
                    ->withMime($item['mime'] ?? 'application/octet-stream');
            }
        }

        return $attachments;
    }
}
