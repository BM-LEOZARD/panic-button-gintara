@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Breadcrumb --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h4 class="page-title mb-0">Tambah Admin</h4>
                </div>
                <div class="col-auto ml-auto">
                    <a href="{{ route('superadmin.data-admin.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-x-circle-fill mr-1"></i> {{ $error }}</div>
                @endforeach
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="card-box pd-20">
            <h5 class="text-blue mb-20">Informasi Akun Admin</h5>

            <form method="POST" action="{{ route('superadmin.data-admin.store') }}" id="formTambahAdmin">
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="inputNama" class="form-control"
                                value="{{ old('name') }}" placeholder="contoh: Budi Santoso" required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                                placeholder="contoh: budi.santoso" required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                placeholder="contoh: budi@gintaranet.com" required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. HP <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}"
                                placeholder="contoh: 08123456789" required />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="Laki-Laki" {{ old('jenis_kelamin') === 'Laki-Laki' ? 'selected' : '' }}>
                                    Laki-Laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" class="form-control" value="Admin123" disabled
                                style="background:#f1f5f9; color:#64748b; font-style:italic;" />
                            <small class="text-muted">Password default admin.</small>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-10">
                    <a href="{{ route('superadmin.data-admin.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="button" class="btn btn-primary" id="btnSimpan">Simpan Admin</button>
                </div>
            </form>
        </div>

        <script>
            document.getElementById('btnSimpan').addEventListener('click', function() {
                const form = document.getElementById('formTambahAdmin');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const nama = document.getElementById('inputNama').value.trim();

                Swal.fire({
                    title: 'Simpan Admin Baru?',
                    html: `Akun admin baru untuk:<br>
                       <strong style="font-size:15px;">${nama}</strong><br><br>
                       <small style="color:#64748b;">Pastikan data yang diisi sudah benar sebelum disimpan.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#265ed7',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-floppy-fill mr-1"></i> Ya, Simpan',
                    cancelButtonText: 'Cek Lagi',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Sedang membuat akun admin baru.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        form.submit();
                    }
                });
            });
        </script>
    </div>
@endsection
