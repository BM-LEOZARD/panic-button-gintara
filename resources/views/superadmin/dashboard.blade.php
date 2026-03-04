@extends('layouts.app')

@push('before-scripts')
    <script>
        window.DashboardData = {
            trendLabels: @json($trendLabels),
            trendData: @json($trendData),
            alarmMenungguTotal: {{ $alarmMenungguTotal }},
            alarmDiprosesTotal: {{ $alarmDiprosesTotal }},
            alarmSelesaiTotal: {{ $alarmSelesaiTotal }},
            totalSemuaAlarm: {{ $totalSemuaAlarm }},
        };
    </script>
@endpush

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">

        <div class="title pb-20">
            <h2 class="h3 mb-0">Panic Button Overview</h2>
        </div>

        <div class="row pb-10">
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">{{ $totalAlarmHariIni }}</div>
                            <div class="font-14 text-secondary weight-500">Alarm Hari Ini</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#e95959">
                                <i class="icon-copy fa fa-bell"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">{{ $totalPelanggan }}</div>
                            <div class="font-14 text-secondary weight-500">Total Pelanggan</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#00eccf">
                                <i class="icon-copy fa fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">{{ $totalAdmin }}</div>
                            <div class="font-14 text-secondary weight-500">Total Admin</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#265ed7">
                                <i class="icon-copy fa fa-shield"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">{{ $totalPendaftar }}</div>
                            <div class="font-14 text-secondary weight-500">Pendaftar Baru</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#09cc06">
                                <i class="icon-copy fa fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row pb-10">

            <div class="col-md-8 mb-20">
                <div class="card-box height-100-p pd-20">
                    <div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
                        <div class="h5 mb-md-0">Tren Pendaftaran</div>
                        <select id="filter-bulan" class="form-control form-control-sm" style="width:auto; min-width:160px;">
                            @foreach ($availableMonths as $bulan)
                                <option value="{{ $bulan['value'] }}"
                                    {{ $bulan['value'] === $currentYear . '-' . now()->month ? 'selected' : '' }}>
                                    {{ $bulan['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="trend-pendaftaran-chart"></div>
                </div>
            </div>

            <div class="col-md-4 mb-20">
                <div class="card-box height-100-p pd-20">
                    <div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
                        <div class="h5 mb-md-0">Sebaran Alarm</div>
                        <div class="font-12 text-secondary">Total Keseluruhan</div>
                    </div>
                    <div id="alarm-status-chart"></div>
                </div>
            </div>

        </div>

        <div class="card-box pb-10">
            <div class="h5 pd-20 mb-0">Alarm Terbaru</div>
            <table id="tabel-alarm-terbaru" class="table nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th class="table-plus">Pelanggan</th>
                        <th>No. HP</th>
                        <th>Blok/No</th>
                        <th>Wilayah</th>
                        <th>Ditangani Oleh</th>
                        <th>Waktu Trigger</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentAlarms as $alarm)
                        <tr>
                            <td class="table-plus">
                                <div class="name-avatar d-flex align-items-center">
                                    <div class="avatar mr-2 flex-shrink-0">
                                        <div class="weight-700 text-white text-center border-radius-100"
                                            style="width:40px;height:40px;line-height:40px;font-size:16px;
                                            background:{{ $alarm->status === 'Menunggu' ? '#e95959' : ($alarm->status === 'Diproses' ? '#265ed7' : '#27ae60') }}">
                                            {{ strtoupper(substr($alarm->pelanggan->user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="txt">
                                        <div class="weight-600">{{ $alarm->pelanggan->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $alarm->pelanggan->user->no_hp }}</td>
                            <td>
                                <span class="badge badge-pill" data-bgcolor="#e7ebf5" data-color="#265ed7">
                                    {{ $alarm->panicButton->GetBlockID }}/{{ $alarm->panicButton->GetNumber }}
                                </span>
                            </td>
                            <td>{{ $alarm->panicButton->wilayah->nama }}</td>
                            <td>{{ $alarm->ditangani_oleh ?? '-' }}</td>
                            <td>{{ $alarm->waktu_trigger?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td>
                                @if ($alarm->status === 'Menunggu')
                                    <span class="badge badge-pill" data-bgcolor="#fde8e8"
                                        data-color="#e95959">Menunggu</span>
                                @elseif($alarm->status === 'Diproses')
                                    <span class="badge badge-pill" data-bgcolor="#e7ebf5"
                                        data-color="#265ed7">Diproses</span>
                                @else
                                    <span class="badge badge-pill" data-bgcolor="#e8f8f5"
                                        data-color="#27ae60">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('superadmin.laporan-pelanggan.show', $alarm->id) }}"
                                        data-color="#265ed7">
                                        <i class="icon-copy dw dw-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-20">
                                Belum ada data alarm
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#tabel-alarm-terbaru')) {
                $('#tabel-alarm-terbaru').DataTable({
                    scrollCollapse: false,
                    autoWidth: false,
                    responsive: true,
                    searching: false,
                    bLengthChange: false,
                    bPaginate: true,
                    bInfo: false,
                    columnDefs: [{
                        targets: 7,
                        orderable: false
                    }],
                    lengthMenu: [
                        [5, 25, 50, -1],
                        [5, 25, 50, 'All']
                    ],
                    language: {
                        info: '_START_-_END_ of _TOTAL_ entries',
                        paginate: {
                            next: '<i class="ion-chevron-right"></i>',
                            previous: '<i class="ion-chevron-left"></i>'
                        }
                    }
                });
            }
        });
    </script>
@endsection

@push('dashboard-script')
    <script src="{{ asset('asset/dashboard/vendors/scripts/dashboard-superadmin.js') }}"></script>
@endpush
