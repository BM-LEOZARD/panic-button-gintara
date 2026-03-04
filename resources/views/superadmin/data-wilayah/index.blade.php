@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            #map {
                height: 420px;
                width: 100%;
                border-radius: 10px;
                z-index: 1;
            }

            .badge-kode {
                background-color: #e7ebf5;
                color: #265ed7;
                padding: 4px 10px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
            }

            .leaflet-popup-content b {
                color: #265ed7;
            }
        </style>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- MAP --}}
        <div class="card-box mb-30">
            <div class="pd-20 d-flex justify-content-between align-items-center">
                <h4 class="text-blue h4 mb-0">Peta Wilayah Cover</h4>
                <small class="text-muted">Klik marker untuk melihat detail wilayah</small>
            </div>
            <div class="pb-20 pl-20 pr-20">
                <div id="map"></div>
            </div>
        </div>

        {{-- TABEL --}}
        <div class="card-box mb-30">
            <div class="pd-20 d-flex justify-content-between align-items-center">
                <h4 class="text-blue h4 mb-0">Data Wilayah Cover</h4>
                <a href="{{ route('superadmin.data-wilayah.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Wilayah
                </a>
            </div>
            <div class="pb-20 pl-20 pr-20">
                <table class="data-table table nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Wilayah</th>
                            <th>Kode Wilayah</th>
                            <th>Koordinat</th>
                            <th>Radius</th>
                            <th>Alamat</th>
                            <th class="datatable-nosort">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wilayah as $i => $w)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="weight-600">{{ $w->nama }}</td>
                                <td><span class="badge-kode">{{ $w->kode_wilayah }}</span></td>
                                <td style="font-size:12px; font-family:monospace;">
                                    {{ $w->latitude }}, {{ $w->longtitude }}
                                </td>
                                <td>{{ number_format($w->radius_meter) }} m</td>
                                <td>{{ $w->alamat }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('superadmin.data-wilayah.edit', $w->id) }}" data-color="#265ed7"
                                            title="Edit">
                                            <i class="icon-copy dw dw-edit2"></i>
                                        </a>
                                        <a href="#" data-color="#e95959"
                                            onclick="confirmDelete({{ $w->id }}, '{{ addslashes($w->nama) }}')"
                                            title="Hapus">
                                            <i class="icon-copy dw dw-delete-3"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data wilayah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Hidden form hapus --}}
        <form method="POST" id="formHapus" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            setTimeout(function() {
                const alert = document.getElementById('alert-success');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);

            const wilayahData = @json($wilayah);

            const mapUtama = L.map('map').setView([-6.732064, 108.552273], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapUtama);

            wilayahData.forEach(w => {
                const mkr = L.marker([w.latitude, w.longtitude]).addTo(mapUtama);
                mkr.bindPopup(`
                <b>${w.nama}</b><br>
                <span style="color:#265ed7; font-weight:600;">${w.kode_wilayah}</span><br>
                Radius: ${w.radius_meter} m<br>
                <small>${w.alamat}</small>
            `);
                L.circle([w.latitude, w.longtitude], {
                    radius: w.radius_meter,
                    color: '#265ed7',
                    fillColor: '#265ed7',
                    fillOpacity: 0.08,
                    weight: 1.5,
                }).addTo(mapUtama);
            });

            if (wilayahData.length > 0) {
                const bounds = wilayahData.map(w => [w.latitude, w.longtitude]);
                mapUtama.fitBounds(bounds, {
                    padding: [40, 40]
                });
            }

            function confirmDelete(id, nama) {
                Swal.fire({
                    title: 'Hapus Wilayah?',
                    html: `Wilayah <strong>${nama}</strong> akan dihapus secara permanen.<br><br>
                       <span style="color:#dc2626; font-weight:600;"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Data yang sudah dihapus tidak dapat dikembalikan.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="bi bi-trash3-fill mr-1"></i> Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Sedang menghapus data wilayah.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        const form = document.getElementById('formHapus');
                        form.action = `/superadmin/data-wilayah/${id}`;
                        form.submit();
                    }
                });
            }
        </script>
    </div>
@endsection
