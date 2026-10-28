<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Anda Telah Diterima</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 24px;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #2F5BFF;
            color: #ffffff;
            padding: 28px 32px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .content {
            padding: 32px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .text {
            font-size: 14px;
            color: #334155;
            margin-bottom: 20px;
        }
        .summary-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .summary-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 12px;
        }
        .summary-item {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .summary-item strong {
            color: #0f172a;
        }
        .message-quote {
            background-color: #ffffff;
            border-left: 3px solid #2F5BFF;
            padding: 12px 16px;
            margin-top: 8px;
            border-radius: 0 6px 6px 0;
            color: #475569;
            font-size: 13px;
            white-space: pre-wrap;
        }
        .closing {
            margin-top: 28px;
            font-size: 14px;
            color: #334155;
        }
        .author-name {
            font-weight: 700;
            color: #0f172a;
            margin-top: 4px;
        }
        .author-role {
            font-size: 13px;
            color: #64748b;
        }
        .footer {
            padding: 20px 32px;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pesan Anda Telah Diterima</h1>
            <p>Konfirmasi Otomatis Portofolio M. Rofiq Aulia</p>
        </div>

        <div class="content">
            <div class="greeting">Halo, {{ $messageRecord->name }}!</div>

            <p class="text">
                Terima kasih telah menghubungi saya melalui formulir kontak portofolio. Pesan Anda telah berhasil saya terima dan saat ini sedang saya tinjau.
            </p>

            <div class="summary-card">
                <div class="summary-title">Ringkasan Pesan yang Dikirimkan</div>
                <div class="summary-item"><strong>Subjek:</strong> {{ $messageRecord->subject }}</div>
                <div class="summary-item"><strong>Waktu:</strong> {{ $messageRecord->created_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }} WIB</div>
                @if($messageRecord->hasAttachment())
                    <div class="summary-item" style="margin-top: 14px;">
                        <strong>Lampiran ({{ count($messageRecord->getAttachments()) }} berkas):</strong>
                        <div style="margin-top: 8px;">
                            @foreach($messageRecord->getAttachments() as $attachment)
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 8px; width: 100%;">
                                    <tr>
                                        <td style="padding: 10px 14px; font-size: 13px; color: #1e293b;">
                                            📎 <strong>{{ $attachment['name'] }}</strong>
                                            @if(!empty($attachment['size']))
                                                <span style="color: #64748b; font-size: 12px; margin-left: 4px;">({{ $messageRecord->formatBytes((int) $attachment['size']) }})</span>
                                            @endif
                                        </td>
                                        <td align="right" style="padding: 10px 14px; width: 90px; text-align: right;">
                                            <a href="{{ $messageRecord->getAttachmentDownloadUrl($loop->index) }}" style="display: inline-block; padding: 6px 14px; background-color: #2F5BFF; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: 600; text-align: center;">
                                                Unduh
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endforeach
                            <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                                💡 <em>Berkas juga telah dilampirkan langsung pada email ini dan dapat dibuka dari baki lampiran di bagian bawah email Anda.</em>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="summary-item" style="margin-top: 12px;"><strong>Isi Pesan:</strong></div>
                <div class="message-quote">{{ $messageRecord->body }}</div>
            </div>

            <p class="text">
                Saya berusaha merespons setiap pesan secepat mungkin (biasanya dalam kurun waktu 1x24 jam kerja). Jika ada informasi mendesak atau tambahan berkas yang ingin disampaikan, Anda dapat membalas email ini secara langsung.
            </p>

            <div class="closing">
                <div>Salam hangat,</div>
                <div class="author-name">M. Rofiq Aulia</div>
                <div class="author-role">Software Engineer & Solution Architect</div>
            </div>
        </div>

        <div class="footer">
            Email ini dikirim secara otomatis ke {{ $messageRecord->email }} sebagai tanda terima pesan portofolio.
        </div>
    </div>
</body>
</html>
