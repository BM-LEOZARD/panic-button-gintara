@extends('emails.layout')

@section('content')
    <p style="margin:0 0 16px;font-size:16px;color:#334155;font-weight:600;">
        Halo, {{ $nama }}! 👋
    </p>

    <p style="margin:0 0 28px;font-size:14px;color:#64748b;line-height:1.7;">
        Nomor HP akun Panic Button Anda telah berhasil diperbarui.
        Mulai sekarang, semua notifikasi WhatsApp akan dikirimkan ke nomor baru Anda.
    </p>

    @include('emails.partials.detail-table', [
        'rows' => [
            ['Nama', $nama],
            ['No. HP Baru', $nomorBaru],
            ['Wilayah', $namaWilayah],
            ['Waktu Perubahan', $waktu],
        ],
    ])

    @include('emails.partials.warning-box', [
        'warning' => 'Jika Anda tidak merasa melakukan perubahan ini, segera hubungi admin wilayah Anda.',
    ])

    <p style="margin:0;font-size:14px;color:#64748b;line-height:1.7;">
        Akun Anda tetap aman selama Anda tidak membagikan informasi login kepada siapapun.
    </p>
@endsection
