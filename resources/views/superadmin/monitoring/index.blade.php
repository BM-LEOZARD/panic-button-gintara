@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <style>
            #map {
                height: 460px;
                width: 100%;
                border-radius: 10px;
                z-index: 1;
            }

            .badge-aman {
                background: #dcfce7;
                color: #16a34a;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
                white-space: nowrap;
            }

            .badge-darurat {
                background: #fee2e2;
                color: #dc2626;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
                white-space: nowrap;
                animation: badgePulse 1.4s ease-in-out infinite;
            }

            .badge-diproses {
                background: #dbeafe;
                color: #1d4ed8;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
                white-space: nowrap;
            }

            .badge-lain {
                background: #f1f5f9;
                color: #64748b;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 12px;
                font-weight: 600;
            }

            .tab-btn {
                padding: 8px 20px;
                border-radius: 50px;
                border: none;
                cursor: pointer;
                font-size: 13px;
                font-weight: 600;
                background: rgba(0, 0, 0, .05);
                color: #64748b;
                transition: all .2s;
            }

            .tab-btn.active {
                background: #265ed7;
                color: #fff;
                box-shadow: 0 3px 10px rgba(38, 94, 215, .3);
            }

            .tab-btn.active-darurat {
                background: #dc2626;
                color: #fff;
                box-shadow: 0 3px 10px rgba(220, 38, 38, .3);
            }

            .tab-btn.active-diproses {
                background: #1d4ed8;
                color: #fff;
                box-shadow: 0 3px 10px rgba(29, 78, 216, .3);
            }

            .live-dot {
                display: inline-block;
                width: 9px;
                height: 9px;
                background: #16a34a;
                border-radius: 50%;
                animation: blink 1.4s ease-in-out infinite;
                margin-right: 5px;
            }

            .live-dot.error {
                background: #dc2626;
                animation: none;
            }

            #jamSekarang {
                font-variant-numeric: tabular-nums;
                font-weight: 600;
                color: #475569;
            }

            @keyframes blink {

                0%,
                100% {
                    opacity: 1
                }

                50% {
                    opacity: .3
                }
            }

            @keyframes badgePulse {

                0%,
                100% {
                    opacity: 1
                }

                50% {
                    opacity: .55
                }
            }

            @keyframes pulseMarkerRed {
                0% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, .7);
                }

                70% {
                    box-shadow: 0 0 0 10px rgba(220, 38, 38, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
                }
            }

            @keyframes pulseMarkerBlue {
                0% {
                    box-shadow: 0 0 0 0 rgba(29, 78, 216, .7);
                }

                70% {
                    box-shadow: 0 0 0 10px rgba(29, 78, 216, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(29, 78, 216, 0);
                }
            }
        </style>

        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0">Monitoring Panic Button</h4>
                    <small class="text-muted">
                        <span class="live-dot" id="liveDot"></span>
                        <span id="liveStatus">Menghubungkan...</span>
                        &nbsp;·&nbsp; <span id="jamSekarang">00:00:00</span>
                    </small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark" id="statSemua">—</div>
                            <div class="font-14 text-secondary weight-500">Total Perangkat</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#265ed7">
                                <i class="icon-copy fa fa-rss"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark" id="statAman">—</div>
                            <div class="font-14 text-secondary weight-500">Status Aman</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#09cc06">
                                <i class="icon-copy bi bi-shield-check mr-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark" id="statDarurat">—</div>
                            <div class="font-14 text-secondary weight-500">Sedang Darurat</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#e95959">
                                <i class="icon-copy fa fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark" id="statDiproses">—</div>
                            <div class="font-14 text-secondary weight-500">Sedang Diproses</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#00eccf">
                                <i class="icon-copy fa fa-hourglass-half"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-box mb-30">
            <div class="pd-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="text-blue h4 mb-0">Peta Lokasi Panic Button</h4>
                    <small class="text-muted">Klik marker untuk detail perangkat</small>
                </div>
                <div style="display:flex; gap:12px; font-size:12px; align-items:center; color:#64748b;">
                    <span><i class="bi bi-circle-fill text-success mr-1" style="font-size:9px;"></i>Aman</span>
                    <span><i class="bi bi-circle-fill text-danger mr-1" style="font-size:9px;"></i>Darurat</span>
                    <span><i class="bi bi-circle-fill mr-1" style="font-size:9px;color:#1d4ed8;"></i>Diproses</span>
                </div>
            </div>
            <div class="pb-20 pl-20 pr-20">
                <div id="map"></div>
            </div>
        </div>

        <div class="card-box mb-30">
            <div class="pd-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="text-blue h4 mb-0">Daftar Perangkat Panic Button</h4>
                <div class="d-flex" style="gap:8px; flex-wrap:wrap;">
                    <button class="tab-btn active" onclick="filterTabel('semua', this)">
                        <i class="bi bi-list-ul mr-1"></i>Semua
                    </button>
                    <button class="tab-btn" onclick="filterTabel('aman', this)">
                        <i class="bi bi-shield-check mr-1"></i>Aman
                    </button>
                    <button class="tab-btn" onclick="filterTabel('darurat', this)">
                        <i class="bi bi-exclamation-triangle-fill mr-1"></i>Darurat
                    </button>
                    <button class="tab-btn" onclick="filterTabel('diproses', this)">
                        <i class="bi bi-hourglass-split mr-1"></i>Diproses
                    </button>
                </div>
            </div>
            <div class="pb-20 pl-20 pr-20">
                <table class="monitoring-table table nowrap" id="tabelMonitoring" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>GUID</th>
                            <th>DisID</th>
                            <th>Latitude</th>
                            <th>Longtitude</th>
                            <th>State</th>
                            <th>Alarm Terakhir</th>
                        </tr>
                    </thead>
                    <tbody id="tabelBody">
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="bi bi-hourglass-split mr-1"></i> Memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const POLL_URL = "{{ route('superadmin.monitoring.poll') }}";
            const POLL_INTERVAL = 10000;
            const WILAYAH_DATA = @json($wilayah);

            function mulaiJam() {
                function tick() {
                    var now = new Date();
                    var h = String(now.getHours()).padStart(2, '0');
                    var m = String(now.getMinutes()).padStart(2, '0');
                    var s = String(now.getSeconds()).padStart(2, '0');
                    document.getElementById('jamSekarang').textContent = h + ':' + m + ':' + s;
                }
                tick();
                setInterval(tick, 1000);
            }
            mulaiJam();

            const mapUtama = L.map('map').setView([-6.732064, 108.552273], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapUtama);

            WILAYAH_DATA.forEach(function(w) {
                L.circle([w.latitude, w.longtitude], {
                    radius: w.radius_meter,
                    color: '#265ed7',
                    weight: 1.8,
                    dashArray: '6,5',
                    fill: false,
                    interactive: false,
                }).addTo(mapUtama);

                L.marker([w.latitude, w.longtitude], {
                    icon: L.divIcon({
                        className: '',
                        html: '<div style="background:rgba(37,94,215,.12);color:#265ed7;' +
                            'font-size:11px;font-weight:700;padding:3px 8px;border-radius:6px;' +
                            'border:1px solid #265ed7;white-space:nowrap;">' + w.nama + '</div>',
                        iconAnchor: [0, 0],
                    }),
                    interactive: false,
                    zIndexOffset: -1000,
                }).addTo(mapUtama);
            });

            var activeMarkers = {};
            var filterMode = 'semua';
            var mapFitted = false;
            var dtInstance = null;
            var prevDaruratCount = 0;
            var isPolling = false;

            function makeIcon(state) {
                var color, size, pulse;
                if (state === 'Aman') {
                    color = '#16a34a';
                    size = 18;
                    pulse = '';
                } else if (state === 'Darurat') {
                    color = '#dc2626';
                    size = 22;
                    pulse = 'animation:pulseMarkerRed 1.2s infinite;';
                } else if (state === 'Diproses') {
                    color = '#1d4ed8';
                    size = 22;
                    pulse = 'animation:pulseMarkerBlue 1.2s infinite;';
                } else {
                    color = '#94a3b8';
                    size = 18;
                    pulse = '';
                }
                return L.divIcon({
                    className: '',
                    html: '<div style="width:' + size + 'px;height:' + size + 'px;border-radius:50%;' +
                        'background:' + color + ';border:3px solid #fff;' +
                        'box-shadow:0 2px 6px rgba(0,0,0,.3);' + pulse + '"></div>',
                    iconSize: [size, size],
                    iconAnchor: [size / 2, size / 2],
                });
            }

            function makePopup(pb) {
                var stateHtml;
                if (pb.state === 'Aman')
                    stateHtml =
                    '<span style="color:#16a34a;font-weight:700;"><i class="bi bi-shield-check mr-1"></i>Aman</span>';
                else if (pb.state === 'Darurat')
                    stateHtml =
                    '<span style="color:#dc2626;font-weight:700;"><i class="bi bi-exclamation-triangle-fill mr-1"></i>DARURAT!</span>';
                else if (pb.state === 'Diproses')
                    stateHtml =
                    '<span style="color:#1d4ed8;font-weight:700;"><i class="bi bi-hourglass-split mr-1"></i>Diproses</span>';
                else
                    stateHtml = '<span style="color:#64748b;">' + pb.state + '</span>';

                return '<div style="min-width:220px;font-size:13px;line-height:1.8;">' +
                    '<span style="color:#265ed7;font-weight:600;font-size:11px;font-family:monospace;">' + pb.guid +
                    '</span><br>' +
                    '<hr style="margin:6px 0;">' +
                    '<b>DisID:</b> ' + pb.disid + '<br>' +
                    '<b>Wilayah:</b> ' + pb.wilayah + ' <small style="color:#265ed7;">(' + pb.kode_wilayah + ')</small><br>' +
                    '<b>Blok/No:</b> ' + pb.blok + '<br>' +
                    '<b>State:</b> ' + stateHtml +
                    '</div>';
            }

            function updateMarkers(list) {
                var seen = {},
                    allCoords = [];

                list.forEach(function(pb) {
                    if (!pb.lat || !pb.lng) return;
                    seen[pb.id] = true;
                    allCoords.push([pb.lat, pb.lng]);

                    if (activeMarkers[pb.id]) {
                        activeMarkers[pb.id].setIcon(makeIcon(pb.state));
                        activeMarkers[pb.id].setPopupContent(makePopup(pb));
                    } else {
                        activeMarkers[pb.id] = L.marker([pb.lat, pb.lng], {
                                icon: makeIcon(pb.state)
                            })
                            .addTo(mapUtama).bindPopup(makePopup(pb));
                    }
                });

                Object.keys(activeMarkers).forEach(function(id) {
                    if (!seen[id]) {
                        mapUtama.removeLayer(activeMarkers[id]);
                        delete activeMarkers[id];
                    }
                });

                if (!mapFitted && allCoords.length > 0) {
                    mapFitted = true;
                    mapUtama.fitBounds(allCoords, {
                        padding: [40, 40]
                    });
                }
            }

            function makeBadge(state) {
                if (state === 'Aman') return '<span class="badge-aman"><i class="bi bi-shield-check mr-1"></i>Aman</span>';
                if (state === 'Darurat')
                    return '<span class="badge-darurat"><i class="bi bi-exclamation-triangle-fill mr-1"></i>Darurat</span>';
                if (state === 'Diproses')
                    return '<span class="badge-diproses"><i class="bi bi-hourglass-split mr-1"></i>Diproses</span>';
                return '<span class="badge-lain">' + state + '</span>';
            }

            function updateTabel(list) {
                var rows = '';
                if (list.length === 0) {
                    rows = '<tr><td colspan="7" class="text-center text-muted">Belum ada perangkat panic button.</td></tr>';
                } else {
                    list.forEach(function(pb, i) {
                        var alarmCell = pb.alarm_waktu ?
                            pb.alarm_waktu + '<br><small style="color:' +
                            (pb.alarm_status === 'Selesai' ? '#16a34a' : '#dc2626') + ';">' + pb.alarm_status +
                            '</small>' :
                            '<span class="text-muted">—</span>';

                        var latCell = pb.lat ?
                            '<span style="font-family:monospace;font-size:12px;">' + parseFloat(pb.lat).toFixed(6) +
                            '</span>' :
                            '<span class="text-muted">—</span>';

                        var lngCell = pb.lng ?
                            '<span style="font-family:monospace;font-size:12px;">' + parseFloat(pb.lng).toFixed(6) +
                            '</span>' :
                            '<span class="text-muted">—</span>';

                        rows += '<tr data-state="' + (pb.state || '').toLowerCase() + '">' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td><span style="font-family:monospace;font-size:12px;color:#265ed7;">' + pb.guid +
                            '</span></td>' +
                            '<td><span style="font-family:monospace;font-weight:600;">' + pb.disid + '</span></td>' +
                            '<td>' + latCell + '</td>' +
                            '<td>' + lngCell + '</td>' +
                            '<td>' + makeBadge(pb.state) + '</td>' +
                            '<td style="font-size:12px;color:#64748b;">' + alarmCell + '</td>' +
                            '</tr>';
                    });
                }

                if (!dtInstance) {
                    document.getElementById('tabelBody').innerHTML = rows;
                    initDataTable();
                } else {
                    dtInstance.clear();
                    dtInstance.rows.add($(rows));
                    dtInstance.draw(false);
                }

                applyFilter();
            }

            function initDataTable() {
                if (typeof $.fn.DataTable === 'undefined' || dtInstance) return;
                dtInstance = $('#tabelMonitoring').DataTable({
                    responsive: true,
                    destroy: false,
                    pageLength: 25,
                    columnDefs: [{
                        targets: '_all',
                        defaultContent: ''
                    }],
                    language: {
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        zeroRecords: 'Data tidak ditemukan',
                        paginate: {
                            previous: 'Sebelumnya',
                            next: 'Berikutnya'
                        },
                    },
                });
            }

            function updateStats(data) {
                document.getElementById('statSemua').textContent = data.total_semua;
                document.getElementById('statAman').textContent = data.total_aman;
                document.getElementById('statDarurat').textContent = data.total_darurat;
                document.getElementById('statDiproses').textContent = data.total_diproses;
            }

            function notifyDarurat(jumlah) {
                if (!('Notification' in window)) return;
                if (Notification.permission === 'granted') {
                    new Notification('DARURAT! Panic Button Aktif', {
                        body: jumlah + ' perangkat sedang dalam kondisi DARURAT.',
                        icon: '/asset/logo.png',
                    });
                } else if (Notification.permission !== 'denied') {
                    Notification.requestPermission().then(function(p) {
                        if (p === 'granted') notifyDarurat(jumlah);
                    });
                }
            }

            function filterTabel(mode, btn) {
                filterMode = mode;
                document.querySelectorAll('.tab-btn').forEach(function(b) {
                    b.classList.remove('active', 'active-darurat', 'active-diproses');
                });
                if (mode === 'darurat') btn.classList.add('active-darurat');
                else if (mode === 'diproses') btn.classList.add('active-diproses');
                else btn.classList.add('active');
                applyFilter();
            }

            function applyFilter() {
                document.querySelectorAll('#tabelBody tr[data-state]').forEach(function(row) {
                    var state = row.getAttribute('data-state');
                    row.style.display = (filterMode === 'semua' || state === filterMode) ? '' : 'none';
                });
            }

            function poll() {
                if (isPolling) return Promise.resolve();
                isPolling = true;

                return fetch(POLL_URL, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
                    })
                    .then(function(res) {
                        if (res.redirected || res.url.includes('/login')) {
                            window.location.reload();
                            return null;
                        }
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        return res.json();
                    })
                    .then(function(data) {
                        if (!data) return;
                        document.getElementById('liveDot').classList.remove('error');
                        document.getElementById('liveStatus').textContent = 'Live';
                        updateStats(data);
                        updateMarkers(data.panic_buttons);
                        updateTabel(data.panic_buttons);
                        if (data.total_darurat > prevDaruratCount) notifyDarurat(data.total_darurat);
                        prevDaruratCount = data.total_darurat;
                    })
                    .catch(function() {
                        document.getElementById('liveDot').classList.add('error');
                        document.getElementById('liveStatus').textContent = 'Koneksi terputus, mencoba ulang...';
                    })
                    .finally(function() {
                        isPolling = false;
                        setTimeout(poll, POLL_INTERVAL);
                    });
            }

            if (typeof window.Echo !== 'undefined') {
                window.Echo.private('superadmin')
                    .listen('.panic.triggered', function() {
                        isPolling = false;
                        poll();
                    })
                    .listen('.alarm.diproses', function() {
                        isPolling = false;
                        poll();
                    })
                    .listen('.alarm.selesai', function() {
                        isPolling = false;
                        poll();
                    });
            }

            poll();
        </script>
    </div>
@endsection
