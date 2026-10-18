<?php

declare(strict_types=1);

namespace App\Enums;

enum DocumentationType: string
{
    case Image = 'image';
    case Document = 'document';
    case Link = 'link';

    public function label(): string
    {
        return match ($this) {
            self::Image => 'Diagram / Gambar',
            self::Document => 'Dokumen / PDF',
            self::Link => 'Tautan Eksternal',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Image => 'primary',
            self::Document => 'warning',
            self::Link => 'info',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Image => 'bi-image',
            self::Document => 'bi-file-earmark-text',
            self::Link => 'bi-box-arrow-up-right',
        };
    }

    public function isImage(): bool
    {
        return $this === self::Image;
    }

    public function isDocument(): bool
    {
        return $this === self::Document;
    }

    public function isLink(): bool
    {
        return $this === self::Link;
    }
}
