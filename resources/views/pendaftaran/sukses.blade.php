<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Gintara Net</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f7ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 24px;
            padding: 48px 32px;
            text-align: center;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.1);
            border: 1px solid #e2e8f0;
        }

        .success-icon {
            font-size: 80px;
            background: #f0fdf4;
            width: 120px;
            height: 120px;
            line-height: 120px;
            border-radius: 60px;
            margin: 0 auto 24px;
            border: 3px solid #22c55e;
        }

        .success-icon i {
            color: #22c55e;
        }

        h1 {
            color: #1a2634;
            font-size: 32px;
            margin-bottom: 16px;
            font-weight: 700;
        }

        p {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .btn {
            display: inline-block;
            background: #0066cc;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 102, 204, 0.2);
        }

        .btn:hover {
            background: #004999;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 102, 204, 0.3);
        }

        .info-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px;
            margin-top: 24px;
            border: 1px solid #e2e8f0;
        }

        .info-box p {
            margin-bottom: 8px;
            font-size: 14px;
            color: #4a5568;
        }

        .info-box small {
            color: #718096;
            font-size: 12px;
        }

        .info-box i {
            color: #0066cc;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="success-icon">
            <i class="bi bi-check-lg"></i>
        </div>

        <h1>Pendaftaran Berhasil!</h1>

        <p>Terima kasih telah mendaftar layanan Panic Button Gintara Net.<br>
            Data Anda sedang dalam proses verifikasi oleh tim kami.</p>

        <div class="info-box">
            <p><i class="bi bi-phone"></i> Kami akan mengirimkan konfirmasi melalui:</p>
            <p><strong>WhatsApp</strong></p>
            <small>Proses verifikasi maksimal 1x24 jam</small>
        </div>

        <a href="{{ route('pendaftaran.index') }}" class="btn" style="margin-top: 24px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Halaman Pendaftaran
        </a>
    </div>
</body>

</html>
