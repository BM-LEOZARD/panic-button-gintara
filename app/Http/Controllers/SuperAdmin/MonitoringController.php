<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PanicButton;
use App\Models\WilayahCover;
use Illuminate\Support\Facades\Cache;

class MonitoringController extends Controller
{
    public function index()
    {
        $wilayah = WilayahCover::all();

        return view('superadmin.monitoring.index', compact('wilayah'));
    }

    public function poll()
    {
        $wilayahMap = Cache::remember('wilayah_map', 60, function () {
            return WilayahCover::select('id', 'nama', 'kode_wilayah')->get()->keyBy('id');
        });

        $panicButtons = PanicButton::select(
            'panic_button.id',
            'panic_button.pelanggan_id',
            'panic_button.wilayah_id',
            'panic_button.DisID',
            'panic_button.GUID',
            'panic_button.GetBlockID',
            'panic_button.GetNumber',
            'panic_button.state',
            'panic_button.timestamp',
        )
            ->with([
                'pelanggan:id,user_id' => [
                    'user:id,name',
                ],
                'lokasi:id,panic_button_id,latitude,longtitude',
                'alarm' => function ($q) {
                    $q->select('id', 'panic_button_id', 'waktu_trigger', 'status')
                        ->latest('id')
                        ->limit(1);
                },
            ])
            ->get();

        $data = $panicButtons->map(function ($pb) use ($wilayahMap) {
            $wilayah = $wilayahMap->get($pb->wilayah_id);
            $alarmTerakhir = $pb->alarm->first();

            if ($pb->state === 'Darurat') {
                $displayState = ($alarmTerakhir && $alarmTerakhir->status === 'Diproses')
                    ? 'Diproses'
                    : 'Darurat';
            } else {
                $displayState = 'Aman';
            }

            return [
                'id' => $pb->id,
                'guid' => $pb->GUID ?? '-',
                'disid' => $pb->DisID  ?? '-',
                'pelanggan' => optional(optional($pb->pelanggan)->user)->name ?? '-',
                'wilayah' => optional($wilayah)->nama ?? '-',
                'kode_wilayah' => optional($wilayah)->kode_wilayah ?? '',
                'blok' => ($pb->GetBlockID ?? '-') . ' / ' . ($pb->GetNumber ?? '-'),
                'state' => $displayState,
                'lat' => optional($pb->lokasi)->latitude,
                'lng' => optional($pb->lokasi)->longtitude,
                'alarm_waktu'  => $alarmTerakhir
                    ? optional($alarmTerakhir->waktu_trigger)->format('d M Y, H:i')
                    : null,
                'alarm_status' => $alarmTerakhir?->status,
            ];
        });

        $total = $panicButtons->count();
        $totalAman = $data->where('state', 'Aman')->count();
        $totalDarurat  = $data->where('state', 'Darurat')->count();
        $totalDiproses = $data->where('state', 'Diproses')->count();

        return response()->json([
            'panic_buttons' => $data,
            'total_semua' => $total,
            'total_aman' => $totalAman,
            'total_darurat' => $totalDarurat,
            'total_diproses' => $totalDiproses,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}
