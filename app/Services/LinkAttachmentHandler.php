<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AttachmentHandler;
use App\Contracts\AttachmentStorage;
use App\Exceptions\InvalidAttachmentException;
use App\Models\ExperienceAttachment;
use Illuminate\Http\UploadedFile;

final class LinkAttachmentHandler implements AttachmentHandler
{
    public function __construct(
        private readonly AttachmentStorage $storage
    ) {}

    public function handle(array $data, ?UploadedFile $file = null, ?ExperienceAttachment $existing = null): array
    {
        if (empty($data['url'])) {
            throw InvalidAttachmentException::linkRequired();
        }

        // If previously was a file, remove the old file
        if ($existing !== null && $existing->file_path) {
            $this->storage->delete($existing->file_path);
        }

        $data['file_path'] = null;
        $data['original_name'] = null;
        $data['mime_type'] = null;
        $data['file_size'] = null;

        return $data;
    }
}
