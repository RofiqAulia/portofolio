<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AttachmentStorage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

final class LocalAttachmentStorage implements AttachmentStorage
{
    public function __construct(
        private readonly Filesystem $disk
    ) {}

    public function store(UploadedFile $file, string $directory = 'attachments'): string
    {
        $path = $file->store($directory, 'public');
        if ($path === false) {
            throw new \RuntimeException('Failed to store attachment file.');
        }

        return $path;
    }

    public function delete(string $path): bool
    {
        if ($this->exists($path)) {
            return $this->disk->delete($path);
        }

        return false;
    }

    public function exists(string $path): bool
    {
        return $this->disk->exists($path);
    }

    public function url(string $path): string
    {
        return $this->disk->url($path);
    }
}
