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
        ];
    }

    public function hasAttachment(): bool
    {
        return ! empty($this->attachment_path);
    }

    public function isImageAttachment(): bool
    {
        return $this->attachment_mime !== null && str_starts_with($this->attachment_mime, 'image/');
    }

    public function formattedAttachmentSize(): string
    {
        if (! $this->attachment_size) {
            return '';
        }

        if ($this->attachment_size >= 1048576) {
            return round($this->attachment_size / 1048576, 1).' MB';
        }

        return round($this->attachment_size / 1024, 1).' KB';
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', MessageStatus::New);
    }
}
