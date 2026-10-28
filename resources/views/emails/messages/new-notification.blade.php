<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru Masuk</title>
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
            padding: 24px 32px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .content {
            padding: 32px;
        }
        .badge {
            display: inline-block;
            background-color: #eff6ff;
            color: #2F5BFF;
            font-weight: 600;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 9999px;
            margin-bottom: 16px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .info-table td {
            padding: 8px 0;
            vertical-align: top;
            font-size: 14px;
        }
        .info-table .label {
            width: 120px;
            color: #64748b;
            font-weight: 500;
        }
        .info-table .value {
            color: #0f172a;
            font-weight: 600;
        }
        .message-box {
            background-color: #f8fafc;
            border-left: 4px solid #2F5BFF;
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #334155;
            white-space: pre-wrap;
        }
        .attachment-box {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            color: #475569;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }
        .btn {
            display: inline-block;
            background-color: #2F5BFF;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
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
            <h1>Notifikasi Pesan Baru</h1>
            <p>Formulir Kontak Portofolio</p>
        </div>

        <div class="content">
            <span class="badge">Pesan Kontak Baru</span>

            <table class="info-table">
                <tr>
                    <td class="label">Nama Pengirim</td>
                    <td class="value">{{ $messageRecord->name }}</td>
                </tr>
                <tr>
                    <td class="label">Email Pengirim</td>
                    <td class="value"><a href="mailto:{{ $messageRecord->email }}" style="color: #2F5BFF; text-decoration: none;">{{ $messageRecord->email }}</a></td>
                </tr>
                <tr>
                    <td class="label">Subjek</td>
                    <td class="value">{{ $messageRecord->subject }}</td>
                </tr>
                <tr>
                    <td class="label">Waktu Masuk</td>
                    <td class="value">{{ $messageRecord->created_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }} WIB</td>
                </tr>
                @if($messageRecord->ip_address)
                <tr>
                    <td class="label">Alamat IP</td>
                    <td class="value" style="font-family: monospace; font-size: 13px; color: #64748b;">{{ $messageRecord->ip_address }}</td>
                </tr>
                @endif
            </table>

            <p style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Isi Pesan:</p>
            <div class="message-box">{{ $messageRecord->body }}</div>

            @if($messageRecord->hasAttachment())
                <div class="attachment-box">
                    📎 <strong>Lampiran:</strong>&nbsp;{{ $messageRecord->attachment_name }} ({{ $messageRecord->formattedAttachmentSize() }})
                </div>
            @endif

            <div style="text-align: center; margin-top: 28px;">
                <a href="{{ $adminUrl }}" class="btn">Buka di Dashboard Admin</a>
            </div>
        </div>

        <div class="footer">
            Email ini dikirim otomatis oleh sistem notifikasi Portofolio M. Rofiq Aulia.
        </div>
    </div>
</body>
</html>
