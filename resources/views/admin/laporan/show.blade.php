@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
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
                padding: 11px 0;
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

            .foto-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 10px;
                margin-top: 12px;
            }

            .foto-item {
                border-radius: 8px;
                overflow: hidden;
                border: 1px solid #e2e8f0;
                background: #f8fafc;
            }

            .foto-item img {
                width: 100%;
                height: 120px;
                object-fit: cover;
                display: block;
                cursor: pointer;
                transition: opacity .2s;
            }

            .foto-item img:hover {
                opacity: .85;
            }

            .foto-item .foto-caption {
                font-size: 10px;
                color: #64748b;
                padding: 4px 6px;
                background: #f8fafc;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .badge-selesai {
                background: #dcfce7;
                color: #16a34a;
                padding: 4px 14px;
                border-radius: 50px;
                font-size: 13px;
                font-weight: 700;
            }
        </style>

        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0"><i class="icon-copy bi bi-file-earmark-text-fill mr-2"></i>Detail Laporan</h4>
                    <small class="text-muted"><i
                            class="icon-copy bi bi-person-fill mr-1"></i>{{ $alarm->pelanggan->user->name }}</small>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="icon-copy bi bi-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        @php
            $tTrigger = $alarm->getRawOriginal('waktu_trigger')
                ? \Carbon\Carbon::parse($alarm->getRawOriginal('waktu_trigger'))
                : null;
            $tSelesai = $alarm->getRawOriginal('waktu_selesai')
                ? \Carbon\Carbon::parse($alarm->getRawOriginal('waktu_selesai'))
                : null;
            $durasi = $tTrigger && $tSelesai ? max(0, $tSelesai->timestamp - $tTrigger->timestamp) : null;
        @endphp

        <div class="row">

            <div class="col-lg-7">

                <div class="card-box pd-20 mb-20">
                    <div class="section-head"><i class="icon-copy bi bi-person-badge-fill mr-2"></i>Identitas Pelanggan
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-person-fill mr-1"></i>Nama Lengkap</div>
                                <div class="info-value">{{ $alarm->pelanggan->user->name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-card-text mr-1"></i>NIK</div>
                                <div class="info-value" style="font-family:monospace;">{{ $alarm->pelanggan->nik }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-calendar-date mr-1"></i>Tempat, Tgl Lahir
                                </div>
                                <div class="info-value">{{ $alarm->pelanggan->ttl }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-telephone-fill mr-1"></i>No. HP</div>
                                <div class="info-value">
                                    <a href="tel:{{ $alarm->pelanggan->user->no_hp }}" style="color:#265ed7;">
                                        {{ $alarm->pelanggan->user->no_hp }}
                                    </a>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-gender-ambiguous mr-1"></i>Jenis Kelamin
                                </div>
                                <div class="info-value">{{ $alarm->pelanggan->user->jenis_kelamin }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-pin-map-fill mr-1"></i>Wilayah</div>
                                <div class="info-value">{{ $alarm->panicButton->wilayah->nama }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box pd-20 mb-20">
                    <div class="section-head"><i class="icon-copy bi bi-stopwatch-fill mr-2"></i>Waktu Penanganan</div>
                    <div class="info-row">
                        <div class="info-label">
                            <i class="icon-copy bi bi-clock-history mr-1"></i>Waktu Trigger
                        </div>
                        <div class="info-value">
                            <i class="icon-copy bi bi-exclamation-triangle-fill text-danger mr-1"></i>
                            {{ $tTrigger ? $tTrigger->format('d M Y, H:i:s') . ' WIB' : '-' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <i class="icon-copy bi bi-check-circle-fill mr-1"></i>Waktu Selesai
                        </div>
                        <div class="info-value">
                            <i class="icon-copy bi bi-check-circle-fill text-success mr-1"></i>
                            {{ $tSelesai ? $tSelesai->format('d M Y, H:i:s') . ' WIB' : '-' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            <i class="icon-copy bi bi-hourglass-split mr-1"></i>Total Durasi Penanganan
                        </div>
                        <div class="info-value" style="font-family:monospace; font-size:20px; color:#d97706;">
                            <i class="icon-copy bi bi-clock-fill mr-1"></i>
                            {{ $durasi !== null ? gmdate('H:i:s', $durasi) : '-' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="icon-copy bi bi-info-circle-fill mr-1"></i>Status</div>
                        <div class="info-value">
                            <span class="badge-selesai">
                                <i class="icon-copy bi bi-check-circle-fill mr-1"></i>Selesai
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="icon-copy bi bi-pencil-square mr-1"></i>Keterangan Penanganan
                        </div>
                        <div class="info-value" style="font-weight:400; line-height:1.6;">
                            <i class="icon-copy bi bi-chat-quote mr-1"></i>{{ $alarm->keterangan ?? '-' }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-5">

                <div class="card-box pd-20 mb-20">
                    <div class="section-head"><i class="icon-copy bi bi-house-door-fill mr-2"></i>Alamat</div>
                    <div class="info-row">
                        <div class="info-label"><i class="icon-copy bi bi-pencil-fill mr-1"></i>Alamat Lengkap</div>
                        <div class="info-value">{{ $alarm->pelanggan->alamat }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="icon-copy bi bi-diagram-3-fill mr-1"></i>RT / RW</div>
                        <div class="info-value">{{ $alarm->pelanggan->RT }} / {{ $alarm->pelanggan->RW }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="icon-copy bi bi-grid-3x3-gap-fill mr-1"></i>Blok / No. Rumah</div>
                        <div class="info-value">
                            {{ $alarm->panicButton->GetBlockID }} / {{ $alarm->panicButton->GetNumber }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="icon-copy bi bi-building mr-1"></i>Desa / Kelurahan</div>
                        <div class="info-value">{{ $alarm->pelanggan->desa }} / {{ $alarm->pelanggan->kelurahan }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="icon-copy bi bi-map-fill mr-1"></i>Kecamatan</div>
                        <div class="info-value">{{ $alarm->pelanggan->kecamatan }}</div>
                    </div>
                    @if ($alarm->lokasi)
                        <div class="info-row">
                            <div class="info-label"><i class="icon-copy bi bi-geo-alt-fill mr-1"></i>Koordinat GPS</div>
                            <div class="info-value" style="font-family:monospace; font-size:12px;">
                                {{ $alarm->lokasi->latitude }}, {{ $alarm->lokasi->longtitude }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <div class="card-box pd-20">
            <div class="section-head">
                <i class="icon-copy bi bi-camera-fill mr-2"></i>Foto Dokumentasi
                <span
                    style="background:#e0f2fe; color:#0369a1; border-radius:50px; padding:2px 10px; font-size:11px; margin-left:6px;">
                    <i class="icon-copy bi bi-images mr-1"></i>{{ $alarm->dokumenFoto->count() }} foto
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
                <div style="text-align:center; padding:30px; color:#94a3b8; font-size:13px;">
                    <i class="icon-copy bi bi-camera mr-2"></i>Tidak ada foto dokumentasi
                </div>
            @endif
        </div>

        <div id="lightbox" onclick="closeLightbox()"
            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.85); z-index:9999;
                align-items:center; justify-content:center; flex-direction:column;">
            <span onclick="closeLightbox()"
                style="position:absolute; top:16px; right:20px; color:#fff; font-size:28px; cursor:pointer;">✕</span>
            <img id="lbImg" src="" alt="Foto"
                style="max-width:90vw; max-height:80vh; border-radius:8px;">
            <div id="lbCaption"
                style="color:#e2e8f0; font-size:13px; margin-top:10px; max-width:90vw; text-align:center;">
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
