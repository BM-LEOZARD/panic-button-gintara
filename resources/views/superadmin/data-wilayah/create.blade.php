@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            #mapCreate {
                height: 100%;
                min-height: 420px;
                width: 100%;
                border-radius: 10px;
                z-index: 1;
            }

            .map-wrapper {
                height: 100%;
            }

            .coord-info {
                background: #e7ebf5;
                border-radius: 8px;
                padding: 10px 14px;
                font-size: 13px;
                color: #265ed7;
                font-weight: 600;
                margin-top: 8px;
            }

            .card-equal {
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .card-equal .map-wrapper {
                flex: 1;
            }
        </style>

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h4 class="page-title mb-0">Tambah Wilayah</h4>
                </div>
                <div class="col-auto ml-auto">
                    <a href="{{ route('superadmin.data-wilayah.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-x-circle-fill mr-1"></i> {{ $error }}</div>
                @endforeach
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <form method="POST" action="{{ route('superadmin.data-wilayah.store') }}" id="formCreate">
            @csrf

            <input type="hidden" name="latitude" id="input_lat" value="{{ old('latitude') }}" />
            <input type="hidden" name="longtitude" id="input_lng" value="{{ old('longtitude') }}" />

            <div class="row" style="align-items: stretch;">

                {{-- ── FORM ── --}}
                <div class="col-md-5 mb-20">
                    <div class="card-box pd-20 card-equal">
                        <h5 class="text-blue mb-20">Informasi Wilayah</h5>

                        <div class="form-group">
                            <label>Nama Wilayah <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="inputNama" class="form-control"
                                value="{{ old('nama') }}" placeholder="contoh: Trusmiland Klayan Tahap 1" required />
                        </div>

                        <div class="form-group">
                            <label>Kode Wilayah <span class="text-danger">*</span></label>
                            <input type="text" name="kode_wilayah" id="kode_wilayah" class="form-control"
                                value="{{ old('kode_wilayah') }}" placeholder="contoh: KLYNTHP1" maxlength="20" required />
                            <small class="text-muted">Digunakan sebagai segmen GUID panic button</small>
                        </div>

                        <div class="form-group">
                            <label>Radius (meter) <span class="text-danger">*</span></label>
                            <input type="number" name="radius_meter" id="radius_meter" class="form-control"
                                value="{{ old('radius_meter', 100) }}" min="50" max="10000" required />
                            <small class="text-muted">Min: 50 m — Max: 10.000 m</small>
                        </div>

                        <div class="form-group">
                            <label>Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap wilayah" required>{{ old('alamat') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end mt-auto pt-20">
                            <a href="{{ route('superadmin.data-wilayah.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="button" class="btn btn-primary" id="btnSimpan">
                                Simpan Wilayah
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ── MAP ── --}}
                <div class="col-md-7 mb-20">
                    <div class="card-box pd-20 card-equal">
                        <h5 class="text-blue mb-10">Pilih Titik Koordinat</h5>
                        <small class="text-muted d-block mb-10">
                            Klik pada peta untuk mengisi koordinat secara otomatis. Lingkaran biru menunjukkan radius
                            wilayah.
                        </small>
                        <div class="map-wrapper">
                            <div id="mapCreate"></div>
                        </div>
                        <div class="coord-info" id="coordInfo">
                            <i class="bi bi-geo-alt-fill mr-1"></i> Belum ada titik yang dipilih. Klik pada peta.
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const mapCreate = L.map('mapCreate').setView([-6.732064, 108.552273], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapCreate);

            let marker = null;
            let circle = null;

            const oldLat = '{{ old('latitude') }}';
            const oldLng = '{{ old('longtitude') }}';
            if (oldLat && oldLng) {
                setMarker(parseFloat(oldLat), parseFloat(oldLng));
                mapCreate.setView([parseFloat(oldLat), parseFloat(oldLng)], 15);
            }

            mapCreate.on('click', function(e) {
                setMarker(e.latlng.lat, e.latlng.lng);
            });

            document.getElementById('radius_meter').addEventListener('input', function() {
                if (circle) circle.setRadius(parseInt(this.value) || 100);
            });

            function setMarker(lat, lng) {
                const radius = parseInt(document.getElementById('radius_meter').value) || 100;

                document.getElementById('input_lat').value = lat.toFixed(8);
                document.getElementById('input_lng').value = lng.toFixed(8);
                document.getElementById('coordInfo').innerHTML =
                    `<i class="bi bi-geo-alt-fill mr-1"></i> Lat: <b>${lat.toFixed(8)}</b> &nbsp;|&nbsp; Lng: <b>${lng.toFixed(8)}</b>`;

                if (marker) marker.remove();
                if (circle) circle.remove();

                marker = L.marker([lat, lng]).addTo(mapCreate)
                    .bindPopup('Titik wilayah dipilih').openPopup();

                circle = L.circle([lat, lng], {
                    radius: radius,
                    color: '#265ed7',
                    fillColor: '#265ed7',
                    fillOpacity: 0.1,
                    weight: 2,
                }).addTo(mapCreate);
            }

            document.getElementById('kode_wilayah').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            document.getElementById('btnSimpan').addEventListener('click', function() {
                const form = document.getElementById('formCreate');
                const lat = document.getElementById('input_lat').value;
                const lng = document.getElementById('input_lng').value;
                const nama = document.getElementById('inputNama').value.trim();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                if (!lat || !lng) {
                    Swal.fire({
                        title: 'Titik Koordinat Belum Dipilih!',
                        text: 'Klik pada peta untuk menentukan titik koordinat wilayah terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonColor: '#265ed7',
                        confirmButtonText: 'Oke, Mengerti',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Simpan Wilayah Baru?',
                    html: `Wilayah <strong>${nama}</strong> akan disimpan.<br>
                       <small style="color:#64748b;">Pastikan titik koordinat dan radius pada peta sudah sesuai.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#265ed7',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-floppy-fill mr-1"></i> Ya, Simpan',
                    cancelButtonText: 'Cek Lagi',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Sedang menyimpan data wilayah.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        form.submit();
                    }
                });
            });
        </script>
    </div>
@endsection
