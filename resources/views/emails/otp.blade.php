@extends('emails.layout')

@section('content')
    <p class="greeting">Halo, {{ $userName }}! 👋</p>

    <p class="info-text">
        Kami menerima permintaan login ke akun Anda. Gunakan kode OTP berikut untuk menyelesaikan proses verifikasi.
        Kode ini hanya perlu dimasukkan <strong>sekali</strong> — setelah berhasil, Anda tidak perlu memasukkan OTP lagi di
        login berikutnya.
    </p>

    {{-- OTP Code Box --}}
    <div class="otp-box">
        <p class="otp-label">Kode Verifikasi OTP</p>
        <p class="otp-code">{{ $otpCode }}</p>
        <p class="otp-expiry">Kode berlaku selama <strong>3 menit</strong></p>
    </div>

    {{-- Warning --}}
    <div class="warning">
        ⚠️ <strong>Jangan bagikan kode ini kepada siapapun</strong>, termasuk pihak yang mengaku dari Gintara Net.
        Tim kami tidak pernah meminta kode OTP Anda.
    </div>

    <p class="info-text" style="margin-bottom:0;">
        Jika Anda tidak melakukan permintaan login ini, abaikan email ini. Akun Anda tetap aman.
    </p>
@endsection
