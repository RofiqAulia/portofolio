<?php

declare(strict_types=1);

namespace App\Enums;

enum AttachmentType: string
{
    case File = 'file';
    case Link = 'link';

    public function label(): string
    {
        return match ($this) {
            self::File => 'Berkas (File)',
            self::Link => 'Tautan (Link)',
        };
    }
}
