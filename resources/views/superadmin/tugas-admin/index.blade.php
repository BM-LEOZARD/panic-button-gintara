@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            .admin-card {
                background: #fff;
                border-radius: 14px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
                border: 1px solid #e2e8f0;
                overflow: hidden;
                height: 100%;
                transition: box-shadow 0.2s;
                display: flex;
                flex-direction: column;
            }

            .admin-card:hover {
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            }

            .card-head {
                background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
                padding: 16px 18px;
            }

            .card-head-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                margin-bottom: 10px;
            }

            .avatar-lg {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.2);
                border: 2px solid rgba(255, 255, 255, 0.4);
                color: #fff;
                font-size: 18px;
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .btn-add-tugas {
                background: rgba(255, 255, 255, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.35);
                color: #fff;
                border-radius: 8px;
                padding: 5px 11px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.2s;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .btn-add-tugas:hover {
                background: rgba(255, 255, 255, 0.28);
                color: #fff;
            }

            .card-head-info {
                width: 100%;
            }

            .card-head-info h6 {
                color: #fff;
                font-size: 14px;
                font-weight: 700;
                margin-bottom: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .card-head-info small {
                color: rgba(255, 255, 255, 0.65);
                font-size: 12px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }

            .card-body-custom {
                padding: 16px 18px;
                flex: 1;
            }

            .section-label {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                color: #94a3b8;
                margin-bottom: 10px;
            }

            .wilayah-list {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .wilayah-item {
                display: flex;
                align-items: center;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                padding: 8px 12px;
                gap: 8px;
            }

            .wilayah-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #22c55e;
                flex-shrink: 0;
            }

            .wilayah-nama {
                font-size: 13px;
                font-weight: 600;
                color: #1e293b;
                flex: 1;
                min-width: 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .wilayah-kode {
                font-size: 11px;
                font-weight: 600;
                color: #265ed7;
                background: #e7ebf5;
                border-radius: 50px;
                padding: 2px 8px;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .btn-hapus-tugas {
                background: none;
                border: none;
                color: #cbd5e1;
                cursor: pointer;
                font-size: 18px;
                padding: 0 0 0 4px;
                line-height: 1;
                flex-shrink: 0;
                transition: color 0.2s;
            }

            .btn-hapus-tugas:hover {
                color: #ef4444;
            }

            .empty-state {
                text-align: center;
                padding: 20px 10px;
                color: #94a3b8;
                font-size: 13px;
            }

            .empty-state i {
                font-size: 26px;
                display: block;
                margin-bottom: 6px;
            }

            .swal2-select-custom {
                width: 100%;
                padding: 10px 12px;
                border: 1.5px solid #e2e8f0;
                border-radius: 8px;
                font-size: 14px;
                color: #1e293b;
                background: #f8fafc;
                margin-top: 10px;
                outline: none;
                cursor: pointer;
            }

            .swal2-select-custom:focus {
                border-color: #2563eb;
                background: #fff;
            }
        </style>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="alert-error">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0">Tugas Admin</h4>
                    <small class="text-muted">Kelola penugasan wilayah untuk setiap admin</small>
                </div>
            </div>
        </div>

        @if ($admins->isEmpty())
            <div class="card-box pd-20 text-center text-muted">
                <i class="bi bi-people" style="font-size:40px; display:block; margin-bottom:10px;"></i>
                Belum ada admin.
                <a href="{{ route('superadmin.data-admin.create') }}">Tambah admin terlebih dahulu.</a>
            </div>
        @else
            <div class="row">
                @foreach ($admins as $admin)
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-20">
                        <div class="admin-card">

                            <div class="card-head">
                                <div class="card-head-top">
                                    <div class="avatar-lg">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <button class="btn-add-tugas"
                                        onclick="openTambahTugas(
                                        {{ $admin->id }},
                                        '{{ addslashes($admin->name) }}',
                                        [{{ $admin->tugasAdmin->pluck('wilayah_cover_id')->join(',') }}]
                                    )">
                                        <i class="bi bi-plus-lg"></i> Tugas
                                    </button>
                                </div>
                                <div class="card-head-info">
                                    <h6 title="{{ $admin->name }}">{{ $admin->name }}</h6>
                                    <small title="{{ $admin->email }}">{{ $admin->email }}</small>
                                </div>
                            </div>

                            <div class="card-body-custom">
                                <div class="section-label">Wilayah yang Dipantau</div>

                                <div class="wilayah-list">
                                    @forelse ($admin->tugasAdmin as $tugas)
                                        <div class="wilayah-item">
                                            <span class="wilayah-dot"></span>
                                            <span class="wilayah-nama"
                                                title="{{ $tugas->wilayah->nama }}">{{ $tugas->wilayah->nama }}</span>
                                            <span class="wilayah-kode">{{ $tugas->wilayah->kode_wilayah }}</span>
                                            <button class="btn-hapus-tugas"
                                                onclick="confirmHapusTugas(
                                                {{ $tugas->id }},
                                                '{{ addslashes($tugas->wilayah->nama) }}',
                                                '{{ addslashes($admin->name) }}'
                                            )"
                                                title="Hapus tugas">
                                                &times;
                                            </button>
                                        </div>
                                    @empty
                                        <div class="empty-state">
                                            <i class="bi bi-map"></i>
                                            Belum ada wilayah yang ditugaskan
                                        </div>
                                    @endforelse
                                </div>

                                @if ($admin->tugasAdmin->count() > 0)
                                    <div style="margin-top:12px; font-size:12px; color:#64748b; text-align:right;">
                                        {{ $admin->tugasAdmin->count() }} wilayah ditugaskan
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Hidden forms --}}
        <form method="POST" id="formTambahTugas" action="{{ route('superadmin.tugas-admin.store') }}"
            style="display:none;">
            @csrf
            <input type="hidden" name="user_id" id="form_user_id" />
            <input type="hidden" name="wilayah_cover_id" id="form_wilayah_id" />
        </form>

        <form method="POST" id="formHapusTugas" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        <script>
            setTimeout(function() {
                ['alert-success', 'alert-error'].forEach(function(id) {
                    const el = document.getElementById(id);
                    if (el) {
                        el.classList.remove('show');
                        el.classList.add('fade');
                        setTimeout(() => el.remove(), 300);
                    }
                });
            }, 5000);

            const WILAYAH_OPTIONS = @json($wilayahJson);

            function openTambahTugas(adminId, adminName, tugasIds) {
                const available = WILAYAH_OPTIONS.filter(w => !tugasIds.includes(w.id));

                if (available.length === 0) {
                    Swal.fire({
                        title: 'Semua Wilayah Sudah Ditugaskan',
                        html: `Semua wilayah yang tersedia sudah ditugaskan kepada<br>
                           <strong>${adminName}</strong>.`,
                        icon: 'info',
                        iconColor: '#2563eb',
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Oke',
                    });
                    return;
                }

                const options = available.map(w =>
                    `<option value="${w.id}">${w.nama} — ${w.kode}</option>`
                ).join('');

                Swal.fire({
                    title: 'Tambah Tugas Wilayah',
                    html: `
                    <p style="color:#64748b; font-size:13px; margin-bottom:4px;">
                        Pilih wilayah untuk ditugaskan kepada:<br>
                        <strong style="color:#1e293b; font-size:14px;">${adminName}</strong>
                    </p>
                    <select id="swal-wilayah" class="swal2-select-custom">
                        <option value="" disabled selected>-- Pilih Wilayah --</option>
                        ${options}
                    </select>
                    <small style="color:#94a3b8; font-size:11px; display:block; margin-top:6px;">
                        Menampilkan ${available.length} wilayah yang belum ditugaskan.
                    </small>
                `,
                    icon: 'info',
                    iconColor: '#2563eb',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-check-circle mr-1"></i> Tambah Tugas',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                    preConfirm: () => {
                        const wilayahId = document.getElementById('swal-wilayah').value;
                        if (!wilayahId) {
                            Swal.showValidationMessage('Pilih wilayah terlebih dahulu.');
                            return false;
                        }
                        return wilayahId;
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Sedang menambahkan tugas wilayah.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        document.getElementById('form_user_id').value = adminId;
                        document.getElementById('form_wilayah_id').value = result.value;
                        document.getElementById('formTambahTugas').submit();
                    }
                });
            }

            function confirmHapusTugas(tugasId, wilayahNama, adminNama) {
                Swal.fire({
                    title: 'Hapus Tugas Wilayah?',
                    html: `Wilayah <strong>${wilayahNama}</strong> akan dicopot dari admin<br>
                       <strong>${adminNama}</strong>.<br><br>
                       <small style="color:#64748b;">Admin tidak akan lagi memantau wilayah ini.</small>`,
                    icon: 'warning',
                    iconColor: '#ef4444',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-trash3-fill mr-1"></i> Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Sedang menghapus tugas wilayah.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        const form = document.getElementById('formHapusTugas');
                        form.action = `/superadmin/tugas-admin/${tugasId}`;
                        form.submit();
                    }
                });
            }
        </script>
    </div>
@endsection
