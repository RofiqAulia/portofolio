<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\MessageStatus;
use App\Mail\MessageAutoReplyMail;
use App\Mail\NewMessageNotificationMail;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_contact_form_successfully(): void
    {
        // Act
        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Klien Pengujian',
            'email' => 'klien@test.com',
            'subject' => 'Tawaran Proyek Laravel',
            'body' => 'Halo, ini adalah pesan pengujian tawaran proyek yang valid.',
            'company_website' => '', // Honeypot empty
        ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('messages', [
            'name' => 'Klien Pengujian',
            'email' => 'klien@test.com',
            'status' => MessageStatus::New->value,
        ]);
    }

    public function test_user_can_submit_contact_form_with_pdf_attachment(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Klien Lampiran PDF',
            'email' => 'pdf@test.com',
            'subject' => 'Lampiran PDF RFP Proyek',
            'body' => 'Berikut terlampir dokumen spesifikasi kebutuhan sistem dalam bentuk PDF.',
            'attachment' => $file,
            'company_website' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $message = Message::where('email', 'pdf@test.com')->first();
        $this->assertNotNull($message);
        $this->assertEquals('document.pdf', $message->attachment_name);
        $this->assertEquals('application/pdf', $message->attachment_mime);
        $this->assertNotNull($message->attachment_path);
        Storage::disk('public')->assertExists($message->attachment_path);
    }

    public function test_user_can_submit_contact_form_with_image_attachment(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('mockup.png', 800, 600);

        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Klien Lampiran Gambar',
            'email' => 'image@test.com',
            'subject' => 'Lampiran Gambar UI Mockup',
            'body' => 'Berikut terlampir mockup desain tampilan UI untuk didiskusikan bersama.',
            'attachment' => $image,
            'company_website' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $message = Message::where('email', 'image@test.com')->first();
        $this->assertNotNull($message);
        $this->assertEquals('mockup.png', $message->attachment_name);
        $this->assertTrue($message->isImageAttachment());
        $this->assertNotNull($message->attachment_path);
        Storage::disk('public')->assertExists($message->attachment_path);
    }

    public function test_contact_form_fails_validation_with_missing_name_or_email(): void
    {
        // Act
        $response = $this->post(route('portfolio.contact.send'), [
            'name' => '',
            'email' => 'bukan-email',
            'body' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors(['name', 'email', 'body']);
        $this->assertEquals(0, Message::count());
    }

    public function test_contact_form_rejects_disallowed_file_types(): void
    {
        Storage::fake('public');

        $executable = UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload');

        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Spam Executable',
            'email' => 'spam@test.com',
            'body' => 'Mencoba mengirimkan file exe terlarang.',
            'attachment' => $executable,
            'company_website' => '',
        ]);

        $response->assertSessionHasErrors(['attachment']);
        $this->assertEquals(0, Message::count());
    }

    public function test_contact_form_rejects_file_larger_than_10mb(): void
    {
        Storage::fake('public');

        $largeFile = UploadedFile::fake()->create('large_file.pdf', 11264, 'application/pdf');

        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Klien File Besar',
            'email' => 'large@test.com',
            'body' => 'Mencoba mengirimkan file berukuran melebihi batas 10 MB.',
            'attachment' => $largeFile,
            'company_website' => '',
        ]);

        $response->assertSessionHasErrors(['attachment']);
        $this->assertEquals(0, Message::count());
    }

    public function test_contact_form_rejects_spambot_when_honeypot_is_filled(): void
    {
        // Act
        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Bot Spammer',
            'email' => 'bot@spammer.com',
            'body' => 'Saya adalah robot yang mengisi field honeypot.',
            'company_website' => 'https://spam.com', // Filled honeypot
        ]);

        // Assert
        $response->assertSessionHasErrors(['company_website']);
        $this->assertEquals(0, Message::count());
    }

    public function test_contact_form_sends_mutual_notification_emails(): void
    {
        Mail::fake();

        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'subject' => 'Tawaran Kerjasama Website',
            'body' => 'Halo, saya ingin mendiskusikan peluang kerjasama pengembangan website.',
            'company_website' => '',
        ]);

        $response->assertRedirect();

        // 1. Penerima (admin: mrofiqaulia@gmail.com) menerima notifikasi pesan baru
        Mail::assertSent(NewMessageNotificationMail::class, function ($mail) {
            return $mail->hasTo('mrofiqaulia@gmail.com')
                && $mail->messageRecord->name === 'Budi Santoso'
                && $mail->messageRecord->subject === 'Tawaran Kerjasama Website';
        });

        // 2. Pengirim (budi@example.com) menerima auto-reply konfirmasi penerimaan pesan
        Mail::assertSent(MessageAutoReplyMail::class, function ($mail) {
            return $mail->hasTo('budi@example.com')
                && $mail->messageRecord->name === 'Budi Santoso';
        });
    }
}
