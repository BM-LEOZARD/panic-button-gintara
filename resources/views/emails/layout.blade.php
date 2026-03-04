<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $pageTitle ?? 'Notifikasi' }} - Gintara Net</title>
    <style>
        /* ── Reset ── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f0f4f8;
            padding: 40px 20px;
            color: #1e293b;
        }

        /* ── Wrapper ── */
        .container {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        /* ── Header ── */
        .header {
            background: linear-gradient(135deg, #0b3d5f 0%, #1b6b8f 100%);
            padding: 36px 40px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.65);
            font-size: 13px;
        }

        /* ── Body ── */
        .body {
            padding: 40px;
        }

        /* ── Teks umum ── */
        .greeting {
            font-size: 16px;
            color: #334155;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .info-text {
            font-size: 14px;
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        /* ── OTP box ── */
        .otp-box {
            background: #f8fafc;
            border: 2px dashed #4aa3ff;
            border-radius: 16px;
            padding: 28px;
            text-align: center;
            margin-bottom: 32px;
        }

        .otp-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .otp-code {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 12px;
            color: #0b3d5f;
            font-family: 'Courier New', monospace;
            padding-left: 12px;
            /* offset letter-spacing */
        }

        .otp-expiry {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 10px;
        }

        .otp-expiry strong {
            color: #ef4444;
        }

        /* ── Detail table ── */
        .detail-box {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .detail-box-header {
            background: #0b3d5f;
            padding: 12px 16px;
        }

        .detail-box-header p {
            margin: 0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.8);
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-row td {
            padding: 10px 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .detail-row:last-child td {
            border-bottom: none;
        }

        .detail-label {
            font-size: 13px;
            color: #64748b;
            width: 38%;
            white-space: nowrap;
        }

        .detail-value {
            font-size: 14px;
            color: #0b3d5f;
            font-weight: 700;
            word-break: break-all;
        }

        /* ── Warning box ── */
        .warning {
            background: #fef9ec;
            border-left: 4px solid #f59e0b;
            border-radius: 0 8px 8px 0;
            padding: 14px 18px;
            font-size: 13px;
            color: #92400e;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        /* ── Success box ── */
        .success-box {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            border-radius: 0 8px 8px 0;
            padding: 14px 18px;
            font-size: 13px;
            color: #166534;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        /* ── Footer ── */
        .footer {
            border-top: 1px solid #e2e8f0;
            padding: 24px 40px;
            text-align: center;
        }

        .footer p {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer .brand {
            font-weight: 700;
            color: #0b3d5f;
        }

        /* ────────────────────────────────────────────────────────────
           Responsive — mobile (max 480px)
           Tabel detail diubah dari 2 kolom horizontal
           menjadi 2 baris vertikal (label atas, nilai bawah)
        ──────────────────────────────────────────────────────────── */
        @media only screen and (max-width: 480px) {

            body {
                padding: 16px 12px;
            }

            .header {
                padding: 28px 20px;
            }

            .header h1 {
                font-size: 18px;
            }

            .body {
                padding: 24px 20px;
            }

            .footer {
                padding: 20px;
            }

            /* Stack tabel detail secara vertikal */
            .detail-table,
            .detail-table tbody,
            .detail-row,
            .detail-row td {
                display: block;
                width: 100% !important;
            }

            .detail-label {
                width: 100% !important;
                white-space: normal;
                padding-top: 12px;
                padding-bottom: 2px;
                padding-left: 14px;
                padding-right: 14px;
                border-bottom: none !important;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #94a3b8;
            }

            .detail-value {
                padding-top: 2px;
                padding-bottom: 12px;
                padding-left: 14px;
                padding-right: 14px;
                font-size: 15px;
            }

            /* OTP tetap terbaca di layar kecil */
            .otp-code {
                font-size: 32px;
                letter-spacing: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        {{-- ── HEADER ── --}}
        <div class="header">
            <h1>GINTARA NET</h1>
            <p>Sistem Panic Button</p>
        </div>

        {{-- ── BODY ── --}}
        <div class="body">
            @yield('content')
        </div>

        {{-- ── FOOTER ── --}}
        <div class="footer">
            <p>
                Email ini dikirim otomatis oleh sistem <span class="brand">Gintara Net</span>.<br>
                Harap tidak membalas email ini.<br>
                &copy; {{ date('Y') }} Gintara Corp. All rights reserved.
            </p>
        </div>

    </div>
</body>

</html>
