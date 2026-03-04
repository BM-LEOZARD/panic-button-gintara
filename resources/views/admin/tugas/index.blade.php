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
                display: inline-block;
            }

            .badge-diproses {
                background: #dbeafe;
                color: #1d4ed8;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
                display: inline-block;
            }

            .badge-selesai {
                background: #dcfce7;
                color: #16a34a;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
                display: inline-block;
            }

            .badge-wilayah {
                background: #e7ebf5;
                color: #265ed7;
                padding: 3px 10px;
                border-radius: 50px;
                font-size: 11px;
                font-weight: 600;
                display: inline-block;
                white-space: nowrap;
            }

            .alarm-card-container,
            .tugas-card-container {
                height: 500px;
                display: flex;
                flex-direction: column;
                margin-bottom: 30px;
            }

            .card-box.alarm-card-box,
            .card-box.tugas-card-box {
                height: 100%;
                display: flex;
                flex-direction: column;
                margin-bottom: 0;
            }

            .scrollable-content {
                flex: 1;
                overflow-y: auto;
                padding-right: 8px;
                margin-top: 15px;
                min-height: 0;
            }

            .scrollable-content::-webkit-scrollbar {
                width: 6px;
            }

            .scrollable-content::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .scrollable-content::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }

            .scrollable-content::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            .alarm-card {
                border: 2px solid #fecaca;
                border-radius: 14px;
                background: #fff;
                padding: 15px;
                margin-bottom: 12px;
                position: relative;
                transition: box-shadow .2s;
            }

            .alarm-card:last-child {
                margin-bottom: 0;
            }

            .alarm-card:hover {
                box-shadow: 0 4px 20px rgba(220, 38, 38, .12);
            }

            .alarm-card .pulse-dot {
                display: inline-block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: #dc2626;
                margin-right: 6px;
                animation: pulse 1.4s infinite;
                flex-shrink: 0;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                    transform: scale(1);
                }

                50% {
                    opacity: .5;
                    transform: scale(1.4);
                }
            }

            .tugas-saya-card {
                border: 2px solid #bfdbfe;
                border-radius: 14px;
                background: #eff6ff;
                padding: 15px;
                margin-bottom: 12px;
            }

            .tugas-saya-card:last-child {
                margin-bottom: 0;
            }

            .nama-pelanggan {
                font-size: 15px;
                font-weight: 700;
                color: #1e293b;
                display: flex;
                align-items: center;
                gap: 4px;
                flex-wrap: wrap;
            }

            .meta {
                font-size: 12px;
                color: #64748b;
                margin-top: 4px;
                word-break: break-word;
            }

            .empty-state {
                text-align: center;
                padding: 60px 15px;
                color: #94a3b8;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .empty-state .icon {
                font-size: 48px;
                margin-bottom: 12px;
            }

            .section-title {
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                color: #64748b;
                margin-bottom: 0;
                padding-bottom: 8px;
                border-bottom: 2px solid #f1f5f9;
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
                flex-shrink: 0;
            }

            .counter-badge {
                background: #fee2e2;
                color: #dc2626;
                border-radius: 50px;
                padding: 2px 10px;
                font-size: 12px;
                font-weight: 600;
                display: inline-block;
            }

            .counter-badge.primary {
                background: #dbeafe;
                color: #1d4ed8;
            }

            .countdown {
                font-family: monospace;
                font-size: 12px;
                font-weight: 700;
                color: #dc2626;
            }

            .action-container {
                flex-shrink: 0;
            }

            .row-responsive {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }

            .col-responsive {
                flex: 1 1 50%;
                padding: 0 10px;
                min-width: 280px;
            }

            @media (min-width: 1200px) {

                .alarm-card-container,
                .tugas-card-container {
                    height: 550px;
                }
            }

            @media (max-width: 991px) {
                .col-responsive {
                    flex: 1 1 100%;
                    margin-bottom: 20px;
                }

                .col-responsive:last-child {
                    margin-bottom: 0;
                }
            }

            @media (max-width: 768px) {

                .alarm-card-container,
                .tugas-card-container {
                    height: 400px;
                }

                .alarm-card,
                .tugas-saya-card {
                    padding: 12px;
                }

                .nama-pelanggan {
                    font-size: 14px;
                }

                .btn-sm {
                    padding: 4px 8px;
                    font-size: 11px;
                }

                .badge-wilayah {
                    font-size: 10px;
                    padding: 2px 8px;
                }

                .empty-state {
                    padding: 30px 10px;
                }

                .empty-state .icon {
                    font-size: 36px;
                }
            }

            @media (max-width: 480px) {

                .alarm-card-container,
                .tugas-card-container {
                    height: 350px;
                }
            }

            .card-box {
                margin-bottom: 0;
            }

            .alarm-card.disabled-card {
                opacity: 0.6;
                background-color: #f8fafc;
                border-color: #cbd5e1;
            }

            .alarm-card.disabled-card:hover {
                box-shadow: none;
            }

            .btn:disabled {
                cursor: not-allowed;
                opacity: 0.5;
                background-color: #94a3b8 !important;
                border-color: #94a3b8 !important;
            }

            .disabled-message {
                font-size: 10px;
                color: #ef4444;
                margin-top: 4px;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .waiting-count-badge {
                background: #dc2626;
                color: white;
                border-radius: 20px;
                padding: 2px 10px;
                font-size: 11px;
                font-weight: 600;
                margin-left: 8px;
            }

            .waiting-info {
                background: #fee2e2;
                border-radius: 8px;
                padding: 8px 12px;
                margin-bottom: 12px;
                font-size: 12px;
                color: #991b1b;
                display: flex;
                align-items: center;
                gap: 8px;
                border-left: 3px solid #dc2626;
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

        {{-- Header --}}
        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0">Tugas Saya</h4>
                    <small class="text-muted d-block d-sm-inline">
                        <i class="icon-copy bi bi-geo-alt-fill mr-1"></i>Wilayah tanggung jawab:
                        <div class="d-inline-block mt-1 mt-sm-0">
                            @forelse ($wilayahIds as $wid)
                                @php
                                    $w = \App\Models\TugasAdmin::with('wilayah')
                                        ->where('user_id', Auth::id())
                                        ->where('wilayah_cover_id', $wid)
                                        ->first()?->wilayah;
                                @endphp
                                @if ($w)
                                    <span class="badge-wilayah mb-1">{{ $w->nama }}</span>
                                @endif
                            @empty
                                <span class="text-danger">Belum ada wilayah yang ditugaskan.</span>
                            @endforelse
                        </div>
                    </small>
                </div>
            </div>
        </div>

        @php
            $sedangMemprosesTugas = $tugasSaya->count() > 0;
            $totalMenunggu = $tugasMenunggu->count();
        @endphp

        <div class="row-responsive">
            {{-- KOLOM KIRI: Alarm Menunggu --}}
            <div class="col-responsive">
                <div class="alarm-card-container">
                    <div class="card-box alarm-card-box pd-20">
                        <div class="section-title">
                            <i class="icon-copy bi bi-exclamation-triangle-fill text-danger mr-2"></i>Alarm Menunggu
                            @if ($totalMenunggu > 0)
                                <span class="counter-badge">{{ $totalMenunggu }}</span>
                            @endif
                        </div>

                        @if ($totalMenunggu > 1)
                            <div class="waiting-info">
                                <i class="icon-copy bi bi-info-circle-fill"></i>
                                <span>Terdapat <strong>{{ $totalMenunggu }}</strong> alarm menunggu. Gunakan scroll untuk
                                    melihat semua.</span>
                            </div>
                        @endif

                        <div class="scrollable-content">
                            @forelse ($tugasMenunggu as $alarm)
                                <div class="alarm-card {{ $sedangMemprosesTugas ? 'disabled-card' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <div class="nama-pelanggan">
                                                @if (!$sedangMemprosesTugas)
                                                    <span class="pulse-dot"></span>
                                                @endif
                                                <span class="text-truncate"
                                                    style="max-width: 200px;">{{ $alarm->pelanggan->user->name }}</span>
                                                @if ($loop->first && $totalMenunggu > 0)
                                                    <span class="waiting-count-badge">Paling Tua</span>
                                                @endif
                                            </div>
                                            <div class="meta">
                                                <i class="icon-copy bi bi-pin-map-fill mr-1 flex-shrink-0"></i>
                                                <span class="text-truncate d-inline-block align-middle"
                                                    style="max-width: 150px;">
                                                    {{ $alarm->panicButton->wilayah->nama }}
                                                </span>
                                                <span class="badge-wilayah ml-1 align-middle">
                                                    {{ $alarm->panicButton->wilayah->kode_wilayah }}
                                                </span>
                                            </div>
                                            <div class="meta">
                                                <i class="icon-copy bi bi-telephone-fill mr-1"></i>
                                                {{ $alarm->pelanggan->user->no_hp }}
                                                <i class="icon-copy bi bi-house-door-fill ml-2 mr-1"></i>
                                                Blok
                                                {{ $alarm->panicButton->GetBlockID }}/{{ $alarm->panicButton->GetNumber }}
                                            </div>
                                            @if ($alarm->lokasi)
                                                <div class="meta">
                                                    <i class="icon-copy bi bi-geo-alt-fill mr-1"></i>
                                                    <span style="font-family:monospace; font-size:11px;">
                                                        {{ $alarm->lokasi->latitude }}, {{ $alarm->lokasi->longtitude }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="meta mt-1">
                                                <i class="icon-copy bi bi-clock-history mr-1"></i>Trigger:
                                                <strong>
                                                    {{ $alarm->panicButton->timestamp
                                                        ? \Carbon\Carbon::createFromTimestamp($alarm->panicButton->timestamp, 'Asia/Jakarta')->format('d M Y, H:i:s') .
                                                            ' WIB'
                                                        : '-' }}
                                                </strong>
                                            </div>
                                            @if (!$sedangMemprosesTugas)
                                                <div class="meta mt-1">
                                                    <span class="countdown"
                                                        data-trigger="{{ $alarm->panicButton->timestamp ?? 0 }}"></span>
                                                </div>
                                            @endif
                                            @if ($totalMenunggu > 1)
                                                <div class="meta mt-1">
                                                    <span class="badge badge-secondary">Antrian
                                                        #{{ $loop->iteration }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="action-container ml-2 ml-sm-3">
                                            <form method="POST" action="{{ route('admin.tugas.ambil', $alarm->id) }}"
                                                class="ambil-tugas-form">
                                                @csrf
                                                <button type="button" class="btn btn-danger btn-sm btn-ambil-tugas"
                                                    data-nama="{{ $alarm->pelanggan->user->name }}"
                                                    @if ($sedangMemprosesTugas) disabled @endif>
                                                    <i class="icon-copy bi bi-rocket-takeoff-fill mr-1"></i>
                                                    @if ($sedangMemprosesTugas)
                                                        Sedang Menangani Tugas
                                                    @else
                                                        Ambil Tugas
                                                    @endif
                                                </button>
                                            </form>
                                            @if ($sedangMemprosesTugas)
                                                <div class="disabled-message">
                                                    <i class="icon-copy bi bi-info-circle-fill"></i>
                                                    Selesaikan tugas sebelumnya
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="icon"><i class="icon-copy bi bi-check2-circle text-success"></i></div>
                                    <div style="font-weight:600; color:#16a34a;">Tidak ada alarm aktif</div>
                                    <div style="font-size:13px;">Semua aman di wilayah Anda.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Sedang Ditangani --}}
            <div class="col-responsive">
                <div class="tugas-card-container">
                    <div class="card-box tugas-card-box pd-20">
                        <div class="section-title">
                            <i class="icon-copy bi bi-gear-wide-connected text-primary mr-2"></i>Sedang Ditangani
                            @if ($tugasSaya->count() > 0)
                                <span class="counter-badge primary">{{ $tugasSaya->count() }}</span>
                            @endif
                        </div>

                        <div class="scrollable-content">
                            @forelse ($tugasSaya as $alarm)
                                <div class="tugas-saya-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <div class="nama-pelanggan" style="color:#1d4ed8;">
                                                <span class="text-truncate"
                                                    style="max-width: 200px;">{{ $alarm->pelanggan->user->name }}</span>
                                            </div>
                                            <div class="meta">
                                                <i class="icon-copy bi bi-pin-map-fill mr-1"></i>
                                                <span class="text-truncate d-inline-block align-middle"
                                                    style="max-width: 150px;">
                                                    {{ $alarm->panicButton->wilayah->nama }}
                                                </span>
                                                <span
                                                    class="badge-wilayah ml-1">{{ $alarm->panicButton->wilayah->kode_wilayah }}</span>
                                            </div>
                                            <div class="meta">
                                                <i
                                                    class="icon-copy bi bi-telephone-fill mr-1"></i>{{ $alarm->pelanggan->user->no_hp }}
                                                <i class="icon-copy bi bi-house-door-fill ml-2 mr-1"></i>Blok
                                                {{ $alarm->panicButton->GetBlockID }}/{{ $alarm->panicButton->GetNumber }}
                                            </div>
                                            <div class="meta">
                                                <i class="icon-copy bi bi-stopwatch-fill mr-1"></i>Diambil sejak:
                                                <strong>{{ \Carbon\Carbon::parse($alarm->updated_at, 'Asia/Jakarta')->format('H:i:s') }}
                                                    WIB</strong>
                                            </div>
                                            <div class="meta">
                                                <i class="icon-copy bi bi-clock-history mr-1"></i>Durasi:
                                                <span class="tugas-durasi"
                                                    data-trigger="{{ $alarm->panicButton->timestamp ?? 0 }}"
                                                    data-updated="{{ $alarm->updated_at->timestamp }}">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="action-container ml-2 ml-sm-3">
                                            <a href="{{ route('admin.tugas.show', $alarm->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="icon-copy bi bi-check2-circle mr-1"></i>Selesaikan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="icon"><i class="icon-copy bi bi-clipboard2"></i></div>
                                    <div style="font-weight:600;">Belum ada tugas yang diambil</div>
                                    <div style="font-size:13px;">Ambil tugas dari kolom kiri jika ada alarm masuk.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($riwayat->count() > 0)
            <div class="card-box pd-20 mt-30" style="margin-top: 30px;">
                <div class="section-title"><i class="icon-copy bi bi-archive-fill mr-2"></i>Riwayat Terakhir (10 Terakhir)
                </div>
                <div class="table-responsive">
                    <table class="data-table table nowrap">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Wilayah</th>
                                <th>Waktu Trigger</th>
                                <th>Waktu Selesai</th>
                                <th>Durasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayat as $r)
                                <tr>
                                    <td class="weight-600">{{ $r->pelanggan->user->name }}</td>
                                    <td>
                                        <span class="badge-wilayah">{{ $r->panicButton->wilayah->nama }}</span>
                                    </td>
                                    <td style="font-size:12px;">
                                        {{ $r->panicButton->timestamp
                                            ? \Carbon\Carbon::createFromTimestamp($r->panicButton->timestamp, 'Asia/Jakarta')->format('d M Y, H:i:s')
                                            : '-' }}
                                    </td>
                                    <td style="font-size:12px;">
                                        {{ $r->waktu_selesai ? \Carbon\Carbon::parse($r->waktu_selesai, 'Asia/Jakarta')->format('d M Y, H:i:s') : '-' }}
                                    </td>
                                    <td style="font-size:12px; font-family:monospace;">
                                        @if ($r->panicButton->timestamp && $r->waktu_selesai)
                                            @php
                                                $epochTrigger = $r->panicButton->timestamp;
                                                $epochSelesai = \Carbon\Carbon::parse($r->waktu_selesai, 'Asia/Jakarta')
                                                    ->timestamp;
                                                $durasi = $epochSelesai - $epochTrigger;
                                            @endphp
                                            {{ gmdate('H:i:s', max(0, $durasi)) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><span class="badge-selesai"><i
                                                class="icon-copy bi bi-check-circle-fill mr-1"></i>Selesai</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function getCurrentEpochGMT7() {
                const now = new Date();
                const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
                const gmt7 = new Date(utc + (7 * 60 * 60 * 1000));
                return Math.floor(gmt7.getTime() / 1000);
            }

            function updateCountdowns() {
                document.querySelectorAll('.countdown[data-trigger]').forEach(el => {
                    const trigger = parseInt(el.dataset.trigger);
                    if (!trigger) return;
                    const diff = getCurrentEpochGMT7() - trigger;
                    const h = Math.floor(diff / 3600);
                    const m = Math.floor((diff % 3600) / 60);
                    const s = diff % 60;
                    if (diff < 60) {
                        el.textContent = `⏳ ${s} detik yang lalu`;
                    } else if (diff < 3600) {
                        el.textContent = `⏳ ${m} menit ${s} detik yang lalu`;
                    } else {
                        el.textContent = `⏳ ${h} jam ${m} menit ${s} detik yang lalu`;
                    }
                });

                document.querySelectorAll('.tugas-durasi').forEach(el => {
                    const trigger = parseInt(el.dataset.trigger);
                    const updated = parseInt(el.dataset.updated);
                    if (!trigger && !updated) return;
                    const start = trigger || updated;
                    const diff = getCurrentEpochGMT7() - start;
                    const h = Math.floor(diff / 3600);
                    const m = Math.floor((diff % 3600) / 60);
                    const s = diff % 60;
                    if (diff < 60) {
                        el.textContent = `${s} detik`;
                    } else if (diff < 3600) {
                        el.textContent = `${m} menit ${s} detik`;
                    } else {
                        el.textContent = `${h} jam ${m} menit ${s} detik`;
                    }
                });
            }

            updateCountdowns();
            setInterval(updateCountdowns, 1000);

            document.addEventListener('DOMContentLoaded', function() {
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

                document.querySelectorAll('.btn-ambil-tugas').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();

                        if (this.disabled) {
                            Swal.fire({
                                title: 'Tidak Bisa Mengambil Tugas',
                                text: 'Anda masih memiliki tugas yang sedang diproses. Selesaikan tugas tersebut terlebih dahulu.',
                                icon: 'warning',
                                confirmButtonColor: '#1d4ed8',
                                confirmButtonText: 'Mengerti',
                                timer: 3000,
                                timerProgressBar: true
                            });
                            return;
                        }

                        const form = this.closest('form');
                        const nama = this.dataset.nama;

                        Swal.fire({
                            title: 'Ambil Tugas?',
                            html: `Anda akan mengambil tugas untuk <strong>${nama}</strong>`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, Ambil Tugas!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    </div>
@endsection
