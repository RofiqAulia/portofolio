<?php

declare(strict_types=1);

namespace App\Rules;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class RecaptchaV3Rule implements ValidationRule
{
    public function __construct(
        protected ?string $expectedAction = null,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $service = app(RecaptchaService::class);

        if (! $service->isEnabled()) {
            return;
        }

        $token = is_string($value) ? $value : null;
        $result = $service->verify($token, $this->expectedAction, request()->ip());

        if (! $result['success']) {
            $fail($result['error'] ?? 'Verifikasi Google reCAPTCHA gagal.');
        }
    }
}
