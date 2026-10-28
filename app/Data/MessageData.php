<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Http\UploadedFile;

final readonly class MessageData
{
    /**
     * @param  array<int, UploadedFile>  $attachments
     */
    public function __construct(
        public string $name,
        public string $email,
        public ?string $subject,
        public string $body,
        public ?string $ipAddress = null,
        public ?UploadedFile $attachment = null,
        public array $attachments = [],
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload, ?string $ip = null): self
    {
        $rawAttachments = $payload['attachments'] ?? [];
        $attachments = [];

        if (is_array($rawAttachments)) {
            foreach ($rawAttachments as $file) {
                if ($file instanceof UploadedFile) {
                    $attachments[] = $file;
                }
            }
        }

        $singleAttachment = ($payload['attachment'] ?? null) instanceof UploadedFile ? $payload['attachment'] : null;

        if ($singleAttachment && empty($attachments)) {
            $attachments[] = $singleAttachment;
        }

        return new self(
            name: (string) ($payload['name'] ?? ''),
            email: (string) ($payload['email'] ?? ''),
            subject: isset($payload['subject']) ? (string) $payload['subject'] : null,
            body: (string) ($payload['body'] ?? ''),
            ipAddress: $ip,
            attachment: $singleAttachment,
            attachments: $attachments,
        );
    }

    /**
     * @return array<int, UploadedFile>
     */
    public function allAttachments(): array
    {
        if (! empty($this->attachments)) {
            return $this->attachments;
        }

        return $this->attachment !== null ? [$this->attachment] : [];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'body' => $this->body,
            'ip_address' => $this->ipAddress,
            'attachment' => $this->attachment,
            'attachments' => $this->attachments,
        ];
    }
}
