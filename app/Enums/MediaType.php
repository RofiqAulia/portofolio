<?php

declare(strict_types=1);

namespace App\Enums;

enum MediaType: string
{
    case Image = 'image';
    case Video = 'video';

    public function label(): string
    {
        return match ($this) {
            self::Image => 'Gambar',
            self::Video => 'Video',
        };
    }
}
