<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AttachmentHandler;
use App\Contracts\AttachmentStorage;
use App\Exceptions\InvalidAttachmentException;
use App\Models\ExperienceAttachment;
use Illuminate\Http\UploadedFile;

final class FileAttachmentHandler implements AttachmentHandler
{
    public function __construct(
        private readonly AttachmentStorage $storage
    ) {}

    public function handle(array $data, ?UploadedFile $file = null, ?ExperienceAttachment $existing = null): array
    {
        if ($file === null && ($existing === null || empty($existing->file_path))) {
            throw InvalidAttachmentException::fileRequired();
        }

        $data['url'] = null;

        if ($file !== null) {
            // Remove old file if replacing
            if ($existing !== null && $existing->file_path) {
                $this->storage->delete($existing->file_path);
            }

            $path = $this->storage->store($file, 'attachments');
            $data['file_path'] = $path;
            $data['original_name'] = $file->getClientOriginalName();
            $data['mime_type'] = $file->getClientMimeType();
            $data['file_size'] = $file->getSize();
        }

        return $data;
    }
}
