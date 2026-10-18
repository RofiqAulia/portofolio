<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

trait RedirectsToPreviousUrl
{
    protected function getRedirectUrl(): string
    {
        if (
            filled($this->previousUrl) &&
            $this->previousUrl !== request()->fullUrl() &&
            $this->previousUrl !== request()->url()
        ) {
            return $this->previousUrl;
        }

        return $this->getResource()::getUrl('index');
    }
}
