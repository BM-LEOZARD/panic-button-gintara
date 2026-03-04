<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AlarmPanicButton;
use Illuminate\Http\Request;

class LaporanPelangganController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $laporan = AlarmPanicButton::with([
            'pelanggan.user',
            'panicButton.wilayah',
            'admin',
            'lokasi',
            'dokumenFoto',
        ])
            ->whereYear('waktu_trigger', $tahun)
            ->whereMonth('waktu_trigger', $bulan)
            ->latest('waktu_trigger')
            ->get();

        $totalAlarm = $laporan->count();

        $tahunList = AlarmPanicButton::selectRaw('YEAR(waktu_trigger) as tahun')
            ->whereNotNull('waktu_trigger')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('superadmin.laporan-pelanggan.index', compact(
            'laporan',
            'bulan',
            'tahun',
            'totalAlarm',
            'tahunList',
        ));
    }

    public function show($id)
    {
        $alarm = AlarmPanicButton::with([
            'pelanggan.user',
            'pelanggan.gambar',
            'panicButton.wilayah',
            'admin',
            'lokasi',
            'dokumenFoto',
        ])->findOrFail($id);

        return view('superadmin.laporan-pelanggan.show', compact('alarm'));
    }
}