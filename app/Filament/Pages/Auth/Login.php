<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use App\Services\RecaptchaService;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public ?string $recaptcha_token = null;

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

    public function authenticate(): ?LoginResponse
    {
        $service = app(RecaptchaService::class);

        if ($service->isEnabled()) {
            $result = $service->verify($this->recaptcha_token, 'admin_login', request()->ip());

            if (! $result['success']) {
                throw ValidationException::withMessages([
                    'data.email' => $result['error'] ?? 'Verifikasi keamanan Google reCAPTCHA gagal atau terdeteksi bot.',
                ]);
            }
        }

        return parent::authenticate();
    }
}
