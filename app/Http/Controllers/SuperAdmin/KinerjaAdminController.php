<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AlarmPanicButton;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class KinerjaAdminController extends Controller
{
    private const COLORS = [
        '#265ed7', // biru
        '#e95959', // merah
        '#27ae60', // hijau
        '#f59e0b', // kuning
        '#8b5cf6', // ungu
        '#06b6d4', // cyan
        '#ec4899', // pink
        '#f97316', // oranye
        '#14b8a6', // teal
        '#6366f1', // indigo
    ];

    public function index()
    {
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $availableMonths = [];
        for ($m = 1; $m <= $currentMonth; $m++) {
            $availableMonths[] = [
                'value' => "{$currentYear}-{$m}",
                'label' => $namaBulan[$m] . ' ' . $currentYear,
            ];
        }

        $chartData = $this->getChartData($currentYear, $currentMonth);

        return view('superadmin.kinerja-admin', array_merge(compact(
            'availableMonths',
            'currentYear',
            'currentMonth',
        ), $chartData));
    }

    public function chartData(Request $request)
    {
        $year = (int) $request->input('year',  Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);

        return response()->json($this->getChartData($year, $month));
    }

    private function getChartData(int $year, int $month): array
    {
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $admins = User::where('role', 'Admin')
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get(['id', 'name']);

        $daysInMonth = $start->daysInMonth;

        $rows = AlarmPanicButton::select(
                'user_id',
                DB::raw('DAY(waktu_selesai) as hari'),
                DB::raw('COUNT(*) as total')
            )
            ->where('status', 'Selesai')
            ->whereNotNull('user_id')
            ->whereBetween('waktu_selesai', [$start, $end])
            ->groupBy('user_id', 'hari')
            ->get();

        $map = [];
        foreach ($rows as $row) {
            $map[$row->user_id][$row->hari] = $row->total;
        }

        $categories = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $categories[] = (string) $d;
        }

        $series = [];
        foreach ($admins as $i => $admin) {
            $data = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $data[] = $map[$admin->id][$d] ?? 0;
            }
            $series[] = [
                'name' => $admin->name,
                'color' => self::COLORS[$i % count(self::COLORS)],
                'data' => $data,
            ];
        }

        $summary = AlarmPanicButton::select(
                'user_id',
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, waktu_trigger, waktu_selesai)) as avg_menit')
            )
            ->where('status', 'Selesai')
            ->whereNotNull('user_id')
            ->whereBetween('waktu_selesai', [$start, $end])
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $summaryList = [];
        foreach ($admins as $i => $admin) {
            $s = $summary->get($admin->id);
            $summaryList[] = [
                'name' => $admin->name,
                'color' => self::COLORS[$i % count(self::COLORS)],
                'total' => $s ? (int) $s->total : 0,
                'avg_menit' => $s ? round($s->avg_menit) : 0,
            ];
        }

        return compact('categories', 'series', 'summaryList');
    }
}