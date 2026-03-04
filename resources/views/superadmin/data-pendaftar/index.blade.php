@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <style>
            .badge-menunggu {
                background: #fef9c3;
                color: #a16207;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
            }

            .badge-disetujui {
                background: #dcfce7;
                color: #16a34a;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
            }

            .badge-ditolak {
                background: #fee2e2;
                color: #dc2626;
                padding: 4px 12px;
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

            .tab-btn {
                padding: 8px 20px;
                border-radius: 50px;
                border: none;
                cursor: pointer;
                font-size: 13px;
                font-weight: 600;
                background: rgba(0, 0, 0, 0.05);
                color: #64748b;
                transition: all .2s;
            }

            .tab-btn.active {
                background: #265ed7;
                color: #fff;
                box-shadow: 0 3px 10px rgba(38, 94, 215, .3);
            }

            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
                animation: fadeIn .3s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(6px)
                }

                to {
                    opacity: 1;
                    transform: translateY(0)
                }
            }
        </style>

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0">Data Pendaftar</h4>
                    <small class="text-muted">Kelola persetujuan pendaftaran pelanggan panic button</small>
                </div>
            </div>
        </div>

        {{-- Tab filter --}}
        <div class="card-box pd-20 mb-20">
            <div class="d-flex" style="gap:8px; flex-wrap:wrap;">
                <button class="tab-btn active" onclick="switchTab('menunggu', this)">
                    <i class="bi bi-clock mr-1"></i>Menunggu
                    <span
                        style="background:#fef9c3; color:#a16207; border-radius:50px; padding:1px 8px; margin-left:4px; font-size:11px;">
                        {{ $pendaftaran->where('status', 'Menunggu')->count() }}
                    </span>
                </button>
                <button class="tab-btn" onclick="switchTab('disetujui', this)">
                    <i class="bi bi-check-circle mr-1"></i>Disetujui
                </button>
                <button class="tab-btn" onclick="switchTab('ditolak', this)">
                    <i class="bi bi-x-circle mr-1"></i>Ditolak
                </button>
            </div>
        </div>

        {{-- ── TAB MENUNGGU ─────────────────────────────────────────── --}}
        <div class="tab-content active" id="tab-menunggu">
            <div class="card-box">
                <div class="pb-20 pl-20 pr-20 pt-20">
                    <table class="data-table table nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Wilayah</th>
                                <th>No. HP</th>
                                <th>Didaftarkan</th>
                                <th class="datatable-nosort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendaftaran->where('status','Menunggu') as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="weight-600">{{ $p->name }}</td>
                                    <td style="font-size:12px; font-family:monospace;">{{ $p->nik }}</td>
                                    <td><span class="badge-wilayah">{{ $p->wilayah->nama }}</span></td>
                                    <td>{{ $p->no_hp }}</td>
                                    <td style="font-size:12px;">{{ $p->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('superadmin.data-pendaftar.show', $p->id) }}"
                                                data-color="#265ed7" title="Lihat & Proses">
                                                <i class="icon-copy dw dw-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ada pendaftaran yang menunggu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── TAB DISETUJUI ────────────────────────────────────────── --}}
        <div class="tab-content" id="tab-disetujui">
            <div class="card-box">
                <div class="pb-20 pl-20 pr-20 pt-20">
                    <table class="data-table table nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Wilayah</th>
                                <th>Disetujui</th>
                                <th class="datatable-nosort">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendaftaran->where('status','Disetujui') as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="weight-600">{{ $p->name }}</td>
                                    <td style="font-size:12px; font-family:monospace;">{{ $p->nik }}</td>
                                    <td><span class="badge-wilayah">{{ $p->wilayah->nama }}</span></td>
                                    <td style="font-size:12px;">
                                        {{ $p->waktu_verifikasi ? $p->waktu_verifikasi->format('d M Y, H:i') : '-' }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('superadmin.data-pendaftar.show', $p->id) }}"
                                                data-color="#265ed7" title="Lihat Detail">
                                                <i class="icon-copy dw dw-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada pendaftaran yang disetujui.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── TAB DITOLAK ──────────────────────────────────────────── --}}
        <div class="tab-content" id="tab-ditolak">
            <div class="card-box">
                <div class="pb-20 pl-20 pr-20 pt-20">
                    <table class="data-table table nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Wilayah</th>
                                <th>Ditolak</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendaftaran->where('status','Ditolak') as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="weight-600">{{ $p->name }}</td>
                                    <td style="font-size:12px; font-family:monospace;">{{ $p->nik }}</td>
                                    <td><span class="badge-wilayah">{{ $p->wilayah->nama }}</span></td>
                                    <td style="font-size:12px;">
                                        {{ $p->waktu_verifikasi ? $p->waktu_verifikasi->format('d M Y') : '-' }}</td>
                                    <td style="font-size:12px; color:#dc2626;">{{ $p->catatan_penolakan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada pendaftaran yang ditolak.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            setTimeout(function() {
                const alert = document.getElementById('alert-success');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);

            function switchTab(name, btn) {
                document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.getElementById(`tab-${name}`).classList.add('active');
                btn.classList.add('active');
            }
        </script>
    </div>
@endsection
