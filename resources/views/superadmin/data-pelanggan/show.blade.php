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

            .badge-darurat {
                background: #fee2e2;
                color: #dc2626;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
            }

            .badge-aman {
                background: #dcfce7;
                color: #16a34a;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
            }

            #miniMap {
                height: 220px;
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

            .device-card {
                background: #f8fafc;
                border: 1.5px solid #e2e8f0;
                border-radius: 12px;
                padding: 16px;
            }

            .guid-mono {
                font-family: 'Courier New', monospace;
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 1px;
                color: #265ed7;
                background: #e7ebf5;
                padding: 8px 12px;
                border-radius: 8px;
                word-break: break-all;
                display: block;
                margin-top: 4px;
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

        {{-- Header --}}
        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0">Detail Pelanggan</h4>
                    <small class="text-muted">{{ $pelanggan->user->name }}</small>
                </div>
                <div class="col-auto d-flex" style="gap:8px;">
                    <a href="{{ route('superadmin.data-pelanggan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- KOLOM KIRI --}}
            <div class="col-lg-7">

                {{-- Identitas --}}
                <div class="card-box pd-20 mb-20">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <div class="section-head mb-0">Identitas Pelanggan</div>
                        @if ($pelanggan->user->trashed())
                            <span class="badge-nonaktif"><i class="bi bi-circle-fill mr-1" style="font-size:8px;"></i>
                                Nonaktif</span>
                        @else
                            <span class="badge-aktif"><i class="bi bi-circle-fill mr-1" style="font-size:8px;"></i>
                                Aktif</span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Nama Lengkap</div>
                                <div class="info-value">{{ $pelanggan->user->name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Username</div>
                                <div class="info-value" style="font-family:monospace;">{{ $pelanggan->user->username }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">NIK</div>
                                <div class="info-value" style="font-family:monospace;">{{ $pelanggan->nik }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tempat, Tgl Lahir</div>
                                <div class="info-value">{{ $pelanggan->ttl }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $pelanggan->user->email }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">No. HP</div>
                                <div class="info-value">{{ $pelanggan->user->no_hp }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Jenis Kelamin</div>
                                <div class="info-value">{{ $pelanggan->user->jenis_kelamin }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Terdaftar Sejak</div>
                                <div class="info-value">{{ $pelanggan->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alamat & Lokasi --}}
                <div class="card-box pd-20 mb-20">
                    <div class="section-head">Alamat & Lokasi</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Alamat</div>
                                <div class="info-value">{{ $pelanggan->alamat }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">RT / RW</div>
                                <div class="info-value">{{ $pelanggan->RT }} / {{ $pelanggan->RW }}</div>
                            </div>
                            @if ($pelanggan->panicButton)
                                <div class="info-row">
                                    <div class="info-label">Blok / No. Rumah</div>
                                    <div class="info-value">
                                        {{ $pelanggan->panicButton->GetBlockID }} /
                                        {{ $pelanggan->panicButton->GetNumber }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <div class="info-label">Desa</div>
                                <div class="info-value">{{ $pelanggan->desa }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Kelurahan</div>
                                <div class="info-value">{{ $pelanggan->kelurahan }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Kecamatan</div>
                                <div class="info-value">{{ $pelanggan->kecamatan }}</div>
                            </div>
                            @if ($pelanggan->panicButton?->lokasi)
                                <div class="info-row">
                                    <div class="info-label">Koordinat</div>
                                    <div class="info-value" style="font-family:monospace; font-size:12px;">
                                        {{ $pelanggan->panicButton->lokasi->latitude }},
                                        {{ $pelanggan->panicButton->lokasi->longtitude }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Peta --}}
                    @if ($pelanggan->panicButton?->lokasi)
                        <div style="margin-top:16px;">
                            <div id="miniMap" onclick="loadMap()">
                                <i class="bi bi-map mr-1"></i> Klik untuk tampilkan lokasi di peta
                            </div>
                            <small style="color:#94a3b8; font-size:11px;">Peta dimuat saat diklik.</small>
                        </div>
                    @endif
                </div>

                {{-- Foto KTP --}}
                @if ($pelanggan->gambar)
                    <div class="card-box pd-20 mb-20">
                        <div class="section-head">Foto KTP</div>
                        <img src="{{ Storage::url($pelanggan->gambar->foto_ktp) }}" alt="KTP {{ $pelanggan->user->name }}"
                            style="max-width:100%; border-radius:10px; border:1px solid #e2e8f0;"
                            onerror="this.alt='Foto tidak tersedia';" />
                    </div>
                @endif

            </div>

            {{-- KOLOM KANAN --}}
            <div class="col-lg-5">

                {{-- Informasi Panic Button --}}
                <div class="card-box pd-20 mb-20">
                    <div class="section-head">Perangkat Panic Button</div>

                    @if ($pelanggan->panicButton)
                        @php $pb = $pelanggan->panicButton; @endphp

                        <div class="device-card mb-16">
                            <div class="info-label mb-6">GUID Perangkat</div>
                            <span class="guid-mono">{{ $pb->GUID }}</span>
                        </div>

                        <div class="info-row">
                            <div class="info-label">DisID</div>
                            <div class="info-value" style="font-family:monospace;">{{ $pb->DisID }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Blok / Nomor</div>
                            <div class="info-value">{{ $pb->GetBlockID }} / {{ $pb->GetNumber }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Wilayah</div>
                            <div class="info-value">
                                {{ $pb->wilayah->nama }}
                                <span
                                    style="font-size:11px; color:#265ed7; background:#e7ebf5; border-radius:50px; padding:2px 8px; margin-left:4px;">
                                    {{ $pb->wilayah->kode_wilayah }}
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status Perangkat</div>
                            <div class="info-value">
                                @if ($pb->state === 'Darurat')
                                    <span class="badge-darurat"><i
                                            class="bi bi-exclamation-triangle-fill mr-1"></i>Darurat</span>
                                @else
                                    <span class="badge-aman"><i class="bi bi-shield-check mr-1"></i>Aman</span>
                                @endif
                            </div>
                        </div>
                        @if ($pb->timestamp)
                            <div class="info-row">
                                <div class="info-label">Timestamp Terakhir</div>
                                <div class="info-value" style="font-size:13px;">
                                    {{ \Carbon\Carbon::parse($pb->timestamp)->format('d M Y, H:i:s') }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning" style="font-size:13px;">
                            <i class="bi bi-exclamation-triangle-fill mr-1"></i> Belum ada perangkat panic button yang
                            terdaftar.
                        </div>
                    @endif
                </div>

                {{-- Info Akun --}}
                <div class="card-box pd-20">
                    <div class="section-head">Informasi Akun</div>
                    <div class="info-row">
                        <div class="info-label">Role</div>
                        <div class="info-value">{{ $pelanggan->user->role }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status Akun</div>
                        <div class="info-value">
                            @if ($pelanggan->user->trashed())
                                <span class="badge-nonaktif">Nonaktif</span>
                                <div style="font-size:11px; color:#94a3b8; margin-top:4px;">
                                    Dinonaktifkan: {{ $pelanggan->user->deleted_at->format('d M Y, H:i') }}
                                </div>
                            @else
                                <span class="badge-aktif">Aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Akun Dibuat</div>
                        <div class="info-value">{{ $pelanggan->user->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>

            </div>
        </div>

        {{-- MODAL NONAKTIFKAN --}}
        <div class="modal fade" id="modalNonaktif" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color:#f59e0b;">Nonaktifkan Pelanggan</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        Nonaktifkan akun pelanggan <strong id="nonaktif_nama"></strong>?
                        <br><small class="text-muted">Pelanggan tidak dapat login dan panic button akan
                            dinonaktifkan.</small>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" id="formNonaktif">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Nonaktifkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL RESTORE --}}
        <div class="modal fade" id="modalRestore" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color:#16a34a;">Aktifkan Kembali</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        Aktifkan kembali akun pelanggan <strong id="restore_nama"></strong>?
                    </div>
                    <div class="modal-footer">
                        <form method="POST" id="formRestore">
                            @csrf
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Aktifkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS PERMANEN --}}
        <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Hapus Permanen</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        Hapus permanen akun pelanggan <strong id="hapus_nama"></strong>?
                        <br><small class="text-danger"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Semua data
                            termasuk panic button dan foto KTP akan dihapus permanen.</small>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" id="formHapus">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            @if ($pelanggan->panicButton?->lokasi)
                const LAT = {{ $pelanggan->panicButton->lokasi->latitude }};
                const LNG = {{ $pelanggan->panicButton->lokasi->longtitude }};
                const NAMA = @json($pelanggan->user->name);
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
                        }).setView([LAT, LNG], 16);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        }).addTo(map);
                        L.marker([LAT, LNG]).addTo(map)
                            .bindPopup('Lokasi: ' + NAMA).openPopup();
                    };
                    document.head.appendChild(script);
                }
            @endif

            function confirmNonaktif(id, nama) {
                document.getElementById('nonaktif_nama').textContent = nama;
                document.getElementById('formNonaktif').action = `/superadmin/data-pelanggan/${id}`;
                $('#modalNonaktif').modal('show');
            }

            function confirmRestore(id, nama) {
                document.getElementById('restore_nama').textContent = nama;
                document.getElementById('formRestore').action = `/superadmin/data-pelanggan/${id}/restore`;
                $('#modalRestore').modal('show');
            }

            function confirmHapus(id, nama) {
                document.getElementById('hapus_nama').textContent = nama;
                document.getElementById('formHapus').action = `/superadmin/data-pelanggan/${id}/force-delete`;
                $('#modalHapus').modal('show');
            }
        </script>
    </div>
@endsection
