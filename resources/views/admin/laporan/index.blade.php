@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <style>
            .badge-selesai {
                background: #dcfce7;
                color: #16a34a;
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

            .stat-card {
                border-radius: 12px;
                padding: 18px 20px;
                margin-bottom: 20px;
                background: #fff;
                border: 1px solid #e2e8f0;
            }

            .stat-card .stat-label {
                font-size: 12px;
                color: #94a3b8;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .stat-card .stat-value {
                font-size: 28px;
                font-weight: 700;
                color: #1e293b;
                margin-top: 4px;
                font-family: monospace;
            }

            .section-title {
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                color: #64748b;
                margin-bottom: 16px;
                padding-bottom: 8px;
                border-bottom: 2px solid #f1f5f9;
            }

            .foto-thumb {
                width: 36px;
                height: 36px;
                object-fit: cover;
                border-radius: 6px;
                border: 1px solid #e2e8f0;
                margin-right: 3px;
                cursor: pointer;
            }

            .empty-state {
                text-align: center;
                padding: 50px 20px;
                color: #94a3b8;
            }
        </style>

        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0"><i class="icon-copy bi bi-file-text-fill mr-2"></i>Laporan Saya</h4>
                    <small class="text-muted"><i class="icon-copy bi bi-clock-history mr-1"></i>Riwayat alarm yang telah
                        ditangani</small>
                </div>
            </div>
        </div>

        <div class="row pb-10">
            <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">{{ $totalAlarm }}</div>
                            <div class="font-14 text-secondary weight-500">
                                <i class="icon-copy bi bi-bell-fill mr-1"></i>Total Alarm Ditangani
                                <div style="font-size:11px; color:#94a3b8; margin-top:2px;">
                                    <i
                                        class="icon-copy bi bi-calendar mr-1"></i>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
                                    {{ $tahun }}
                                </div>
                            </div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#ff5b5b">
                                <i class="icon-copy bi bi-bell-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">{{ gmdate('H:i:s', $rerataDurasi) }}</div>
                            <div class="font-14 text-secondary weight-500">
                                <i class="icon-copy bi bi-stopwatch-fill mr-1"></i>Rata-rata Durasi
                                <div style="font-size:11px; color:#94a3b8; margin-top:2px;">Per penanganan</div>
                            </div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#00eccf">
                                <i class="icon-copy bi bi-stopwatch-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">{{ $totalFoto }}</div>
                            <div class="font-14 text-secondary weight-500">
                                <i class="icon-copy bi bi-images mr-1"></i>Total Foto Dokumentasi
                                <div style="font-size:11px; color:#94a3b8; margin-top:2px;">Foto terunggah</div>
                            </div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#09cc06">
                                <i class="icon-copy bi bi-images"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-box pd-20">
            <div class="section-title">
                <i class="icon-copy bi bi-list-ul mr-2"></i>Detail Laporan
                @if ($laporan->count() > 0)
                    <span
                        style="background:#e0f2fe; color:#0369a1; border-radius:50px; padding:2px 10px; font-size:11px; margin-left:6px;">
                        <i class="icon-copy bi bi-database mr-1"></i>{{ $laporan->count() }} data
                    </span>
                @endif
            </div>

            @if ($laporan->count() > 0)
                <div class="table-responsive">
                    <table class="data-table table nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pelanggan</th>
                                <th>Wilayah</th>
                                <th>Waktu Trigger</th>
                                <th>Waktu Selesai</th>
                                <th>Durasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan as $i => $r)
                                @php
                                    $tTrigger = $r->getRawOriginal('waktu_trigger')
                                        ? \Carbon\Carbon::parse($r->getRawOriginal('waktu_trigger'))
                                        : null;
                                    $tSelesai = $r->getRawOriginal('waktu_selesai')
                                        ? \Carbon\Carbon::parse($r->getRawOriginal('waktu_selesai'))
                                        : null;
                                    $durasi =
                                        $tTrigger && $tSelesai
                                            ? max(0, $tSelesai->timestamp - $tTrigger->timestamp)
                                            : null;
                                @endphp
                                <tr>
                                    <td style="font-size:12px; color:#94a3b8;">{{ $i + 1 }}</td>
                                    <td class="weight-600">{{ $r->pelanggan->user->name }}</td>
                                    <td>
                                        <span class="badge-wilayah">
                                            <i
                                                class="icon-copy bi bi-pin-map-fill mr-1"></i>{{ $r->panicButton->wilayah->nama }}
                                        </span>
                                    </td>
                                    <td style="font-size:12px;">
                                        <i class="icon-copy bi bi-clock-history mr-1"></i>
                                        {{ $tTrigger ? $tTrigger->format('d M Y, H:i:s') : '-' }}
                                    </td>
                                    <td style="font-size:12px;">
                                        <i class="icon-copy bi bi-check-circle-fill text-success mr-1"></i>
                                        {{ $tSelesai ? $tSelesai->format('d M Y, H:i:s') : '-' }}
                                    </td>
                                    <td style="font-family:monospace; font-size:12px;">
                                        <i class="icon-copy bi bi-hourglass-split mr-1"></i>
                                        {{ $durasi !== null ? gmdate('H:i:s', $durasi) : '-' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.laporan.show', $r->id) }}" class="btn btn-xs btn-primary">
                                            <i class="icon-copy bi bi-eye-fill mr-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div style="font-size:48px; margin-bottom:12px;"><i class="icon-copy bi bi-files"></i></div>
                    <div style="font-weight:600;">Tidak ada data</div>
                    <div style="font-size:13px;">
                        <i class="icon-copy bi bi-calendar2-x mr-1"></i>Belum ada alarm selesai di
                        {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                    </div>
                </div>
            @endif
        </div>

        <div id="lightbox" onclick="closeLightbox()"
            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.85); z-index:9999;
                align-items:center; justify-content:center; flex-direction:column;">
            <span onclick="closeLightbox()"
                style="position:absolute; top:16px; right:20px; color:#fff; font-size:28px; cursor:pointer;">✕</span>
            <img id="lbImg" src="" alt="Foto" style="max-width:90vw; max-height:80vh; border-radius:8px;">
            <div id="lbCaption" style="color:#e2e8f0; font-size:13px; margin-top:10px; max-width:90vw; text-align:center;">
            </div>
        </div>

        <script>
            function openLightbox(src, caption) {
                const lb = document.getElementById('lightbox');
                document.getElementById('lbImg').src = src;
                document.getElementById('lbCaption').textContent = caption || '';
                lb.style.display = 'flex';
            }

            function closeLightbox() {
                document.getElementById('lightbox').style.display = 'none';
            }
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeLightbox();
            });
        </script>
    </div>
@endsection
