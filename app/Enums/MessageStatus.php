<?php

declare(strict_types=1);

namespace App\Enums;

enum MessageStatus: string
{
    case New = 'new';
    case Read = 'read';
    case Replied = 'replied';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Baru',
            self::Read => 'Dibaca',
            self::Replied => 'Dibalas',
            self::Archived => 'Diarsipkan',
        };
    }
}
