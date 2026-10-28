<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MessageStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'body',
        'attachment_path',
        'attachment_name',
        'attachment_mime',
        'attachment_size',
        'attachments',
        'status',
        'ip_address',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => MessageStatus::class,
            'read_at' => 'datetime',
            'attachment_size' => 'integer',
            'attachments' => 'array',
        ];
    }

    /**
     * @return array<int, array{path: string, name: string, mime: ?string, size: ?int}>
     */
    public function getAttachments(): array
    {
        if (is_array($this->attachments) && count($this->attachments) > 0) {
            return array_values($this->attachments);
        }

        if (! empty($this->attachment_path)) {
            return [
                [
                    'path' => $this->attachment_path,
                    'name' => $this->attachment_name ?? basename($this->attachment_path),
                    'mime' => $this->attachment_mime,
                    'size' => $this->attachment_size,
                ],
            ];
        }

        return [];
    }

    public function hasAttachment(): bool
    {
        return count($this->getAttachments()) > 0;
    }

    public function hasMultipleAttachments(): bool
    {
        return count($this->getAttachments()) > 1;
    }

    public function isImageAttachment(?int $index = 0): bool
    {
        $attachments = $this->getAttachments();
        $target = $attachments[$index] ?? null;

        if (! $target) {
            return false;
        }

        $mime = $target['mime'] ?? '';

        return str_starts_with((string) $mime, 'image/');
    }

    public function totalAttachmentSize(): int
    {
        return (int) array_sum(array_column($this->getAttachments(), 'size'));
    }

    public function formattedAttachmentSize(?int $index = null): string
    {
        if ($index !== null) {
            $attachments = $this->getAttachments();
            $bytes = (int) ($attachments[$index]['size'] ?? 0);

            return $this->formatBytes($bytes);
        }

        $total = $this->totalAttachmentSize();
        if ($total <= 0 && $this->attachment_size) {
            $total = (int) $this->attachment_size;
        }

        return $this->formatBytes($total);
    }

    public function getAttachmentDownloadUrl(int $index = 0): string
    {
        $token = substr(sha1($this->email.(string) $this->created_at), 0, 16);

        return route('portfolio.messages.download-attachment', [
            'message' => $this->id,
            'index' => $index,
            'token' => $token,
        ]);
    }

    public function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '';
        }

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1).' MB';
        }

        return round($bytes / 1024, 1).' KB';
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', MessageStatus::New);
    }
}
