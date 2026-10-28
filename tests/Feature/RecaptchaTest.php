<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\RecaptchaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecaptchaTest extends TestCase
{
    use RefreshDatabase;

    public function test_recaptcha_service_is_disabled_by_default_without_keys(): void
    {
        Config::set('services.recaptcha.site_key', '');
        Config::set('services.recaptcha.secret_key', '');

        $service = app(RecaptchaService::class);
        $this->assertFalse($service->isEnabled());

        $result = $service->verify(null, 'test_action');
        $this->assertTrue($result['success']);
        $this->assertEquals(1.0, $result['score']);
    }

    public function test_recaptcha_service_verifies_valid_google_token_successfully(): void
    {
        Config::set('services.recaptcha.site_key', 'test_site_key');
        Config::set('services.recaptcha.secret_key', 'test_secret_key');
        Config::set('services.recaptcha.force_in_tests', true);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.9,
                'action' => 'contact_form',
            ], 200),
        ]);

        $service = app(RecaptchaService::class);
        $result = $service->verify('valid_token_123', 'contact_form');

        $this->assertTrue($result['success']);
        $this->assertEquals(0.9, $result['score']);
        $this->assertEquals('contact_form', $result['action']);
    }

    public function test_recaptcha_service_rejects_low_score_bot(): void
    {
        Config::set('services.recaptcha.site_key', 'test_site_key');
        Config::set('services.recaptcha.secret_key', 'test_secret_key');
        Config::set('services.recaptcha.force_in_tests', true);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.2, // Below 0.5 threshold
                'action' => 'contact_form',
            ], 200),
        ]);

        $service = app(RecaptchaService::class);
        $result = $service->verify('bot_token_456', 'contact_form');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('mencurigakan atau bot', $result['error'] ?? '');
    }

    public function test_recaptcha_service_rejects_action_mismatch(): void
    {
        Config::set('services.recaptcha.site_key', 'test_site_key');
        Config::set('services.recaptcha.secret_key', 'test_secret_key');
        Config::set('services.recaptcha.force_in_tests', true);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.8,
                'action' => 'wrong_action',
            ], 200),
        ]);

        $service = app(RecaptchaService::class);
        $result = $service->verify('token_789', 'expected_action');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('tidak sesuai', $result['error'] ?? '');
    }

    public function test_whatsapp_verification_endpoint_allows_valid_request(): void
    {
        Config::set('services.recaptcha.site_key', 'test_site_key');
        Config::set('services.recaptcha.secret_key', 'test_secret_key');
        Config::set('services.recaptcha.force_in_tests', true);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.85,
                'action' => 'whatsapp_click',
            ], 200),
        ]);

        $response = $this->postJson(route('portfolio.contact.verify-wa'), [
            'recaptcha_token' => 'valid_wa_token',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertStringContainsString('wa.me', $response->json('target_url'));
    }

    public function test_whatsapp_verification_endpoint_blocks_bot(): void
    {
        Config::set('services.recaptcha.site_key', 'test_site_key');
        Config::set('services.recaptcha.secret_key', 'test_secret_key');
        Config::set('services.recaptcha.force_in_tests', true);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.1, // Bot
                'action' => 'whatsapp_click',
            ], 200),
        ]);

        $response = $this->postJson(route('portfolio.contact.verify-wa'), [
            'recaptcha_token' => 'bot_wa_token',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_contact_form_requires_recaptcha_token_when_enabled(): void
    {
        Config::set('services.recaptcha.site_key', 'test_site_key');
        Config::set('services.recaptcha.secret_key', 'test_secret_key');
        Config::set('services.recaptcha.force_in_tests', true);

        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Pengguna Pengujian',
            'email' => 'user@example.com',
            'subject' => 'Test',
            'body' => 'Pesan pengujian kontak dengan validasi recaptcha.',
            'company_website' => '',
            // missing recaptcha_token
        ]);

        $response->assertSessionHasErrors(['recaptcha_token']);
    }

    public function test_contact_form_succeeds_with_valid_recaptcha_token(): void
    {
        Config::set('services.recaptcha.site_key', 'test_site_key');
        Config::set('services.recaptcha.secret_key', 'test_secret_key');
        Config::set('services.recaptcha.force_in_tests', true);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.95,
                'action' => 'contact_form',
            ], 200),
        ]);

        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Pengguna Valid',
            'email' => 'valid@example.com',
            'subject' => 'Pertanyaan Kerjasama',
            'body' => 'Pesan pengujian kontak dengan token recaptcha valid.',
            'company_website' => '',
            'recaptcha_token' => 'valid_token_abc',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
