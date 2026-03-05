@extends('emails.layout')

@section('content')
    <p style="margin:0 0 16px;font-size:16px;color:#334155;font-weight:600;">
        Halo, {{ $nama }}! 👋
    </p>

    <p style="margin:0 0 28px;font-size:14px;color:#64748b;line-height:1.7;">
        Password akun Panic Button Anda telah berhasil diubah.
        Gunakan password baru Anda untuk login ke sistem.
    </p>

    @include('emails.partials.detail-table', [
        'rows' => [['Nama', $nama], ['Wilayah', $namaWilayah], ['Waktu Perubahan', $waktu]],
    ])

    @include('emails.partials.warning-box', [
        'warning' =>
            'Jika Anda tidak merasa mengubah password, segera hubungi admin wilayah Anda dan amankan akun Anda.',
    ])

    @include('emails.partials.login-button')

    <p style="margin:0;font-size:14px;color:#64748b;line-height:1.7;">
        Tips keamanan: Jangan bagikan password Anda kepada siapapun, termasuk kepada admin.
    </p>
@endsection
