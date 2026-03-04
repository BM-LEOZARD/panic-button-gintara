@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Breadcrumb --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h4 class="page-title mb-0">Edit Admin</h4>
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
            <h5 class="text-blue mb-20">Edit Informasi Admin</h5>

            <form method="POST" action="{{ route('superadmin.data-admin.update', $admin->id) }}" id="formEditAdmin">
                @csrf
                @method('PUT')
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ $admin->name }}" disabled
                                style="background:#f1f5f9; color:#64748b;" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" value="{{ $admin->username }}" disabled
                                style="background:#f1f5f9; color:#64748b;" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="{{ $admin->email }}" disabled
                                style="background:#f1f5f9; color:#64748b;" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <input type="text" class="form-control" value="{{ $admin->jenis_kelamin }}" disabled
                                style="background:#f1f5f9; color:#64748b;" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. HP <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp" id="inputNoHp" class="form-control"
                                value="{{ old('no_hp', $admin->no_hp) }}" required placeholder="contoh: 08123456789" />
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-10">
                    <a href="{{ route('superadmin.data-admin.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <button type="button" class="btn btn-primary" id="btnUpdate">Perbarui Admin</button>
                </div>
            </form>
        </div>

        <script>
            document.getElementById('btnUpdate').addEventListener('click', function() {
                const form = document.getElementById('formEditAdmin');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const noHp = document.getElementById('inputNoHp').value.trim();

                Swal.fire({
                    title: 'Perbarui No. HP Admin?',
                    html: `No. HP akan diperbarui menjadi:<br>
               <strong style="font-size:15px;">${noHp}</strong>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#265ed7',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-floppy-fill mr-1"></i> Ya, Perbarui',
                    cancelButtonText: 'Cek Lagi',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memperbarui...',
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
