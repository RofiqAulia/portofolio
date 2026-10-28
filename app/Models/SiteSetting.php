<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    public const CACHE_KEY = 'site_settings_all_dict';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget(self::CACHE_KEY);
        });
    }

    /**
     * @return array<string, string|null>
     */
    public static function allKeyed(): array
    {
        return Cache::remember(self::CACHE_KEY, 86400, function (): array {
            return static::pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $settings = static::allKeyed();

        return array_key_exists($key, $settings) && $settings[$key] !== null
            ? (string) $settings[$key]
            : $default;
    }

    public static function set(string $key, ?string $value, string $group = 'general'): self
    {
        $model = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget(self::CACHE_KEY);

        return $model;
    }
}
