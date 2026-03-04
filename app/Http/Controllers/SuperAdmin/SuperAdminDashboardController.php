<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AlarmPanicButton;
use App\Models\Pelanggan;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalAlarmHariIni = AlarmPanicButton::whereDate('waktu_trigger', $today)->count();
        $alarmSelesaiHariIni = AlarmPanicButton::whereDate('waktu_trigger', $today)
            ->where('status', 'Selesai')->count();
        $alarmDiproses = AlarmPanicButton::whereDate('waktu_trigger', $today)
            ->where('status', 'Diproses')->count();
        $alarmMenunggu = AlarmPanicButton::whereDate('waktu_trigger', $today)
            ->where('status', 'Menunggu')->count();

        $totalPelanggan = Pelanggan::count();
        $totalAdmin = User::where('role', 'Admin')->whereNull('deleted_at')->count();
        $totalPendaftar = Pendaftaran::where('status', 'Menunggu')->count();

        $now = Carbon::now();
        ['labels' => $trendLabels, 'data' => $trendData] = $this->getTrendData(
            $now->year,
            $now->month
        );

        $availableMonths = $this->getAvailableMonths($now->year);
        $currentYear = $now->year;

        $alarmSelesaiTotal  = AlarmPanicButton::where('status', 'Selesai')->count();
        $alarmDiprosesTotal = AlarmPanicButton::where('status', 'Diproses')->count();
        $alarmMenungguTotal = AlarmPanicButton::where('status', 'Menunggu')->count();
        $totalSemuaAlarm = $alarmSelesaiTotal + $alarmDiprosesTotal + $alarmMenungguTotal;

        $recentAlarms = AlarmPanicButton::with([
            'pelanggan.user',
            'panicButton.wilayah',
        ])
            ->orderBy('waktu_trigger', 'desc')
            ->limit(10)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalAlarmHariIni',
            'alarmSelesaiHariIni',
            'alarmDiproses',
            'alarmMenunggu',
            'totalPelanggan',
            'totalAdmin',
            'totalPendaftar',
            'trendLabels',
            'trendData',
            'availableMonths',
            'currentYear',
            'alarmSelesaiTotal',
            'alarmDiprosesTotal',
            'alarmMenungguTotal',
            'totalSemuaAlarm',
            'recentAlarms',
        ));
    }

    public function trendData(Request $request)
    {
        $year  = (int) $request->input('year',  Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);

        ['labels' => $labels, 'data' => $data] = $this->getTrendData($year, $month);

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    private function getTrendData(int $year, int $month): array
    {
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $rows = Pendaftaran::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->select(DB::raw('DAY(created_at) as hari'), DB::raw('COUNT(*) as total'))
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        $weeklyTotals = [0, 0, 0, 0, 0];
        foreach ($rows as $row) {
            $idx = (int) ceil($row->hari / 7) - 1;
            if ($idx >= 0 && $idx <= 4) {
                $weeklyTotals[$idx] += $row->total;
            }
        }

        return [
            'labels' => ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'],
            'data' => $weeklyTotals,
        ];
    }

    private function getAvailableMonths(int $year): array
    {
        $now = Carbon::now();
        $months = [];

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $maxMonth = ($year === $now->year) ? $now->month : 12;

        for ($m = 1; $m <= $maxMonth; $m++) {
            $months[] = [
                'value' => "{$year}-{$m}",
                'label' => $namaBulan[$m] . ' ' . $year,
            ];
        }

        return $months;
    }
}
