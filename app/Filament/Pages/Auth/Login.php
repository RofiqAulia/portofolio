<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    public function getHeading(): string|Htmlable
    {
        return 'Masuk ke Panel Portofolio';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return new HtmlString(
            'Panel administrasi portofolio profesional berbasis <strong>Filament</strong>. '.
            '<br><a href="/" class="text-sm font-semibold text-primary-600 hover:text-primary-500 underline inline-flex items-center gap-1 mt-1">← Kembali ke Beranda Publik</a>'
        );
    }
}
