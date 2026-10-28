<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan Pesan</title>
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
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .reply-box {
            font-size: 15px;
            color: #1e293b;
            line-height: 1.7;
            margin-bottom: 28px;
            white-space: pre-wrap;
        }
        .original-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #94a3b8;
            border-radius: 6px;
            padding: 16px;
            font-size: 13px;
            color: #64748b;
            margin-top: 24px;
        }
        .original-title {
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            margin-bottom: 8px;
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
            <h1>Balasan Pesan</h1>
            <p>M. Rofiq Aulia - Portofolio</p>
        </div>

        <div class="content">
            <div class="greeting">Halo, {{ $messageRecord->name }}!</div>

            <div class="reply-box">{!! nl2br(e($replyBody)) !!}</div>

            <div class="closing">
                <div>Salam hangat,</div>
                <div class="author-name">M. Rofiq Aulia</div>
                <div class="author-role">Software Engineer & Solution Architect</div>
            </div>

            <div class="original-box">
                <div class="original-title">Kutipan Pesan Anda Sebelumnya ({{ $messageRecord->created_at?->format('d M Y, H:i') ?? 'Terkirim' }}):</div>
                <div style="font-weight: 600; margin-bottom: 4px;">Subjek: {{ $messageRecord->subject }}</div>
                <div style="white-space: pre-wrap;">{{ $messageRecord->body }}</div>
            </div>
        </div>

        <div class="footer">
            Email ini dikirim oleh M. Rofiq Aulia melalui sistem Portofolio sebagai balasan resmi atas pesan Anda.
        </div>
    </div>
</body>
</html>
