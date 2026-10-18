<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperienceHighlight extends Model
{
    use HasFactory;

    protected $fillable = [
        'experience_id',
        'content',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }
}
