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

    public const MAX_ATTACHMENT_BYTES = 15728640; // 15 MB (Batas aman Gmail SMTP)

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        // Jika total berkas melebihi 15 MB, jangan membebani socket SMTP agar tidak timeout/ditolak Gmail (limit 25MB).
        // Admin tetap dapat mengunduh langsung via tombol unduh di email atau dashboard.
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
