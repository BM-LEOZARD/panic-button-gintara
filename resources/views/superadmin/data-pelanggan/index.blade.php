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

            .badge-wilayah {
                background: #e7ebf5;
                color: #265ed7;
                padding: 3px 10px;
                border-radius: 50px;
                font-size: 11px;
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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- ── TABEL PELANGGAN AKTIF ─────────────────────────────────── --}}
        <div class="card-box mb-30">
            <div class="pd-20 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-blue h4 mb-0">Data Pelanggan Aktif</h4>
                    <small class="text-muted">Pelanggan terdaftar yang memiliki akun panic button aktif.</small>
                </div>
            </div>
            <div class="pb-20 pl-20 pr-20">
                <table class="data-table table nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Wilayah</th>
                            <th>No. HP</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                            <th class="datatable-nosort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggan as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center" style="gap:10px;">
                                        <span class="avatar-circle">
                                            {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                        </span>
                                        <span class="weight-600">{{ $p->user->name }}</span>
                                    </div>
                                </td>
                                <td style="font-family:monospace; font-size:12px;">{{ $p->nik }}</td>
                                <td>
                                    @if ($p->panicButton?->wilayah)
                                        <span class="badge-wilayah">{{ $p->panicButton->wilayah->nama }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $p->user->no_hp }}</td>
                                <td><span class="badge-gender">{{ $p->user->jenis_kelamin }}</span></td>
                                <td><span class="badge-aktif">Aktif</span></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('superadmin.data-pelanggan.show', $p->id) }}" data-color="#265ed7"
                                            title="Lihat Detail">
                                            <i class="icon-copy dw dw-eye"></i>
                                        </a>
                                        <a href="#" data-color="#f59e0b"
                                            onclick="confirmNonaktif({{ $p->id }}, '{{ addslashes($p->user->name) }}')"
                                            title="Nonaktifkan">
                                            <i class="icon-copy dw dw-ban"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada data pelanggan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── TABEL PELANGGAN NONAKTIF ──────────────────────────────── --}}
        @if ($pelangganNonaktif->count() > 0)
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4 mb-0">Pelanggan Nonaktif</h4>
                    <small class="text-muted">Akun dinonaktifkan. Dapat diaktifkan kembali atau dihapus permanen.</small>
                </div>
                <div class="pb-20 pl-20 pr-20">
                    <table class="data-table table nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Wilayah</th>
                                <th>No. HP</th>
                                <th>Dinonaktifkan</th>
                                <th>Status</th>
                                <th class="datatable-nosort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelangganNonaktif as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center" style="gap:10px;">
                                            <span class="avatar-circle" style="background:#94a3b8;">
                                                {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                            </span>
                                            <span class="weight-600 text-muted">{{ $p->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted" style="font-family:monospace; font-size:12px;">
                                        {{ $p->nik }}
                                    </td>
                                    <td>
                                        @if ($p->panicButton?->wilayah)
                                            <span class="badge-wilayah">{{ $p->panicButton->wilayah->nama }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $p->user->no_hp }}</td>
                                    <td class="text-muted" style="font-size:12px;">
                                        {{ $p->user->deleted_at->format('d M Y, H:i') }}
                                    </td>
                                    <td><span class="badge-nonaktif">Nonaktif</span></td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('superadmin.data-pelanggan.show', $p->id) }}"
                                                data-color="#265ed7" title="Lihat Detail">
                                                <i class="icon-copy dw dw-eye"></i>
                                            </a>
                                            <a href="#" data-color="#16a34a"
                                                onclick="confirmRestore({{ $p->id }}, '{{ addslashes($p->user->name) }}')"
                                                title="Aktifkan kembali">
                                                <i class="icon-copy dw dw-checked"></i>
                                            </a>
                                            <a href="#" data-color="#e95959"
                                                onclick="confirmHapus({{ $p->id }}, '{{ addslashes($p->user->name) }}')"
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
                    title: 'Nonaktifkan Pelanggan?',
                    html: `Akun pelanggan <strong>${nama}</strong> akan dinonaktifkan.<br>
                       <small style="color:#64748b;">Pelanggan tidak dapat login dan panic button akan dinonaktifkan. Data tetap tersimpan dan dapat diaktifkan kembali kapan saja.</small>`,
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
                            text: 'Sedang menonaktifkan akun pelanggan.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        const form = document.getElementById('formNonaktif');
                        form.action = `/superadmin/data-pelanggan/${id}`;
                        form.submit();
                    }
                });
            }

            function confirmRestore(id, nama) {
                Swal.fire({
                    title: 'Aktifkan Kembali?',
                    html: `Akun pelanggan <strong>${nama}</strong> akan diaktifkan kembali.<br>
                       <small style="color:#64748b;">Pelanggan dapat login kembali dan panic button akan aktif kembali.</small>`,
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
                            text: 'Sedang mengaktifkan kembali akun pelanggan.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        const form = document.getElementById('formRestore');
                        form.action = `/superadmin/data-pelanggan/${id}/restore`;
                        form.submit();
                    }
                });
            }

            function confirmHapus(id, nama) {
                Swal.fire({
                    title: 'Hapus Permanen?',
                    html: `Akun pelanggan <strong>${nama}</strong> akan dihapus secara permanen.<br><br>
                       <span style="color:#dc2626; font-weight:600;"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Semua data termasuk panic button dan foto KTP akan dihapus dan tidak dapat dikembalikan.</span>`,
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
                            text: 'Sedang menghapus data pelanggan secara permanen.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        const form = document.getElementById('formHapus');
                        form.action = `/superadmin/data-pelanggan/${id}/force-delete`;
                        form.submit();
                    }
                });
            }
        </script>
    </div>
@endsection
