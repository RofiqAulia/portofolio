<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class RecaptchaService
{
    public function isEnabled(): bool
    {
        $siteKey = (string) config('services.recaptcha.site_key', '');
        $secretKey = (string) config('services.recaptcha.secret_key', '');

        if ($siteKey === '' || $secretKey === '') {
            return false;
        }

        if (app()->runningUnitTests()) {
            return (bool) config('services.recaptcha.force_in_tests', false);
        }

        return true;
    }

    public function getSiteKey(): string
    {
        return (string) config('services.recaptcha.site_key', '');
    }

    public function getMinimumScore(): float
    {
        return (float) config('services.recaptcha.minimum_score', 0.5);
    }

    /**
     * @return array{success: bool, score: float, action: ?string, error: ?string}
     */
    public function verify(?string $token, ?string $expectedAction = null, ?string $ip = null): array
    {
        if (! $this->isEnabled()) {
            return [
                'success' => true,
                'score' => 1.0,
                'action' => $expectedAction,
                'error' => null,
            ];
        }

        if (empty($token)) {
            return [
                'success' => false,
                'score' => 0.0,
                'action' => null,
                'error' => 'Token verifikasi keamanan reCAPTCHA tidak ditemukan.',
            ];
        }

        try {
            $secret = (string) config('services.recaptcha.secret_key');
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://www.google.com/recaptcha/api/siteverify', array_filter([
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $ip,
                ]));

            if (! $response->successful()) {
                Log::warning('Gagal menghubungi Google reCAPTCHA API: '.$response->status());

                return [
                    'success' => false,
                    'score' => 0.0,
                    'action' => null,
                    'error' => 'Layanan verifikasi keamanan sedang mengalami gangguan.',
                ];
            }

            /** @var array{success?: bool, score?: float, action?: string, 'error-codes'?: array<string>} $json */
            $json = $response->json();

            $isSuccess = (bool) ($json['success'] ?? false);
            $score = (float) ($json['score'] ?? 0.0);
            $action = isset($json['action']) ? (string) $json['action'] : null;

            if (! $isSuccess) {
                $errorCodes = implode(', ', $json['error-codes'] ?? []);
                Log::info('Verifikasi reCAPTCHA ditolak oleh Google: '.$errorCodes);

                return [
                    'success' => false,
                    'score' => $score,
                    'action' => $action,
                    'error' => 'Verifikasi keamanan Google reCAPTCHA tidak valid.',
                ];
            }

            if ($score < $this->getMinimumScore()) {
                Log::info("Skor reCAPTCHA ({$score}) lebih rendah dari batas minimal ({$this->getMinimumScore()})");

                return [
                    'success' => false,
                    'score' => $score,
                    'action' => $action,
                    'error' => 'Terdeteksi aktivitas mencurigakan atau bot pada permintaan Anda.',
                ];
            }

            if ($expectedAction !== null && $action !== null && $action !== $expectedAction) {
                Log::info("Tindakan reCAPTCHA tidak cocok: diharapkan '{$expectedAction}', diterima '{$action}'");

                return [
                    'success' => false,
                    'score' => $score,
                    'action' => $action,
                    'error' => 'Tindakan verifikasi keamanan tidak sesuai.',
                ];
            }

            return [
                'success' => true,
                'score' => $score,
                'action' => $action,
                'error' => null,
            ];
        } catch (Throwable $e) {
            Log::error('Eksepsi saat verifikasi reCAPTCHA: '.$e->getMessage(), ['exception' => $e]);

            return [
                'success' => false,
                'score' => 0.0,
                'action' => null,
                'error' => 'Terjadi kesalahan sistem saat memproses verifikasi keamanan.',
            ];
        }
    }
}
