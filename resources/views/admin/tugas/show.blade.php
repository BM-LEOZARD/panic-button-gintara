@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                color: #94a3b8;
                margin-bottom: 14px;
            }

            #miniMap {
                height: 240px;
                border-radius: 10px;
                background: #f1f5f9;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #94a3b8;
                font-size: 13px;
                cursor: pointer;
                border: 1px solid #e2e8f0;
            }

            #miniMap.loaded {
                display: block;
            }

            .alert-darurat {
                background: #fff1f2;
                border: 2px solid #fecaca;
                border-radius: 12px;
                padding: 16px 20px;
                margin-bottom: 20px;
            }

            .pulse-dot {
                display: inline-block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: #dc2626;
                margin-right: 6px;
                animation: pulse 1.4s infinite;
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

            .timer-besar {
                font-family: monospace;
                font-size: 28px;
                font-weight: 700;
                color: #dc2626;
            }

            @media (max-width: 768px) {
                .timer-besar {
                    font-size: 24px;
                }

                .alert-darurat {
                    padding: 12px 15px;
                }
            }

            @media (max-width: 480px) {
                .timer-besar {
                    font-size: 20px;
                }
            }

            /* Foto dokumentasi */
            .foto-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 10px;
                margin-top: 12px;
            }

            @media (max-width: 768px) {
                .foto-grid {
                    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                }
            }

            @media (max-width: 480px) {
                .foto-grid {
                    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                }
            }

            .foto-item {
                position: relative;
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

            @media (max-width: 768px) {
                .foto-item img {
                    height: 100px;
                }
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

            .foto-item .btn-hapus-foto {
                position: absolute;
                top: 4px;
                right: 4px;
                background: rgba(220, 38, 38, .85);
                color: #fff;
                border: none;
                border-radius: 50%;
                width: 22px;
                height: 22px;
                font-size: 12px;
                line-height: 22px;
                text-align: center;
                cursor: pointer;
                padding: 0;
            }

            .foto-item .btn-hapus-foto:hover {
                background: #b91c1c;
            }

            /* Drop zone upload */
            .drop-zone {
                border: 2px dashed #cbd5e1;
                border-radius: 10px;
                padding: 20px;
                text-align: center;
                color: #94a3b8;
                font-size: 13px;
                cursor: pointer;
                transition: border-color .2s, background .2s;
                margin-top: 12px;
            }

            .drop-zone:hover,
            .drop-zone.dragover {
                border-color: #265ed7;
                background: #eff6ff;
                color: #265ed7;
            }

            .preview-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 8px;
                margin-top: 10px;
            }

            @media (max-width: 768px) {
                .preview-list {
                    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                }
            }

            .preview-item {
                position: relative;
                border-radius: 8px;
                overflow: hidden;
                border: 1px solid #bfdbfe;
            }

            .preview-item img {
                width: 100%;
                height: 100px;
                object-fit: cover;
                display: block;
            }

            @media (max-width: 768px) {
                .preview-item img {
                    height: 80px;
                }
            }

            .preview-item input[type="text"] {
                width: 100%;
                font-size: 10px;
                padding: 3px 5px;
                border: none;
                border-top: 1px solid #e2e8f0;
                background: #f8fafc;
                outline: none;
            }

            .preview-item .btn-remove-preview {
                position: absolute;
                top: 3px;
                right: 3px;
                background: rgba(0, 0, 0, .55);
                color: #fff;
                border: none;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                font-size: 11px;
                line-height: 20px;
                text-align: center;
                cursor: pointer;
                padding: 0;
            }

            /* Lightbox */
            #lightbox {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .85);
                z-index: 9999;
                align-items: center;
                justify-content: center;
                flex-direction: column;
            }

            #lightbox.show {
                display: flex;
            }

            #lightbox img {
                max-width: 90vw;
                max-height: 80vh;
                border-radius: 8px;
            }

            #lightbox .lb-caption {
                color: #e2e8f0;
                font-size: 13px;
                margin-top: 10px;
                max-width: 90vw;
                text-align: center;
            }

            #lightbox .lb-close {
                position: absolute;
                top: 16px;
                right: 20px;
                color: #fff;
                font-size: 28px;
                cursor: pointer;
                line-height: 1;
            }

            /* Responsive adjustments */
            .row-responsive {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }

            .col-left-responsive {
                flex: 1 1 60%;
                padding: 0 10px;
            }

            .col-right-responsive {
                flex: 1 1 40%;
                padding: 0 10px;
            }

            @media (max-width: 992px) {

                .col-left-responsive,
                .col-right-responsive {
                    flex: 1 1 100%;
                }

                .col-right-responsive {
                    margin-top: 20px;
                }
            }

            /* Form responsive */
            .form-control {
                font-size: 14px;
            }

            @media (max-width: 768px) {
                .form-control {
                    font-size: 16px;
                    /* Prevent zoom on mobile */
                }
            }
        </style>

        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0">Tangani Alarm</h4>
                    <small class="text-muted d-block d-sm-inline">{{ $alarm->pelanggan->user->name }}</small>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.tugas.index') }}" class="btn btn-secondary btn-sm">
                        <i class="icon-copy bi bi-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="alert-success">
                <i class="icon-copy bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" id="alert-error">
                <i class="icon-copy bi bi-exclamation-triangle-fill mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- Banner darurat + timer --}}
        <div class="alert-darurat">
            <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">
                <div>
                    <div style="font-size:16px; font-weight:700; color:#dc2626;">
                        <span class="pulse-dot"></span>
                        <i class="icon-copy bi bi-exclamation-triangle-fill mr-1"></i>ALARM DARURAT AKTIF
                    </div>
                    <div style="font-size:13px; color:#64748b; margin-top:4px;">
                        <i class="icon-copy bi bi-clock-history mr-1"></i>Trigger:
                        <strong>
                            {{ $alarm->panicButton->timestamp
                                ? \Carbon\Carbon::createFromTimestamp($alarm->panicButton->timestamp)->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB'
                                : '-' }}
                        </strong>
                    </div>
                </div>
                <div class="text-center">
                    <div style="font-size:11px; color:#94a3b8; margin-bottom:2px;">
                        <i class="icon-copy bi bi-stopwatch-fill mr-1"></i>DURASI PENANGANAN
                    </div>
                    <div class="timer-besar" id="timerBesar">00:00:00</div>
                </div>
            </div>
        </div>

        <div class="row-responsive">
            {{-- KOLOM KIRI: Info Pelanggan --}}
            <div class="col-left-responsive">

                {{-- Identitas --}}
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
                                <div class="info-value">
                                    {{ $alarm->panicButton->wilayah->nama }}
                                    <span
                                        style="font-size:11px; color:#265ed7; background:#e7ebf5; border-radius:50px; padding:2px 8px; margin-left:4px;">
                                        {{ $alarm->panicButton->wilayah->kode_wilayah }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="card-box pd-20 mb-20">
                    <div class="section-head"><i class="icon-copy bi bi-house-door-fill mr-2"></i>Alamat & Lokasi</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-pencil-fill mr-1"></i>Alamat</div>
                                <div class="info-value">{{ $alarm->pelanggan->alamat }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-diagram-3-fill mr-1"></i>RT / RW</div>
                                <div class="info-value">{{ $alarm->pelanggan->RT }} / {{ $alarm->pelanggan->RW }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-grid-3x3-gap-fill mr-1"></i>Blok / No.
                                    Rumah</div>
                                <div class="info-value">
                                    {{ $alarm->panicButton->GetBlockID }} / {{ $alarm->panicButton->GetNumber }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-building mr-1"></i>Desa / Kelurahan</div>
                                <div class="info-value">{{ $alarm->pelanggan->desa }} / {{ $alarm->pelanggan->kelurahan }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label"><i class="icon-copy bi bi-map-fill mr-1"></i>Kecamatan</div>
                                <div class="info-value">{{ $alarm->pelanggan->kecamatan }}</div>
                            </div>
                            @if ($alarm->lokasi)
                                <div class="info-row">
                                    <div class="info-label"><i class="icon-copy bi bi-geo-alt-fill mr-1"></i>Koordinat GPS
                                    </div>
                                    <div class="info-value" style="font-family:monospace; font-size:12px;">
                                        {{ $alarm->lokasi->latitude }}, {{ $alarm->lokasi->longtitude }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($alarm->lokasi)
                        <div style="margin-top:16px;">
                            <div id="miniMap" onclick="loadMap()">
                                <i class="icon-copy bi bi-map mr-1"></i>🗺️ Klik untuk tampilkan lokasi di peta
                            </div>
                            <small style="color:#94a3b8; font-size:11px;">
                                <i class="icon-copy bi bi-info-circle mr-1"></i>Peta dimuat saat diklik.
                            </small>
                        </div>
                    @endif
                </div>

                {{-- FOTO DOKUMENTASI --}}
                <div class="card-box pd-20 mb-20">
                    <div class="section-head">
                        <i class="icon-copy bi bi-camera-fill mr-2"></i>Foto Dokumentasi
                        <span
                            style="background:#e0f2fe; color:#0369a1; border-radius:50px; padding:2px 10px; font-size:11px; margin-left:6px;">
                            <i class="icon-copy bi bi-images mr-1"></i>{{ $alarm->dokumenFoto->count() }} foto
                        </span>
                    </div>

                    {{-- Grid foto yang sudah ada --}}
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

                                    {{-- Hapus foto dengan konfirmasi --}}
                                    <form method="POST" action="{{ route('admin.tugas.hapus-foto', $foto->id) }}"
                                        class="hapus-foto-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-hapus-foto btn-hapus-foto-trigger"
                                            data-foto-id="{{ $foto->id }}" title="Hapus foto">
                                            <i class="icon-copy bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align:center; padding:20px; color:#94a3b8; font-size:13px;">
                            <i class="icon-copy bi bi-camera mr-2"></i>Belum ada foto dokumentasi
                        </div>
                    @endif

                    <hr style="margin: 16px 0;">

                    {{-- Form upload foto baru --}}
                    <div style="font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">
                        <i class="icon-copy bi bi-cloud-upload-fill mr-1"></i>Tambah Foto
                    </div>

                    @if ($errors->has('foto.*'))
                        <div class="alert alert-danger" style="font-size:12px; padding:8px 12px;">
                            @foreach ($errors->get('foto.*') as $err)
                                <div><i class="icon-copy bi bi-exclamation-triangle-fill mr-1"></i>❌ {{ $err[0] }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.tugas.upload-foto', $alarm->id) }}"
                        enctype="multipart/form-data" id="formUploadFoto">
                        @csrf

                        {{-- Drop zone --}}
                        <div class="drop-zone" id="dropZone" onclick="document.getElementById('inputFoto').click()">
                            <i class="icon-copy bi bi-cloud-upload mr-2"></i>📁 Klik atau seret foto ke sini<br>
                            <span style="font-size:11px;">JPG, PNG, WEBP — Maks. 5MB per foto</span>
                        </div>
                        <input type="file" id="inputFoto" name="foto[]" multiple accept="image/*"
                            style="display:none;" onchange="previewFotos(this)">

                        {{-- Preview sebelum upload --}}
                        <div class="preview-list" id="previewList"></div>

                        {{-- Kontainer input tersembunyi untuk keterangan --}}
                        <div id="keteranganInputs"></div>

                        <button type="submit" class="btn btn-primary btn-sm mt-10" id="btnUpload"
                            style="display:none;">
                            <i class="icon-copy bi bi-cloud-upload-fill mr-1"></i>📤 Upload Foto
                        </button>
                    </form>
                </div>
            </div>

            {{-- KOLOM KANAN: Form Selesaikan --}}
            <div class="col-right-responsive">
                <div class="card-box pd-20">
                    <div class="section-head"><i class="icon-copy bi bi-check2-circle mr-2"></i>Selesaikan Penanganan
                    </div>

                    @if ($errors->has('keterangan'))
                        <div class="alert alert-danger" style="font-size:13px;">
                            <i class="icon-copy bi bi-exclamation-triangle-fill mr-1"></i>❌
                            {{ $errors->first('keterangan') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.tugas.selesai', $alarm->id) }}" id="formSelesaikan">
                        @csrf
                        <div class="form-group">
                            <label style="font-weight:600; color:#374151;">
                                <i class="icon-copy bi bi-pencil-square mr-1"></i>Keterangan Penanganan <span
                                    class="text-danger">*</span>
                            </label>
                            <textarea name="keterangan" class="form-control" rows="5"
                                placeholder="Jelaskan tindakan yang telah dilakukan, kondisi pelanggan, dan hasil penanganan..." required>{{ old('keterangan') }}</textarea>
                            <small class="text-muted"><i class="icon-copy bi bi-info-circle mr-1"></i>Wajib diisi sebelum
                                menandai tugas sebagai selesai.</small>
                        </div>

                        <button type="button" class="btn btn-success btn-block btn-selesaikan">
                            <i class="icon-copy bi bi-check2-circle mr-1"></i>✅ Tandai Selesai
                        </button>
                    </form>

                    <hr style="margin: 20px 0;">

                    <div style="font-size:12px; color:#94a3b8; word-break: break-all;">
                        <strong><i class="icon-copy bi bi-device-ssd mr-1"></i>GUID Perangkat:</strong><br>
                        <span style="font-family:monospace; font-size: 11px;">{{ $alarm->panicButton->GUID }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lightbox --}}
        <div id="lightbox" onclick="closeLightbox()">
            <span class="lb-close" onclick="closeLightbox()">✕</span>
            <img id="lbImg" src="" alt="Foto Dokumentasi">
            <div class="lb-caption" id="lbCaption"></div>
        </div>

        <script>
            const startTime = {{ $alarm->updated_at->timestamp }};

            function updateTimer() {
                const diff = Math.floor(Date.now() / 1000) - startTime;
                const h = String(Math.floor(diff / 3600)).padStart(2, '0');
                const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
                const s = String(diff % 60).padStart(2, '0');
                document.getElementById('timerBesar').textContent = `${h}:${m}:${s}`;
            }
            updateTimer();
            setInterval(updateTimer, 1000);

            @if ($alarm->lokasi)
                const LAT = {{ $alarm->lokasi->latitude }};
                const LNG = {{ $alarm->lokasi->longtitude }};
                const NAMA = @json($alarm->pelanggan->user->name);
                let mapLoaded = false;

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
                            .setView([LAT, LNG], 17);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        }).addTo(map);
                        L.marker([LAT, LNG]).addTo(map)
                            .bindPopup('<i class="icon-copy bi bi-exclamation-triangle-fill text-danger mr-1"></i>🚨 ' +
                                NAMA).openPopup();
                    };
                    document.head.appendChild(script);
                }
            @endif

            let selectedFiles = [];

            function previewFotos(input) {
                const files = Array.from(input.files);
                if (!files.length) return;

                selectedFiles = [...selectedFiles, ...files];
                renderPreview();
            }

            function renderPreview() {
                const list = document.getElementById('previewList');
                const btnUpload = document.getElementById('btnUpload');
                list.innerHTML = '';

                if (!selectedFiles.length) {
                    btnUpload.style.display = 'none';
                    return;
                }

                btnUpload.style.display = 'inline-block';

                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const item = document.createElement('div');
                        item.className = 'preview-item';
                        item.innerHTML = `
                        <img src="${e.target.result}" alt="preview">
                        <input type="text" placeholder="Keterangan (opsional)"
                               id="ket_${index}" oninput="syncKeterangan()">
                        <button type="button" class="btn-remove-preview"
                                onclick="removePreview(${index})"><i class="icon-copy bi bi-x"></i></button>
                    `;
                        list.appendChild(item);
                    };
                    reader.readAsDataURL(file);
                });

                syncFiles();
            }

            function removePreview(index) {
                selectedFiles.splice(index, 1);
                renderPreview();
            }

            function syncFiles() {
                const dt = new DataTransfer();
                selectedFiles.forEach(f => dt.items.add(f));
                document.getElementById('inputFoto').files = dt.files;
            }

            function syncKeterangan() {
                const container = document.getElementById('keteranganInputs');
                container.innerHTML = '';
                selectedFiles.forEach((_, index) => {
                    const val = document.getElementById(`ket_${index}`)?.value ?? '';
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = `keterangan[${index}]`;
                    hidden.value = val;
                    container.appendChild(hidden);
                });
            }

            document.getElementById('formUploadFoto').addEventListener('submit', function() {
                syncKeterangan();
                syncFiles();
            });

            const dropZone = document.getElementById('dropZone');
            dropZone.addEventListener('dragover', e => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });
            dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
                const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                selectedFiles = [...selectedFiles, ...files];
                renderPreview();
            });

            function openLightbox(src, caption) {
                document.getElementById('lbImg').src = src;
                document.getElementById('lbCaption').textContent = caption || '';
                document.getElementById('lightbox').classList.add('show');
            }

            function closeLightbox() {
                document.getElementById('lightbox').classList.remove('show');
            }
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeLightbox();
            });

            document.addEventListener('DOMContentLoaded', function() {
                const btnSelesaikan = document.querySelector('.btn-selesaikan');
                if (btnSelesaikan) {
                    btnSelesaikan.addEventListener('click', function() {
                        const form = document.getElementById('formSelesaikan');
                        const keterangan = form.querySelector('textarea[name="keterangan"]').value;

                        if (!keterangan.trim()) {
                            Swal.fire({
                                title: 'Keterangan Wajib Diisi!',
                                text: 'Harap isi keterangan penanganan sebelum menyelesaikan tugas.',
                                icon: 'warning',
                                confirmButtonColor: '#265ed7'
                            });
                            return;
                        }

                        Swal.fire({
                            title: 'Selesaikan Tugas?',
                            html: `Anda akan menandai tugas ini sebagai <strong>SELESAI</strong><br>Pastikan kondisi pelanggan sudah aman.`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#16a34a',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, Selesaikan!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                form.submit();
                            }
                        });
                    });
                }

                document.querySelectorAll('.btn-hapus-foto-trigger').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const form = this.closest('form');

                        Swal.fire({
                            title: 'Hapus Foto?',
                            text: 'Foto yang dihapus tidak dapat dikembalikan.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                form.submit();
                            }
                        });
                    });
                });

                setTimeout(() => {
                    document.getElementById('alert-success')?.remove();
                    document.getElementById('alert-error')?.remove();
                }, 5000);
            });
        </script>
    </div>
@endsection
