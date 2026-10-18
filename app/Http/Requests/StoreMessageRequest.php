<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Data\MessageData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:5', 'max:3000'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,7z,csv,jpg,jpeg,png,webp,gif,svg,bmp',
                'max:10240',
            ],
            'company_website' => ['nullable', 'max:0'], // Honeypot field: must be empty
        ];
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
            'attachment.file' => 'Berkas lampiran tidak valid.',
            'attachment.mimes' => 'Format berkas harus berupa dokumen (PDF, Word, Excel, ZIP) atau gambar (JPG, PNG, WebP, SVG).',
            'attachment.max' => 'Ukuran berkas lampiran maksimal 10 MB.',
            'company_website.max' => 'Terdeteksi spam bot.',
        ];
    }

    public function toData(): MessageData
    {
        $payload = $this->validated();
        if ($this->hasFile('attachment')) {
            $payload['attachment'] = $this->file('attachment');
        }

        return MessageData::fromArray(
            payload: $payload,
            ip: $this->ip(),
        );
    }
}
