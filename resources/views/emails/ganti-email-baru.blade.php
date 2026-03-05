@extends('emails.layout')

@section('content')
    <p style="margin:0 0 16px;font-size:16px;color:#334155;font-weight:600;">
        Halo, {{ $nama }}! 👋
    </p>

    <p style="margin:0 0 28px;font-size:14px;color:#64748b;line-height:1.7;">
        Email ini berhasil terhubung ke akun Panic Button Anda.
        Mulai sekarang, semua notifikasi sistem akan dikirimkan ke alamat email ini.
    </p>

    @include('emails.partials.detail-table', [
        'rows' => [
            ['Nama', $nama],
            ['Email Terdaftar', $emailBaru],
            ['Wilayah', $namaWilayah],
            ['Waktu Aktif', $waktu],
        ],
    ])

    <div class="success-box">
        <p>✅ <strong>Email Anda kini aktif</strong> dan terhubung dengan sistem Panic Button Gintara Net.
            Gunakan email ini untuk login ke akun Anda.</p>
    </div>

    @include('emails.partials.warning-box', [
        'warning' => 'Jika Anda tidak merasa melakukan perubahan ini, segera hubungi admin wilayah Anda.',
    ])

    @include('emails.partials.login-button')

    <p style="margin:0;font-size:14px;color:#64748b;line-height:1.7;">
        Akun Anda tetap aman selama Anda menjaga kerahasiaan informasi login.
    </p>
@endsection
