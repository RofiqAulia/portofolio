<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Http\UploadedFile;

final readonly class MessageData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $subject,
        public string $body,
        public ?string $ipAddress = null,
        public ?UploadedFile $attachment = null,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload, ?string $ip = null): self
    {
        return new self(
            name: (string) ($payload['name'] ?? ''),
            email: (string) ($payload['email'] ?? ''),
            subject: isset($payload['subject']) ? (string) $payload['subject'] : null,
            body: (string) ($payload['body'] ?? ''),
            ipAddress: $ip,
            attachment: ($payload['attachment'] ?? null) instanceof UploadedFile ? $payload['attachment'] : null,
        );
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
        ];
    }
}
