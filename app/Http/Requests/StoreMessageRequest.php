<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Data\MessageData;
use App\Rules\RecaptchaV3Rule;
use App\Services\RecaptchaService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->missing('recaptcha_token') && $this->filled('g-recaptcha-response')) {
            $this->merge([
                'recaptcha_token' => $this->input('g-recaptcha-response'),
            ]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $recaptchaService = app(RecaptchaService::class);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:5', 'max:3000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => [
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,7z,csv,jpg,jpeg,png,webp,gif,svg,bmp',
                'max:10240',
            ],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,7z,csv,jpg,jpeg,png,webp,gif,svg,bmp',
                'max:10240',
            ],
            'company_website' => ['nullable', 'max:0'], // Honeypot field: must be empty
        ];

        if ($recaptchaService->isEnabled()) {
            $rules['recaptcha_token'] = ['required', 'string', new RecaptchaV3Rule('contact_form')];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'body.required' => 'Pesan wajib diisi.',
            'body.min' => 'Pesan minimal terdiri dari 5 karakter.',
            'attachments.array' => 'Daftar berkas lampiran tidak valid.',
            'attachments.max' => 'Maksimal 5 berkas lampiran yang dapat diunggah sekaligus.',
            'attachments.*.file' => 'Salah satu berkas lampiran tidak valid.',
            'attachments.*.mimes' => 'Format berkas harus berupa dokumen (PDF, Word, Excel, ZIP) atau gambar (JPG, PNG, WebP, SVG).',
            'attachments.*.max' => 'Ukuran setiap berkas lampiran maksimal 10 MB.',
            'attachment.file' => 'Berkas lampiran tidak valid.',
            'attachment.mimes' => 'Format berkas harus berupa dokumen (PDF, Word, Excel, ZIP) atau gambar (JPG, PNG, WebP, SVG).',
            'attachment.max' => 'Ukuran berkas lampiran maksimal 10 MB.',
            'company_website.max' => 'Terdeteksi spam bot.',
            'recaptcha_token.required' => 'Verifikasi keamanan reCAPTCHA wajib diselesaikan.',
        ];
    }

    public function toData(): MessageData
    {
        $payload = $this->validated();

        if ($this->hasFile('attachments')) {
            $payload['attachments'] = $this->file('attachments');
        }

        if ($this->hasFile('attachment')) {
            $payload['attachment'] = $this->file('attachment');
        }

        return MessageData::fromArray(
            payload: $payload,
            ip: $this->ip(),
        );
    }
}
