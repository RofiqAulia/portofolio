<?php

declare(strict_types=1);

namespace App\Services;

final class VisitorHasher
{
    public function __construct(
        private readonly string $appKey
    ) {}

    public function hash(string $ip, ?string $userAgent, ?string $date = null): string
    {
        $date = $date ?? date('Y-m-d');
        $raw = sprintf('%s|%s|%s|%s', $ip, $userAgent ?? 'unknown', $date, $this->appKey);

        return hash('sha256', $raw);
    }
}
