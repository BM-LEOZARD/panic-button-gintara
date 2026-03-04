@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            .info-label {
                font-size: 12px;
                color: #94a3b8;
                font-weight: 500;
                margin-bottom: 2px;
            }

            .info-value {
                font-size: 14px;
                color: #1e293b;
                font-weight: 600;
            }

            .info-row {
                padding: 12px 0;
                border-bottom: 1px solid #f1f5f9;
            }

            .info-row:last-child {
                border-bottom: none;
            }

            .section-head {
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                color: #94a3b8;
                margin-bottom: 14px;
            }

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

            .guid-box {
                background: #f8fafc;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 16px;
            }

            .guid-segments {
                display: flex;
                justify-content: center;
                gap: 4px;
                flex-wrap: wrap;
                margin-bottom: 14px;
            }

            .seg {
                padding: 4px 12px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 700;
                font-family: monospace;
                letter-spacing: 1px;
            }

            .seg-fixed {
                background: #e0f2fe;
                color: #0369a1;
            }

            .seg-wilayah {
                background: #e7ebf5;
                color: #265ed7;
            }

            .seg-acak {
                background: #fef9c3;
                color: #a16207;
                border: 1.5px dashed #fcd34d;
            }

            .seg-urutan {
                background: #dcfce7;
                color: #16a34a;
            }

            .guid-readonly {
                font-family: 'Courier New', monospace;
                font-size: 16px;
                font-weight: 700;
                letter-spacing: 2px;
                color: #1e293b;
                text-align: center;
                background: #fff;
                border: 1.5px solid #e2e8f0;
                border-radius: 10px;
                padding: 14px;
                margin-bottom: 8px;
                user-select: all;
                word-break: break-all;
            }

            /* ── FIX MAP Z-INDEX ── */
            /* Wrapper peta harus relatif dan z-index rendah agar tidak menembus header */
            .map-wrapper {
                position: relative;
                z-index: 1;
            }

            #miniMap {
                height: 200px;
                border-radius: 10px;
                background: #f1f5f9;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #94a3b8;
                font-size: 13px;
                cursor: pointer;
                border: 1px solid #e2e8f0;
                /* Pastikan peta tidak pernah melebihi z-index header */
                position: relative;
                z-index: 1;
            }

            #miniMap.loaded {
                display: block;
            }

            /* Override Leaflet z-index agar tidak menembus header/sidebar */
            .leaflet-top,
            .leaflet-bottom {
                z-index: 400 !important;
            }

            .leaflet-pane {
                z-index: 200 !important;
            }

            .leaflet-tile-pane {
                z-index: 200 !important;
            }

            .leaflet-overlay-pane {
                z-index: 250 !important;
            }

            .leaflet-marker-pane,
            .leaflet-shadow-pane {
                z-index: 300 !important;
            }

            .leaflet-popup-pane {
                z-index: 350 !important;
            }
        </style>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0">Detail Pendaftaran</h4>
                </div>
                <div class="col-auto">
                    <a href="{{ route('superadmin.data-pendaftar.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- ── KOLOM KIRI ──────────────────────────────────────── --}}
            <div class="col-lg-7">

                {{-- Identitas --}}
                <div class="card-box pd-20 mb-20">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <div class="section-head mb-0">Identitas Pendaftar</div>
                        @if ($pendaftaran->status === 'Menunggu')
                            <span class="badge-menunggu"><i class="bi bi-clock-fill mr-1"></i>Menunggu</span>
                        @elseif ($pendaftaran->status === 'Disetujui')
                            <span class="badge-disetujui"><i class="bi bi-check-circle-fill mr-1"></i>Disetujui</span>
                        @else
                            <span class="badge-ditolak"><i class="bi bi-x-circle-fill mr-1"></i>Ditolak</span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Nama Lengkap</div>
                                <div class="info-value">{{ $pendaftaran->name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Username</div>
                                <div class="info-value">{{ $pendaftaran->username }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">NIK</div>
                                <div class="info-value" style="font-family:monospace;">{{ $pendaftaran->nik }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tempat, Tgl Lahir</div>
                                <div class="info-value">{{ $pendaftaran->ttl }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Jenis Kelamin</div>
                                <div class="info-value">{{ $pendaftaran->jenis_kelamin }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $pendaftaran->email }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">No. HP</div>
                                <div class="info-value">{{ $pendaftaran->no_hp }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Wilayah</div>
                                <div class="info-value">
                                    {{ $pendaftaran->wilayah->nama }}
                                    <span
                                        style="font-size:11px; color:#265ed7; background:#e7ebf5; border-radius:50px; padding:2px 8px; margin-left:4px;">
                                        {{ $pendaftaran->wilayah->kode_wilayah }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Didaftarkan</div>
                                <div class="info-value">{{ $pendaftaran->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="card-box pd-20 mb-20">
                    <div class="section-head">Alamat & Lokasi</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Alamat</div>
                                <div class="info-value">{{ $pendaftaran->alamat }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">RT / RW</div>
                                <div class="info-value">{{ $pendaftaran->RT }} / {{ $pendaftaran->RW }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Blok / No. Rumah</div>
                                <div class="info-value">{{ $pendaftaran->GetBlockID }} / {{ $pendaftaran->GetNumber }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Desa</div>
                                <div class="info-value">{{ $pendaftaran->desa }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Kelurahan</div>
                                <div class="info-value">{{ $pendaftaran->kelurahan }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Kecamatan</div>
                                <div class="info-value">{{ $pendaftaran->kecamatan }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Koordinat</div>
                                <div class="info-value" style="font-family:monospace; font-size:12px;">
                                    {{ $pendaftaran->latitude }}, {{ $pendaftaran->longtitude }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Map wrapper dengan z-index fix --}}
                    <div class="map-wrapper" style="margin-top:16px;">
                        <div id="miniMap" onclick="loadMap()">
                            <i class="bi bi-map mr-1"></i> Klik untuk tampilkan lokasi di peta
                        </div>
                        <small style="color:#94a3b8; font-size:11px;">Peta dimuat saat diklik untuk mempercepat
                            halaman.</small>
                    </div>
                </div>

                {{-- Foto KTP --}}
                <div class="card-box pd-20 mb-20">
                    <div class="section-head">Foto KTP</div>
                    <img src="{{ Storage::url($pendaftaran->foto_ktp) }}" alt="KTP {{ $pendaftaran->name }}"
                        style="max-width:100%; border-radius:10px; border:1px solid #e2e8f0;"
                        onerror="this.src=''; this.alt='Foto tidak tersedia';" />
                </div>

            </div>

            {{-- ── KOLOM KANAN ─────────────────────────────────────── --}}
            <div class="col-lg-5">

                @if ($pendaftaran->status === 'Menunggu')

                    <div class="card-box pd-20 mb-20">
                        <div class="section-head">Buat Akun Panic Button</div>

                        @if ($errors->any())
                            <div class="alert alert-danger" style="font-size:13px;">
                                @foreach ($errors->all() as $err)
                                    <div><i class="bi bi-x-circle-fill mr-1"></i> {{ $err }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('superadmin.data-pendaftar.setujui', $pendaftaran->id) }}"
                            id="formSetujui">
                            @csrf

                            {{-- GUID --}}
                            <div class="guid-box">
                                <div class="info-label mb-10">GUID Panic Button</div>
                                <div class="guid-segments">
                                    <span class="seg seg-fixed">GNTR</span>
                                    <span style="color:#94a3b8; line-height:24px;">-</span>
                                    <span class="seg seg-fixed">PB</span>
                                    <span style="color:#94a3b8; line-height:24px;">-</span>
                                    <span class="seg seg-wilayah">{{ $pendaftaran->wilayah->kode_wilayah }}</span>
                                    <span style="color:#94a3b8; line-height:24px;">-</span>
                                    <span class="seg seg-acak">{{ $kodeAcak }}</span>
                                    <span style="color:#94a3b8; line-height:24px;">-</span>
                                    <span class="seg seg-urutan">{{ $nomorUrut }}</span>
                                </div>
                                <div class="guid-readonly">{{ $guid }}</div>
                                <small style="color:#94a3b8; font-size:11px; display:block; text-align:center;">
                                    GUID di-generate otomatis dan tidak dapat diubah.
                                </small>
                                <input type="hidden" name="GUID" value="{{ $guid }}" />
                            </div>

                            {{-- DisID --}}
                            <div class="form-group mb-16">
                                <label style="color:#64748b; font-size:13px; font-weight:600;">DisID Perangkat</label>
                                <input type="text" name="DisID" class="form-control"
                                    value="{{ old('DisID', $disId) }}" style="font-family:monospace; letter-spacing:1px;"
                                    readonly />
                                <small class="text-muted" style="font-size:11px;">
                                    DisID = Kode wilayah + urutan perangkat (otomatis).
                                </small>
                            </div>

                            <button type="button" class="btn btn-success btn-block" id="btnSetujui">
                                <i class="bi bi-check-circle-fill mr-1"></i> Setujui & Buat Akun Panic Button
                            </button>
                        </form>
                    </div>

                    <div class="card-box pd-20">
                        <div class="section-head">Tolak Pendaftaran</div>
                        <form method="POST" action="{{ route('superadmin.data-pendaftar.tolak', $pendaftaran->id) }}"
                            id="formTolak">
                            @csrf
                            <div class="form-group">
                                <label style="color:#64748b; font-size:13px; font-weight:600;">
                                    Alasan Penolakan <span class="text-danger">*</span>
                                </label>
                                <textarea name="catatan_penolakan" id="catatanPenolakan" class="form-control" rows="3"
                                    placeholder="Jelaskan alasan penolakan...">{{ old('catatan_penolakan') }}</textarea>
                            </div>

                            <button type="button" class="btn btn-danger btn-block" id="btnTolak">
                                <i class="bi bi-x-circle-fill mr-1"></i> Tolak Pendaftaran
                            </button>
                        </form>
                    </div>
                @elseif ($pendaftaran->status === 'Disetujui')
                    <div class="card-box pd-20">
                        <div class="section-head">Akun Panic Button</div>
                        <div class="alert alert-success" style="font-size:13px;">
                            <i class="bi bi-check-circle-fill mr-1"></i> Pendaftaran disetujui pada
                            {{ $pendaftaran->waktu_verifikasi?->format('d M Y, H:i') }}
                        </div>
                        @if ($pendaftaran->panicButton ?? false)
                            <div class="info-row">
                                <div class="info-label">GUID</div>
                                <div class="info-value"
                                    style="font-family:monospace; font-size:13px; word-break:break-all;">
                                    {{ $pendaftaran->panicButton->GUID }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">DisID</div>
                                <div class="info-value" style="font-family:monospace;">
                                    {{ $pendaftaran->panicButton->DisID }}
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="card-box pd-20">
                        <div class="section-head">Keterangan Penolakan</div>
                        <div class="alert alert-danger" style="font-size:13px;">
                            <i class="bi bi-x-circle-fill mr-1"></i> Pendaftaran ditolak pada
                            {{ $pendaftaran->waktu_verifikasi?->format('d M Y, H:i') }}
                        </div>
                        <div class="info-label">Catatan</div>
                        <p style="color:#dc2626; font-size:14px;">{{ $pendaftaran->catatan_penolakan }}</p>
                    </div>
                @endif

            </div>
        </div>

        <script>
            let mapLoaded = false;
            const LAT = {{ $pendaftaran->latitude }};
            const LNG = {{ $pendaftaran->longtitude }};
            const NAMA = @json($pendaftaran->name);

            function loadMap() {
                if (mapLoaded) return;
                mapLoaded = true;

                const el = document.getElementById('miniMap');
                el.innerHTML = '';
                el.classList.add('loaded');
                el.style.cursor = 'default';

                const script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.onload = function() {
                    const map = L.map('miniMap', {
                            zoomControl: true,
                            scrollWheelZoom: false
                        })
                        .setView([LAT, LNG], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                    L.marker([LAT, LNG]).addTo(map)
                        .bindPopup('Lokasi: ' + NAMA).openPopup();
                };
                document.head.appendChild(script);
            }

            document.getElementById('btnSetujui')?.addEventListener('click', function() {
                Swal.fire({
                    title: 'Setujui Pendaftaran?',
                    html: `Akun Panic Button akan dibuat untuk:<br>
                       <strong style="font-size:15px;">{{ $pendaftaran->name }}</strong><br><br>
                       <small style="color:#64748b;">GUID dan DisID akan ditetapkan secara otomatis dan tidak dapat diubah.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-check-circle-fill mr-1"></i> Ya, Setujui',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang membuat akun Panic Button, harap tunggu.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        document.getElementById('formSetujui').submit();
                    }
                });
            });

            document.getElementById('btnTolak')?.addEventListener('click', function() {
                const catatan = document.getElementById('catatanPenolakan').value.trim();

                if (!catatan) {
                    Swal.fire({
                        title: 'Alasan Diperlukan!',
                        text: 'Mohon isi alasan penolakan terlebih dahulu sebelum melanjutkan.',
                        icon: 'warning',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Oke, Mengerti',
                    });
                    document.getElementById('catatanPenolakan').focus();
                    return;
                }

                Swal.fire({
                    title: 'Tolak Pendaftaran?',
                    html: `Pendaftaran atas nama:<br>
                       <strong style="font-size:15px;">{{ $pendaftaran->name }}</strong><br><br>
                       akan <span style="color:#dc2626; font-weight:700;">ditolak</span> dengan alasan yang telah diisi.<br>
                       <small style="color:#64748b;">Tindakan ini tidak dapat dibatalkan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-x-circle-fill mr-1"></i> Ya, Tolak',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang menyimpan data penolakan, harap tunggu.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        document.getElementById('formTolak').submit();
                    }
                });
            });
        </script>
    </div>
@endsection
