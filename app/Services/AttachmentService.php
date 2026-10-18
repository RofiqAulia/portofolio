<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AttachmentHandler;
use App\Enums\AttachmentType;

final class AttachmentService
{
    public function __construct(
        private readonly FileAttachmentHandler $fileHandler,
        private readonly LinkAttachmentHandler $linkHandler
    ) {}

    public function resolveHandler(AttachmentType $type): AttachmentHandler
    {
        return match ($type) {
            AttachmentType::File => $this->fileHandler,
            AttachmentType::Link => $this->linkHandler,
        };
    }
}
