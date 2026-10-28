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

    public function test_user_can_submit_contact_form_with_multiple_attachments(): void
    {
        Storage::fake('public');
        Mail::fake();

        $pdf = UploadedFile::fake()->create('spesifikasi.pdf', 300, 'application/pdf');
        $image = UploadedFile::fake()->image('desain.png', 400, 300);

        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Klien Multi File',
            'email' => 'multifile@example.com',
            'subject' => 'Diskusi Proyek Multi Berkas',
            'body' => 'Berikut kami lampirkan dua berkas penting untuk dianalisis.',
            'attachments' => [$pdf, $image],
            'company_website' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $message = Message::where('email', 'multifile@example.com')->first();
        $this->assertNotNull($message);
        $this->assertTrue($message->hasAttachment());
        $this->assertTrue($message->hasMultipleAttachments());
        $this->assertCount(2, $message->getAttachments());

        $attachments = $message->getAttachments();
        $this->assertEquals('spesifikasi.pdf', $attachments[0]['name']);
        $this->assertEquals('desain.png', $attachments[1]['name']);

        Storage::disk('public')->assertExists($attachments[0]['path']);
        Storage::disk('public')->assertExists($attachments[1]['path']);

        // Pastikan kedua email memiliki lampiran MIME
        Mail::assertSent(NewMessageNotificationMail::class, function (NewMessageNotificationMail $mail) {
            $attachments = $mail->attachments();

            return count($attachments) === 2;
        });

        Mail::assertSent(MessageAutoReplyMail::class, function (MessageAutoReplyMail $mail) {
            $attachments = $mail->attachments();

            return count($attachments) === 2;
        });
    }

    public function test_user_can_download_message_attachment_via_secure_url(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('undangan.pdf', 100, 'application/pdf');

        $this->post(route('portfolio.contact.send'), [
            'name' => 'Klien Unduh Berkas',
            'email' => 'unduh@example.com',
            'subject' => 'Uji Coba Unduh Berkas',
            'body' => 'Pesan pengujian untuk menguji URL unduhan berkas lampiran.',
            'attachments' => [$file],
            'company_website' => '',
        ]);

        $message = Message::where('email', 'unduh@example.com')->firstOrFail();
        $downloadUrl = $message->getAttachmentDownloadUrl(0);

        $downloadResponse = $this->get($downloadUrl);

        $downloadResponse->assertOk();
        $downloadResponse->assertDownload('undangan.pdf');
    }

    public function test_contact_form_rejects_more_than_5_attachments(): void
    {
        Storage::fake('public');

        $files = [
            UploadedFile::fake()->create('f1.pdf', 50, 'application/pdf'),
            UploadedFile::fake()->create('f2.pdf', 50, 'application/pdf'),
            UploadedFile::fake()->create('f3.pdf', 50, 'application/pdf'),
            UploadedFile::fake()->create('f4.pdf', 50, 'application/pdf'),
            UploadedFile::fake()->create('f5.pdf', 50, 'application/pdf'),
            UploadedFile::fake()->create('f6.pdf', 50, 'application/pdf'),
        ];

        $response = $this->post(route('portfolio.contact.send'), [
            'name' => 'Klien Terlalu Banyak Berkas',
            'email' => 'toomany@example.com',
            'subject' => 'Upload 6 Berkas Sekaligus',
            'body' => 'Mencoba mengirim 6 berkas sekaligus padahal batasnya 5.',
            'attachments' => $files,
            'company_website' => '',
        ]);

        $response->assertSessionHasErrors(['attachments']);
        $this->assertEquals(0, Message::where('email', 'toomany@example.com')->count());
    }
}
