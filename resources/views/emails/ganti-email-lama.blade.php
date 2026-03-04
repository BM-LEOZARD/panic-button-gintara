@extends('emails.layout')

@section('content')
    <p style="margin:0 0 16px;font-size:16px;color:#334155;font-weight:600;">
        Halo, {{ $nama }}! 👋
    </p>

    <p style="margin:0 0 28px;font-size:14px;color:#64748b;line-height:1.7;">
        Alamat email akun Panic Button Anda telah berhasil diubah.
        Notifikasi ini dikirim ke email lama Anda sebagai konfirmasi keamanan.
        Login berikutnya, gunakan email baru Anda.
    </p>

    @include('emails.partials.detail-table', [
        'rows' => [
            ['Nama', $nama],
            ['Email Baru', $emailBaru],
            ['Wilayah', $namaWilayah],
            ['Waktu Perubahan', $waktu],
        ],
    ])

    @include('emails.partials.warning-box', [
        'warning' => 'Jika Anda tidak merasa melakukan perubahan ini, segera hubungi admin wilayah Anda.',
    ])

    <p style="margin:0;font-size:14px;color:#64748b;line-height:1.7;">
        Akun Anda tetap aman selama Anda menjaga kerahasiaan informasi login.
    </p>
@endsection
