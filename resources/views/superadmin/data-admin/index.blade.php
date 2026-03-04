@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            .badge-aktif {
                background: #dcfce7;
                color: #16a34a;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
            }

            .badge-nonaktif {
                background: #fee2e2;
                color: #dc2626;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
            }

            .badge-gender {
                background: #e7ebf5;
                color: #265ed7;
                padding: 4px 10px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
            }

            .avatar-circle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: #265ed7;
                color: #fff;
                font-weight: 700;
                font-size: 15px;
                flex-shrink: 0;
            }
        </style>

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- ── TABEL ADMIN AKTIF ──────────────────────────────────────── --}}
        <div class="card-box mb-30">
            <div class="pd-20 d-flex justify-content-between align-items-center">
                <h4 class="text-blue h4 mb-0">Data Admin Aktif</h4>
                <a href="{{ route('superadmin.data-admin.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Admin
                </a>
            </div>
            <div class="pb-20 pl-20 pr-20">
                <table class="data-table table nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                            <th class="datatable-nosort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $i => $admin)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center" style="gap:10px;">
                                        <span class="avatar-circle">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </span>
                                        <span class="weight-600">{{ $admin->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $admin->username }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->no_hp }}</td>
                                <td><span class="badge-gender">{{ $admin->jenis_kelamin }}</span></td>
                                <td><span class="badge-aktif">Aktif</span></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('superadmin.data-admin.edit', $admin->id) }}" data-color="#265ed7"
                                            title="Edit">
                                            <i class="icon-copy dw dw-edit2"></i>
                                        </a>
                                        <a href="#" data-color="#f59e0b"
                                            onclick="confirmNonaktif({{ $admin->id }}, '{{ addslashes($admin->name) }}')"
                                            title="Nonaktifkan">
                                            <i class="icon-copy dw dw-ban"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── TABEL ADMIN NONAKTIF ───────────────────────────────────── --}}
        @if ($adminsNonaktif->count() > 0)
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4 mb-0">Admin Nonaktif</h4>
                    <small class="text-muted">Admin yang telah dinonaktifkan. Dapat diaktifkan kembali atau dihapus
                        permanen.</small>
                </div>
                <div class="pb-20 pl-20 pr-20">
                    <table class="data-table table nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Dinonaktifkan</th>
                                <th>Status</th>
                                <th class="datatable-nosort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adminsNonaktif as $i => $admin)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center" style="gap:10px;">
                                            <span class="avatar-circle" style="background:#94a3b8;">
                                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                                            </span>
                                            <span class="weight-600 text-muted">{{ $admin->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $admin->username }}</td>
                                    <td class="text-muted">{{ $admin->email }}</td>
                                    <td class="text-muted">{{ $admin->no_hp }}</td>
                                    <td class="text-muted" style="font-size:12px;">
                                        {{ $admin->deleted_at->format('d M Y, H:i') }}
                                    </td>
                                    <td><span class="badge-nonaktif">Nonaktif</span></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="#" data-color="#16a34a"
                                                onclick="confirmRestore({{ $admin->id }}, '{{ addslashes($admin->name) }}')"
                                                title="Aktifkan kembali">
                                                <i class="icon-copy dw dw-checked"></i>
                                            </a>
                                            <a href="#" data-color="#e95959"
                                                onclick="confirmHapus({{ $admin->id }}, '{{ addslashes($admin->name) }}')"
                                                title="Hapus permanen">
                                                <i class="icon-copy dw dw-delete-3"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Hidden forms untuk submit --}}
        <form method="POST" id="formNonaktif" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        <form method="POST" id="formRestore" style="display:none;">
            @csrf
        </form>

        <form method="POST" id="formHapus" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        <script>
            setTimeout(function() {
                const alert = document.getElementById('alert-success');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);

            function confirmNonaktif(id, nama) {
                Swal.fire({
                    title: 'Nonaktifkan Admin?',
                    html: `Akun admin <strong>${nama}</strong> akan dinonaktifkan.<br>
                       <small style="color:#64748b;">Admin tidak dapat login, namun data tetap tersimpan dan dapat diaktifkan kembali kapan saja.</small>`,
                    icon: 'warning',
                    iconColor: '#f59e0b',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-slash-circle mr-1"></i> Ya, Nonaktifkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang menonaktifkan akun admin.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        const form = document.getElementById('formNonaktif');
                        form.action = `/superadmin/data-admin/${id}`;
                        form.submit();
                    }
                });
            }

            function confirmRestore(id, nama) {
                Swal.fire({
                    title: 'Aktifkan Kembali?',
                    html: `Akun admin <strong>${nama}</strong> akan diaktifkan kembali.<br>
                       <small style="color:#64748b;">Admin akan dapat login ke sistem kembali.</small>`,
                    icon: 'question',
                    iconColor: '#16a34a',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-check-circle mr-1"></i> Ya, Aktifkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang mengaktifkan kembali akun admin.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        const form = document.getElementById('formRestore');
                        form.action = `/superadmin/data-admin/${id}/restore`;
                        form.submit();
                    }
                });
            }

            function confirmHapus(id, nama) {
                Swal.fire({
                    title: 'Hapus Permanen?',
                    html: `Akun admin <strong>${nama}</strong> akan dihapus secara permanen.<br><br>
                       <span style="color:#dc2626; font-weight:600;"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Tindakan ini tidak dapat dibatalkan dan data tidak dapat dikembalikan.</span>`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-trash3-fill mr-1"></i> Ya, Hapus Permanen',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                    input: 'text',
                    inputPlaceholder: `Ketik "${nama}" untuk konfirmasi`,
                    inputAttributes: {
                        autocomplete: 'off'
                    },
                    inputValidator: (value) => {
                        if (!value) return 'Kolom konfirmasi tidak boleh kosong.';
                        if (value !== nama) return `Nama tidak cocok. Ketik persis: ${nama}`;
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Sedang menghapus data admin secara permanen.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        const form = document.getElementById('formHapus');
                        form.action = `/superadmin/data-admin/${id}/force-delete`;
                        form.submit();
                    }
                });
            }
        </script>
    </div>
@endsection
