<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlarmPanicButton;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $bulan = now('Asia/Jakarta')->month;
        $tahun = now('Asia/Jakarta')->year;

        $laporan = AlarmPanicButton::with([
            'pelanggan.user',
            'panicButton.wilayah',
            'dokumenFoto',
        ])
            ->where('user_id', $admin->id)
            ->where('status', 'Selesai')
            ->whereMonth('waktu_selesai', $bulan)
            ->whereYear('waktu_selesai', $tahun)
            ->latest('waktu_selesai')
            ->get();

        $totalAlarm  = $laporan->count();
        $totalDurasi = $laporan->sum(function ($r) {
            if (!$r->getRawOriginal('waktu_trigger') || !$r->getRawOriginal('waktu_selesai')) return 0;
            $tTrigger = \Carbon\Carbon::parse($r->getRawOriginal('waktu_trigger'));
            $tSelesai  = \Carbon\Carbon::parse($r->getRawOriginal('waktu_selesai'));
            return max(0, $tSelesai->timestamp - $tTrigger->timestamp);
        });
        $rerataDurasi = $totalAlarm > 0 ? intdiv($totalDurasi, $totalAlarm) : 0;
        $totalFoto    = $laporan->sum(fn($r) => $r->dokumenFoto->count());

        return view('admin.laporan.index', compact(
            'laporan',
            'bulan',
            'tahun',
            'totalAlarm',
            'rerataDurasi',
            'totalFoto'
        ));
    }

    public function show($id)
    {
        $admin = Auth::user();

        $alarm = AlarmPanicButton::with([
            'pelanggan.user',
            'panicButton.wilayah',
            'lokasi',
            'dokumenFoto',
        ])
            ->where('id', $id)
            ->where('user_id', $admin->id)
            ->where('status', 'Selesai')
            ->firstOrFail();
 
        return view('admin.laporan.show', compact('alarm'));
    }
}
