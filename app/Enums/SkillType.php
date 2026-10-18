<?php

declare(strict_types=1);

namespace App\Enums;

enum SkillType: string
{
    case Hard = 'hard';
    case Soft = 'soft';
    case Analysis = 'analysis';

    public function label(): string
    {
        return match ($this) {
            self::Hard => 'Hard Skill',
            self::Soft => 'Soft Skill',
            self::Analysis => 'Analisis & Desain',
        };
    }
}
