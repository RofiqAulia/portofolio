<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface AttachmentStorage
{
    /**
     * Store an uploaded file and return the stored path.
     */
    public function store(UploadedFile $file, string $directory = 'attachments'): string;

    /**
     * Delete a file by path.
     */
    public function delete(string $path): bool;

    /**
     * Check if a file exists.
     */
    public function exists(string $path): bool;

    /**
     * Get the full URL to the file.
     */
    public function url(string $path): string;
}
