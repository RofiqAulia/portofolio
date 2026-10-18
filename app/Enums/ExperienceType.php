<?php

declare(strict_types=1);

namespace App\Enums;

enum ExperienceType: string
{
    case Internship = 'internship';
    case Organization = 'organization';

    public function label(): string
    {
        return match ($this) {
            self::Internship => 'Magang',
            self::Organization => 'Organisasi',
        };
    }
}
