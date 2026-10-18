<?php

declare(strict_types=1);

namespace App\Data;

final readonly class PageViewData
{
    public function __construct(
        public string $page,
        public string $visitorHash,
        public ?int $projectId = null,
        public ?string $referrer = null,
        public ?string $userAgent = null,
    ) {}
}
