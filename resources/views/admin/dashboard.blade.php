@extends('layouts.app')

@section('content')
    <div class="pd-ltr-20">
        <div class="card-box pd-20 height-100-p mb-30">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <img src="{{ asset(Auth::user()->jenis_kelamin === 'Laki-Laki' ? 'asset/banner-pria.png' : 'asset/banner-wanita.png') }}"
                        alt="" />
                </div>
                <div class="col-md-8">
                    <h4 class="font-20 weight-500 mb-10 text-capitalize">
                        Selamat datang kembali,
                        <div class="weight-600 font-30 text-blue">{{ Auth::user()->name }}!</div>
                    </h4>
                    <p class="font-18 max-width-600">
                        Semoga harimu menyenangkan. Pantau tugas dan laporan Anda melalui menu di sebelah kiri.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
