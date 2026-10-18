<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class InvalidAttachmentException extends RuntimeException
{
    public static function fileRequired(): self
    {
        return new self('Attachment tipe file harus menyertakan file dan URL harus kosong.');
    }

    public static function linkRequired(): self
    {
        return new self('Attachment tipe link harus menyertakan URL valid dan file harus kosong.');
    }
}
