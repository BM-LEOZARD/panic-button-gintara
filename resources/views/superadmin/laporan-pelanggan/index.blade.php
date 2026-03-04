@extends('layouts.app')

@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">

        <style>
            .badge-menunggu {
                background: #fef9c3;
                color: #a16207;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 11px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            .badge-diproses {
                background: #dbeafe;
                color: #1d4ed8;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 11px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            .badge-selesai {
                background: #dcfce7;
                color: #16a34a;
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 11px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }

            .badge-wilayah {
                background: #e7ebf5;
                color: #265ed7;
                padding: 3px 10px;
                border-radius: 50px;
                font-size: 11px;
                font-weight: 600;
            }

            .filter-bar {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 14px;
                padding: 14px 20px;
                margin-bottom: 20px;
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                align-items: flex-end;
            }

            .filter-group {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .filter-group label {
                font-size: 11px;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: .5px;
            }

            .filter-group select {
                padding: 7px 10px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                font-size: 13px;
                color: #1e293b;
                outline: none;
                transition: .2s;
                background: #f8fafc;
                cursor: pointer;
            }

            .filter-group select:focus {
                border-color: #265ed7;
                background: #fff;
            }

            .section-title {
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
                color: #64748b;
                margin-bottom: 16px;
                padding-bottom: 8px;
                border-bottom: 2px solid #f1f5f9;
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
            }

            .empty-state {
                text-align: center;
                padding: 60px 20px;
                color: #94a3b8;
            }

            tr.row-menunggu td {
                background: #fffbeb;
            }

            tr.row-diproses td {
                background: #eff6ff;
            }

            @media(max-width:768px) {
                .filter-bar {
                    flex-direction: column;
                }
            }
        </style>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" id="alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="page-header mb-20">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="page-title mb-0">
                        <i class="icon-copy bi bi-file-earmark-bar-graph-fill mr-2"></i>Laporan Pelanggan
                    </h4>
                    <small class="text-muted">
                        <i class="icon-copy bi bi-calendar mr-1"></i>
                        {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                        &nbsp;·&nbsp;
                        <i class="icon-copy bi bi-collection mr-1"></i>Data alarm + admin penanganan
                    </small>
                </div>
            </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('superadmin.laporan-pelanggan.index') }}" id="filterForm">
            <div class="filter-bar">
                <div class="filter-group">
                    <label><i class="icon-copy bi bi-calendar-month mr-1"></i>Bulan</label>
                    <select name="bulan" onchange="document.getElementById('filterForm').submit()">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($bulan == $m)>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="icon-copy bi bi-calendar-year mr-1"></i>Tahun</label>
                    <select name="tahun" onchange="document.getElementById('filterForm').submit()">
                        @foreach ($tahunList as $t)
                            <option value="{{ $t }}" @selected($tahun == $t)>{{ $t }}</option>
                        @endforeach
                        @if ($tahunList->isEmpty())
                            <option value="{{ now()->year }}" selected>{{ now()->year }}</option>
                        @endif
                    </select>
                </div>
            </div>
        </form>

        {{-- TABEL --}}
        <div class="card-box pd-20">
            <div class="section-title">
                <i class="icon-copy bi bi-table mr-2"></i>Detail Laporan
                @if ($laporan->count() > 0)
                    <span
                        style="background:#e0f2fe; color:#0369a1; border-radius:50px; padding:2px 10px; font-size:11px; margin-left:4px;">
                        <i class="icon-copy bi bi-database mr-1"></i>{{ $laporan->count() }} data
                    </span>
                @endif
                <span style="margin-left:auto; font-size:11px; color:#94a3b8; font-weight:400; font-style:italic;">
                    Data per bulan yang dipilih
                </span>
            </div>

            @if ($laporan->count() > 0)
                <div class="table-responsive">
                    <table class="data-table table nowrap" style="font-size:13px;">
                        <thead>
                            <tr>
                                <th style="width:36px;">#</th>
                                <th>Pelanggan</th>
                                <th>Wilayah</th>
                                <th>Admin Penanganan</th>
                                <th>Waktu Trigger</th>
                                <th>Waktu Selesai</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan as $i => $r)
                                @php
                                    $tTrigger = $r->getRawOriginal('waktu_trigger')
                                        ? \Carbon\Carbon::parse($r->getRawOriginal('waktu_trigger'))
                                        : null;
                                    $tSelesai = $r->getRawOriginal('waktu_selesai')
                                        ? \Carbon\Carbon::parse($r->getRawOriginal('waktu_selesai'))
                                        : null;
                                    $durasi =
                                        $tTrigger && $tSelesai
                                            ? max(0, $tSelesai->timestamp - $tTrigger->timestamp)
                                            : null;
                                    if ($durasi !== null) {
                                        $jam = intdiv($durasi, 3600);
                                        $menit = intdiv($durasi % 3600, 60);
                                        $detik = $durasi % 60;
                                        if ($jam > 0) {
                                            $durasiTeks = $jam . ' jam' . ($menit > 0 ? ' ' . $menit . ' menit' : '');
                                        } elseif ($menit > 0) {
                                            $durasiTeks =
                                                $menit . ' menit' . ($detik > 0 ? ' ' . $detik . ' detik' : '');
                                        } else {
                                            $durasiTeks = $detik . ' detik';
                                        }
                                        $durasiWarna =
                                            $durasi > 3600 ? '#dc2626' : ($durasi > 1800 ? '#d97706' : '#16a34a');
                                    }
                                    $rowClass = match ($r->status) {
                                        'Menunggu' => 'row-menunggu',
                                        'Diproses' => 'row-diproses',
                                        default => '',
                                    };
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td style="color:#94a3b8; font-size:11px;">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="weight-600" style="font-size:13px;">{{ $r->pelanggan->user->name }}
                                        </div>
                                        <div style="font-size:11px; color:#94a3b8;">
                                            <i
                                                class="icon-copy bi bi-telephone-fill mr-1"></i>{{ $r->pelanggan->user->no_hp }}
                                        </div>
                                        <div style="font-size:11px; color:#94a3b8;">
                                            <i class="icon-copy bi bi-house-door-fill mr-1"></i>
                                            Blok {{ $r->panicButton->GetBlockID }}/{{ $r->panicButton->GetNumber }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-wilayah">{{ $r->panicButton->wilayah->nama }}</span>
                                        <div style="font-size:10px; color:#94a3b8; margin-top:3px;">
                                            {{ $r->panicButton->wilayah->kode_wilayah }}</div>
                                    </td>
                                    <td>
                                        @if ($r->ditangani_oleh)
                                            <div class="weight-600" style="font-size:13px; color:#1d4ed8;">
                                                <i
                                                    class="icon-copy bi bi-person-badge-fill mr-1"></i>{{ $r->ditangani_oleh }}
                                            </div>
                                            @if ($r->admin)
                                                <div style="font-size:11px; color:#94a3b8;">
                                                    <i
                                                        class="icon-copy bi bi-telephone-fill mr-1"></i>{{ $r->admin->no_hp }}
                                                </div>
                                            @endif
                                        @else
                                            <span style="color:#cbd5e1; font-size:12px; font-style:italic;">Belum
                                                diambil</span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;">
                                        @if ($tTrigger)
                                            <i class="icon-copy bi bi-exclamation-triangle-fill text-danger mr-1"></i>
                                            {{ $tTrigger->format('d M Y') }}<br>
                                            <span style="color:#64748b;">{{ $tTrigger->format('H:i:s') }} WIB</span>
                                        @else
                                            <span style="color:#cbd5e1;">—</span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;">
                                        @if ($tSelesai)
                                            <i class="icon-copy bi bi-check-circle-fill text-success mr-1"></i>
                                            {{ $tSelesai->format('d M Y') }}<br>
                                            <span style="color:#64748b;">{{ $tSelesai->format('H:i:s') }} WIB</span>
                                        @else
                                            <span style="color:#cbd5e1;">—</span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;">
                                        @if ($durasi !== null)
                                            <span style="color:{{ $durasiWarna }}; font-weight:600;">
                                                <i class="icon-copy bi bi-hourglass-split mr-1"></i>{{ $durasiTeks }}
                                            </span>
                                        @else
                                            <span style="color:#cbd5e1;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($r->status === 'Menunggu')
                                            <span class="badge-menunggu"><i
                                                    class="icon-copy bi bi-clock-fill"></i>Menunggu</span>
                                        @elseif ($r->status === 'Diproses')
                                            <span class="badge-diproses"><i
                                                    class="icon-copy bi bi-gear-fill"></i>Diproses</span>
                                        @else
                                            <span class="badge-selesai"><i
                                                    class="icon-copy bi bi-check-circle-fill"></i>Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('superadmin.laporan-pelanggan.show', $r->id) }}"
                                            class="btn btn-xs btn-primary" style="white-space:nowrap;">
                                            <i class="icon-copy bi bi-eye-fill mr-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div style="font-size:52px; margin-bottom:12px;"><i class="icon-copy bi bi-inbox"></i></div>
                    <div style="font-weight:600; font-size:15px;">Tidak ada data</div>
                    <div style="font-size:13px; margin-top:6px;">
                        Belum ada alarm di <strong>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
                            {{ $tahun }}</strong>
                    </div>
                </div>
            @endif
        </div>

        <script>
            setTimeout(function() {
                const el = document.getElementById('alert-success');
                if (el) {
                    el.classList.remove('show');
                    el.classList.add('fade');
                    setTimeout(() => el.remove(), 300);
                }
            }, 5000);
        </script>
    </div>
@endsection
