@extends('layouts.app')

@push('before-scripts')
    <script>
        window.KinerjaData = {
            categories: @json($categories),
            series: @json($series),
            summaryList: @json($summaryList),
            currentYear: {{ $currentYear }},
            currentMonth: {{ $currentMonth }},
        };
    </script>
@endpush

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">

        <div class="title pb-20">
            <h2 class="h3 mb-0">Kinerja Admin</h2>
            <p class="text-secondary font-14 mb-0">Jumlah alarm diselesaikan per admin</p>
        </div>

        <div class="card-box mb-20 pd-20">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-20">
                <div class="h5 mb-0">Alarm Diselesaikan per Hari</div>
                <select id="filter-bulan-kinerja" class="form-control form-control-sm" style="width:auto; min-width:160px;">
                    @foreach ($availableMonths as $bulan)
                        <option value="{{ $bulan['value'] }}"
                            {{ $bulan['value'] === $currentYear . '-' . $currentMonth ? 'selected' : '' }}>
                            {{ $bulan['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="kinerja-chart"></div>
        </div>

        <div class="card-box pb-10">
            <div class="h5 pd-20 mb-0">Ringkasan Kinerja Bulan Ini</div>
            <table id="tabel-kinerja" class="table nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Total Alarm Diselesaikan</th>
                        <th>Rata-rata Waktu Penanganan</th>
                    </tr>
                </thead>
                <tbody id="tbody-kinerja">
                    @forelse($summaryList as $row)
                        <tr>
                            <td>
                                <span class="d-inline-block mr-2"
                                    style="width:12px;height:12px;border-radius:3px;
                                    background:{{ $row['color'] }};vertical-align:middle;"></span>
                                <span class="weight-600">{{ $row['name'] }}</span>
                            </td>
                            <td>{{ $row['total'] }} alarm</td>
                            <td>
                                @if ($row['avg_menit'] > 0)
                                    @php
                                        $jam = floor($row['avg_menit'] / 60);
                                        $menit = $row['avg_menit'] % 60;
                                    @endphp
                                    {{ $jam > 0 ? $jam . ' jam ' : '' }}{{ $menit }} menit
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-20">
                                Belum ada data kinerja bulan ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection

@push('dashboard-script')
    <script src="{{ asset('asset/dashboard/src/plugins/highcharts-6.0.7/code/highcharts.js') }}"></script>
    <script src="{{ asset('asset/dashboard/vendors/scripts/kinerja-admin.js') }}"></script>
@endpush
