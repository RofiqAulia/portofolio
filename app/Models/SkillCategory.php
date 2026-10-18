<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SkillType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => SkillType::class,
            'sort_order' => 'integer',
        ];
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('sort_order');
    }
}
