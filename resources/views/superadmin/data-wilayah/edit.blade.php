@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            #mapEdit {
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
                    <h4 class="page-title mb-0">Edit Wilayah</h4>
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

        <form method="POST" action="{{ route('superadmin.data-wilayah.update', $wilayah->id) }}" id="formEdit">
            @csrf
            @method('PUT')

            <input type="hidden" name="latitude" id="input_lat" value="{{ old('latitude', $wilayah->latitude) }}" />
            <input type="hidden" name="longtitude" id="input_lng" value="{{ old('longtitude', $wilayah->longtitude) }}" />

            <div class="row" style="align-items: stretch;">

                {{-- ── FORM ── --}}
                <div class="col-md-5 mb-20">
                    <div class="card-box pd-20 card-equal">
                        <h5 class="text-blue mb-20">Informasi Wilayah</h5>

                        <div class="form-group">
                            <label>Nama Wilayah <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="inputNama" class="form-control"
                                value="{{ old('nama', $wilayah->nama) }}" required />
                        </div>

                        <div class="form-group">
                            <label>Kode Wilayah <span class="text-danger">*</span></label>
                            <input type="text" name="kode_wilayah" id="kode_wilayah" class="form-control"
                                value="{{ old('kode_wilayah', $wilayah->kode_wilayah) }}" maxlength="20" required />
                            <small class="text-muted">Digunakan sebagai segmen GUID panic button</small>
                        </div>

                        <div class="form-group">
                            <label>Radius (meter) <span class="text-danger">*</span></label>
                            <input type="number" name="radius_meter" id="radius_meter" class="form-control"
                                value="{{ old('radius_meter', $wilayah->radius_meter) }}" min="50" max="10000"
                                required />
                            <small class="text-muted">Min: 50 m — Max: 10.000 m</small>
                        </div>

                        <div class="form-group">
                            <label>Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $wilayah->alamat) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end mt-auto pt-20">
                            <a href="{{ route('superadmin.data-wilayah.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="button" class="btn btn-primary" id="btnUpdate">
                                Perbarui Wilayah
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ── MAP ── --}}
                <div class="col-md-7 mb-20">
                    <div class="card-box pd-20 card-equal">
                        <h5 class="text-blue mb-10">Ubah Titik Koordinat</h5>
                        <small class="text-muted d-block mb-10">
                            Klik pada peta untuk mengubah koordinat. Lingkaran biru menunjukkan radius wilayah.
                        </small>
                        <div class="map-wrapper">
                            <div id="mapEdit"></div>
                        </div>
                        <div class="coord-info" id="coordInfo">
                            <i class="bi bi-geo-alt-fill mr-1"></i> Lat: <b>{{ $wilayah->latitude }}</b> &nbsp;|&nbsp; Lng:
                            <b>{{ $wilayah->longtitude }}</b>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const currentLat = {{ $wilayah->latitude }};
            const currentLng = {{ $wilayah->longtitude }};
            const currentRadius = {{ $wilayah->radius_meter }};
            const currentNama = @json($wilayah->nama);

            const mapEdit = L.map('mapEdit').setView([currentLat, currentLng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapEdit);

            let marker = null;
            let circle = null;

            setMarker(currentLat, currentLng, false);

            mapEdit.on('click', function(e) {
                setMarker(e.latlng.lat, e.latlng.lng, true);
            });

            document.getElementById('radius_meter').addEventListener('input', function() {
                if (circle) circle.setRadius(parseInt(this.value) || 100);
            });

            function setMarker(lat, lng, isNew) {
                const radius = parseInt(document.getElementById('radius_meter').value) || currentRadius;

                document.getElementById('input_lat').value = parseFloat(lat).toFixed(8);
                document.getElementById('input_lng').value = parseFloat(lng).toFixed(8);
                document.getElementById('coordInfo').innerHTML =
                    `<i class="bi bi-geo-alt-fill mr-1"></i> Lat: <b>${parseFloat(lat).toFixed(8)}</b> &nbsp;|&nbsp; Lng: <b>${parseFloat(lng).toFixed(8)}</b>
                 ${isNew ? '&nbsp;<span style="color:#22c55e; font-size:12px;"><i class="bi bi-check-circle-fill mr-1"></i>Diperbarui</span>' : ''}`;

                if (marker) marker.remove();
                if (circle) circle.remove();

                marker = L.marker([lat, lng]).addTo(mapEdit)
                    .bindPopup(isNew ? 'Titik diperbarui' : currentNama)
                    .openPopup();

                circle = L.circle([lat, lng], {
                    radius: radius,
                    color: '#265ed7',
                    fillColor: '#265ed7',
                    fillOpacity: 0.1,
                    weight: 2,
                }).addTo(mapEdit);
            }

            document.getElementById('kode_wilayah').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            document.getElementById('btnUpdate').addEventListener('click', function() {
                const form = document.getElementById('formEdit');
                const nama = document.getElementById('inputNama').value.trim();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: 'Perbarui Wilayah?',
                    html: `Data wilayah <strong>${nama}</strong> akan disimpan dengan perubahan terbaru.<br>
                       <small style="color:#64748b;">Pastikan titik koordinat dan radius pada peta sudah sesuai.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#265ed7',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-floppy-fill mr-1"></i> Ya, Perbarui',
                    cancelButtonText: 'Cek Lagi',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memperbarui...',
                            text: 'Sedang menyimpan perubahan data wilayah.',
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
