@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <style>
            .info-label {
                font-size: 11px;
                color: #94a3b8;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .5px;
                margin-bottom: 3px;
            }

            .info-value {
                font-size: 14px;
                font-weight: 600;
                color: #1e293b;
                word-break: break-word;
            }

            .info-row {
                padding: 11px 0;
                border-bottom: 1px solid #f1f5f9;
            }

            .info-row:last-child {
                border-bottom: none;
            }

            .section-head {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                color: #94a3b8;
                margin-bottom: 14px;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .badge-menunggu {
                background: #fef9c3;
                color: #a16207;
                padding: 5px 14px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .badge-diproses {
                background: #dbeafe;
                color: #1d4ed8;
                padding: 5px 14px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .badge-selesai {
                background: #dcfce7;
                color: #16a34a;
                padding: 5px 14px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            /* Foto grid */
            .foto-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
                gap: 10px;
                margin-top: 12px;
            }

            .foto-item {
                border-radius: 10px;
                overflow: hidden;
                border: 1px solid #e2e8f0;
                background: #f8fafc;
            }

            .foto-item img {
                width: 100%;
                height: 110px;
                object-fit: cover;
                display: block;
                cursor: pointer;
                transition: opacity .2s;
            }

            .foto-item img:hover {
                opacity: .8;
            }

            .foto-caption {
                font-size: 10px;
                color: #64748b;
                padding: 4px 8px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Map */
            #detailMap {
                height: 220px;
                border-radius: 10px;
                border: 1px solid #e2e8f0;
                background: #f1f5f9;
            }

            /* Responsive grid */
            .detail-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }

            .detail-grid-2 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-top: 20px;
            }

            @media(max-width:992px) {

                .detail-grid,
                .detail-grid-2 {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        {{-- ── HEADER ──────────────────────────────────────── --}}
        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0">
                        <i class="icon-copy bi bi-file-earmark-text-fill mr-2"></i>Detail Laporan
                    </h4>
                    <small class="text-muted">{{ $alarm->pelanggan->user->name }} &middot; ID #{{ $alarm->id }}</small>
                </div>
                <div class="col-auto d-flex gap-2" style="gap:8px;">
                    <a href="{{ route('superadmin.laporan-pelanggan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="icon-copy bi bi-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- ── GRID UTAMA ──────────────────────────────────── --}}
        <div class="detail-grid">

            {{-- KOLOM KIRI: Identitas Pelanggan --}}
            <div class="card-box pd-20">
                <div class="section-head"><i class="icon-copy bi bi-person-fill mr-1"></i>Identitas Pelanggan</div>
                <div class="info-row">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $alarm->pelanggan->user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">NIK</div>
                    <div class="info-value" style="font-family:monospace;">{{ $alarm->pelanggan->nik }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tempat, Tgl Lahir</div>
                    <div class="info-value">{{ $alarm->pelanggan->ttl }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">No. HP</div>
                    <div class="info-value">
                        <a href="tel:{{ $alarm->pelanggan->user->no_hp }}" style="color:#265ed7;">
                            {{ $alarm->pelanggan->user->no_hp }}
                        </a>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value" style="font-size:13px;">{{ $alarm->pelanggan->user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">{{ $alarm->pelanggan->user->jenis_kelamin }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat Lengkap</div>
                    <div class="info-value" style="font-size:13px;">
                        {{ $alarm->pelanggan->alamat }}, RT {{ $alarm->pelanggan->RT }}/RW {{ $alarm->pelanggan->RW }},
                        {{ $alarm->pelanggan->desa }}, {{ $alarm->pelanggan->kecamatan }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Blok / Nomor Rumah</div>
                    <div class="info-value">{{ $alarm->panicButton->GetBlockID }} / {{ $alarm->panicButton->GetNumber }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Wilayah</div>
                    <div class="info-value">
                        {{ $alarm->panicButton->wilayah->nama }}
                        <span
                            style="font-size:11px; background:#e7ebf5; color:#265ed7; padding:2px 8px; border-radius:50px; margin-left:4px;">
                            {{ $alarm->panicButton->wilayah->kode_wilayah }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Data Admin + Waktu --}}
            <div style="display:flex; flex-direction:column; gap:20px;">

                {{-- Admin Penanganan --}}
                <div class="card-box pd-20">
                    <div class="section-head"><i class="icon-copy bi bi-person-badge-fill mr-1"></i>Admin Penanganan</div>
                    @if ($alarm->ditangani_oleh)
                        <div class="info-row">
                            <div class="info-label">Nama Admin</div>
                            <div class="info-value" style="color:#1d4ed8;">{{ $alarm->ditangani_oleh }}</div>
                        </div>
                        @if ($alarm->admin)
                            <div class="info-row">
                                <div class="info-label">No. HP Admin</div>
                                <div class="info-value">
                                    <a href="tel:{{ $alarm->admin->no_hp }}"
                                        style="color:#265ed7;">{{ $alarm->admin->no_hp }}</a>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email Admin</div>
                                <div class="info-value" style="font-size:13px;">{{ $alarm->admin->email }}</div>
                            </div>
                        @endif
                        @if ($alarm->keterangan)
                            <div class="info-row">
                                <div class="info-label">Keterangan Penanganan</div>
                                <div class="info-value"
                                    style="font-size:13px; font-weight:400; color:#374151; line-height:1.6; background:#f8fafc; border-radius:8px; padding:10px 12px; margin-top:4px;">
                                    {{ $alarm->keterangan }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div style="text-align:center; padding:20px; color:#cbd5e1; font-size:13px;">
                            <i class="icon-copy bi bi-person-dash"
                                style="font-size:28px; display:block; margin-bottom:8px;"></i>
                            Belum ada admin yang mengambil tugas ini
                        </div>
                    @endif
                </div>

                {{-- Waktu & Durasi --}}
                <div class="card-box pd-20">
                    <div class="section-head"><i class="icon-copy bi bi-clock-history mr-1"></i>Waktu & Durasi</div>
                    @php
                        $tTrigger = $alarm->getRawOriginal('waktu_trigger')
                            ? \Carbon\Carbon::parse($alarm->getRawOriginal('waktu_trigger'))
                            : null;
                        $tSelesai = $alarm->getRawOriginal('waktu_selesai')
                            ? \Carbon\Carbon::parse($alarm->getRawOriginal('waktu_selesai'))
                            : null;

                        $durasi = $tTrigger && $tSelesai ? max(0, $tSelesai->timestamp - $tTrigger->timestamp) : null;

                        if ($durasi !== null) {
                            $jam = intdiv($durasi, 3600);
                            $menit = intdiv($durasi % 3600, 60);
                            $detik = $durasi % 60;
                            if ($jam > 0) {
                                $durasiTeks = $jam . ' jam' . ($menit > 0 ? ' ' . $menit . ' menit' : '');
                            } elseif ($menit > 0) {
                                $durasiTeks = $menit . ' menit' . ($detik > 0 ? ' ' . $detik . ' detik' : '');
                            } else {
                                $durasiTeks = $detik . ' detik';
                            }
                            $durasiWarna = $durasi > 3600 ? '#dc2626' : ($durasi > 1800 ? '#d97706' : '#16a34a');
                        }
                    @endphp
                    <div class="info-row">
                        <div class="info-label"><i
                                class="icon-copy bi bi-exclamation-triangle-fill text-danger mr-1"></i>Waktu Trigger</div>
                        <div class="info-value">
                            {{ $tTrigger ? $tTrigger->format('d M Y, H:i:s') . ' WIB' : '—' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="icon-copy bi bi-check-circle-fill text-success mr-1"></i>Waktu
                            Selesai</div>
                        <div class="info-value">
                            {{ $tSelesai ? $tSelesai->format('d M Y, H:i:s') . ' WIB' : '—' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="icon-copy bi bi-hourglass-split mr-1"></i>Total Durasi</div>
                        <div class="info-value">
                            @if ($durasi !== null)
                                <span style="color:{{ $durasiWarna }}; font-size:18px;">{{ $durasiTeks }}</span>
                            @else
                                <span style="color:#cbd5e1;">—</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── BARIS BAWAH: Map + Foto ─────────────────────── --}}
        <div class="detail-grid-2">

            {{-- Peta Lokasi --}}
            <div class="card-box pd-20">
                <div class="section-head"><i class="icon-copy bi bi-geo-alt-fill mr-1"></i>Lokasi Panic Button</div>
                @if ($alarm->lokasi)
                    <div style="font-size:12px; color:#94a3b8; margin-bottom:10px; font-family:monospace;">
                        {{ $alarm->lokasi->latitude }}, {{ $alarm->lokasi->longtitude }}
                    </div>
                    <div id="detailMap"></div>
                @else
                    <div style="text-align:center; padding:30px; color:#cbd5e1; font-size:13px;">
                        <i class="icon-copy bi bi-geo-slash" style="font-size:28px; display:block; margin-bottom:8px;"></i>
                        Koordinat tidak tersedia
                    </div>
                @endif
            </div>

            {{-- Foto Dokumentasi --}}
            <div class="card-box pd-20">
                <div class="section-head">
                    <i class="icon-copy bi bi-camera-fill mr-1"></i>Foto Dokumentasi
                    <span
                        style="background:#e0f2fe; color:#0369a1; border-radius:50px; padding:2px 8px; font-size:10px; margin-left:4px;">
                        {{ $alarm->dokumenFoto->count() }} foto
                    </span>
                </div>
                @if ($alarm->dokumenFoto->count() > 0)
                    <div class="foto-grid">
                        @foreach ($alarm->dokumenFoto as $foto)
                            <div class="foto-item">
                                <img src="{{ Storage::url($foto->foto_dokumentasi) }}" alt="Dokumentasi"
                                    onclick="openLightbox('{{ Storage::url($foto->foto_dokumentasi) }}', '{{ $foto->keterangan }}')">
                                @if ($foto->keterangan)
                                    <div class="foto-caption" title="{{ $foto->keterangan }}">
                                        <i class="icon-copy bi bi-chat-text mr-1"></i>{{ $foto->keterangan }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center; padding:30px; color:#cbd5e1; font-size:13px;">
                        <i class="icon-copy bi bi-camera-slash"
                            style="font-size:28px; display:block; margin-bottom:8px;"></i>
                        Tidak ada foto dokumentasi
                    </div>
                @endif
            </div>

        </div>

        {{-- Lightbox --}}
        <div id="lightbox" onclick="closeLightbox()"
            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.85); z-index:9999;
            align-items:center; justify-content:center; flex-direction:column;">
            <span onclick="closeLightbox()"
                style="position:absolute; top:16px; right:20px; color:#fff; font-size:28px; cursor:pointer;">✕</span>
            <img id="lbImg" src="" alt="Foto"
                style="max-width:90vw; max-height:80vh; border-radius:8px;">
            <div id="lbCaption" style="color:#e2e8f0; font-size:13px; margin-top:10px; text-align:center;"></div>
        </div>

        <script>
            function openLightbox(src, caption) {
                document.getElementById('lbImg').src = src;
                document.getElementById('lbCaption').textContent = caption || '';
                document.getElementById('lightbox').style.display = 'flex';
            }

            function closeLightbox() {
                document.getElementById('lightbox').style.display = 'none';
            }
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeLightbox();
            });

            @if ($alarm->lokasi)
                const LAT = {{ $alarm->lokasi->latitude }};
                const LNG = {{ $alarm->lokasi->longtitude }};
                const NAMA = @json($alarm->pelanggan->user->name);

                (function loadMap() {
                    const s = document.createElement('script');
                    s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                    s.onload = function() {
                        const map = L.map('detailMap', {
                                zoomControl: true,
                                scrollWheelZoom: false
                            })
                            .setView([LAT, LNG], 17);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        }).addTo(map);
                        L.marker([LAT, LNG]).addTo(map)
                            .bindPopup('🚨 ' + NAMA).openPopup();
                    };
                    document.head.appendChild(s);
                })();
            @endif
        </script>

    </div>
@endsection
