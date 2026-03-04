<?php

namespace App\Http\Controllers\Admin;

use App\Events\AlarmDiproses;
use App\Events\AlarmSelesai;
use App\Http\Controllers\Controller;
use App\Models\AlarmPanicButton;
use App\Models\DokumenFoto;
use App\Models\TugasAdmin;
use App\Services\FonnteMessageTemplate;
use App\Services\FonnteService;
use App\Services\MqttService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $wilayahIds = TugasAdmin::where('user_id', $admin->id)
            ->pluck('wilayah_cover_id');

        $tugasMenunggu = AlarmPanicButton::with([
            'pelanggan.user',
            'panicButton.wilayah',
            'lokasi',
        ])
            ->where('status', 'Menunggu')
            ->whereHas('panicButton', fn($q) => $q->whereIn('wilayah_id', $wilayahIds))
            ->latest('waktu_trigger')
            ->get();

        $tugasSaya = AlarmPanicButton::with([
            'pelanggan.user',
            'panicButton.wilayah',
            'lokasi',
        ])
            ->where('status', 'Diproses')
            ->where('user_id', $admin->id)
            ->latest('waktu_trigger')
            ->get();

        $riwayat = AlarmPanicButton::with([
            'pelanggan.user',
            'panicButton.wilayah',
        ])
            ->where('status', 'Selesai')
            ->where('user_id', $admin->id)
            ->latest('waktu_selesai')
            ->limit(10)
            ->get();

        return view('admin.tugas.index', compact('tugasMenunggu', 'tugasSaya', 'riwayat', 'wilayahIds'));
    }

    public function ambil($id)
    {
        $admin = Auth::user();

        $tugasDiproses = AlarmPanicButton::where('user_id', $admin->id)
            ->where('status', 'Diproses')
            ->exists();

        if ($tugasDiproses) {
            return redirect()->route('admin.tugas.index')
                ->with('error', 'Anda masih memiliki tugas yang sedang diproses. Selesaikan tugas tersebut terlebih dahulu sebelum mengambil tugas baru.');
        }

        $wilayahIds = TugasAdmin::where('user_id', $admin->id)
            ->pluck('wilayah_cover_id');

        $alarm = null;

        try {
            DB::transaction(function () use ($id, $admin, $wilayahIds, &$alarm) {
                $alarm = AlarmPanicButton::lockForUpdate()
                    ->where('id', $id)
                    ->where('status', 'Menunggu')
                    ->whereHas('panicButton', fn($q) => $q->whereIn('wilayah_id', $wilayahIds))
                    ->firstOrFail();

                $alarm->update([
                    'status'         => 'Diproses',
                    'user_id'        => $admin->id,
                    'ditangani_oleh' => $admin->name,
                ]);
            });
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.tugas.index')
                ->with('error', 'Tugas ini sudah diambil oleh admin lain.');
        }

        $alarm->load('pelanggan.user', 'panicButton.wilayah');

        // ── MQTT: STATE 2 ──────────────────────────────────────────
        $this->publishMqtt($alarm->panicButton, 2);

        // Broadcast ke superadmin via WebSocket
        broadcast(new AlarmDiproses($alarm));

        // ── WA: Tim menuju lokasi ──────────────────────────────────
        $this->kirimWA(
            noHp: $alarm->pelanggan->user->no_hp,
            pesan: FonnteMessageTemplate::timMenujuLokasi(
                namaPelanggan: $alarm->pelanggan->user->name,
                namaAdmin: $admin->name,
                noHpAdmin: $admin->no_hp,
                namaWilayah: $alarm->panicButton->wilayah->nama,
                waktu: now('Asia/Jakarta')->format('H:i'),
            )
        );

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil diambil. Segera tangani pelanggan.');
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
            ->where('status', 'Diproses')
            ->firstOrFail();

        return view('admin.tugas.show', compact('alarm'));
    }

    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto.*'       => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'keterangan.*' => 'nullable|string|max:255',
        ], [
            'foto.*.required' => 'File foto wajib dipilih.',
            'foto.*.image'    => 'File harus berupa gambar.',
            'foto.*.mimes'    => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto.*.max'      => 'Ukuran foto maksimal 5MB.',
        ]);

        $admin = Auth::user();

        $alarm = AlarmPanicButton::where('id', $id)
            ->where('user_id', $admin->id)
            ->where('status', 'Diproses')
            ->firstOrFail();

        $fotos       = $request->file('foto') ?? [];
        $keterangans = $request->input('keterangan', []);

        foreach ($fotos as $index => $file) {
            $path = $file->store('dokumen_foto', 'public');
            DokumenFoto::create([
                'alarm_panic_button_id' => $alarm->id,
                'foto_dokumentasi'      => $path,
                'keterangan'            => $keterangans[$index] ?? null,
            ]);
        }

        return back()->with('success', count($fotos) . ' foto berhasil diunggah.');
    }

    public function hapusFoto($fotoId)
    {
        $admin = Auth::user();

        $foto = DokumenFoto::whereHas('alarm', fn($q) => $q->where('user_id', $admin->id))
            ->findOrFail($fotoId);

        Storage::disk('public')->delete($foto->foto_dokumentasi);
        $foto->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function selesai(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:1000',
        ], [
            'keterangan.required' => 'Keterangan penanganan wajib diisi.',
        ]);

        $admin = Auth::user();

        $alarm = AlarmPanicButton::where('id', $id)
            ->where('user_id', $admin->id)
            ->where('status', 'Diproses')
            ->firstOrFail();

        $now = null;

        DB::transaction(function () use ($alarm, $request, &$now) {
            $now = Carbon::now('Asia/Jakarta');

            $alarm->update([
                'status'        => 'Selesai',
                'waktu_selesai' => $now,
                'keterangan'    => $request->keterangan,
            ]);

            $alarm->panicButton->update([
                'state'     => 'Aman',
                'timestamp' => $now->timestamp,
            ]);
        });

        $alarm->load('pelanggan.user', 'panicButton.wilayah');

        $this->publishMqtt($alarm->panicButton, 0, $now->timestamp);

        broadcast(new AlarmSelesai($alarm));

        $this->kirimWA(
            noHp: $alarm->pelanggan->user->no_hp,
            pesan: FonnteMessageTemplate::penangananSelesai(
                namaPelanggan: $alarm->pelanggan->user->name,
                namaAdmin: $admin->name,
                noHpAdmin: $admin->no_hp,
                namaWilayah: $alarm->panicButton->wilayah->nama,
                waktuSelesai: $now->format('d M Y, H:i'),
                keterangan: $request->keterangan,
            )
        );

        return redirect()->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil diselesaikan. Panic button pelanggan sudah kembali Aman.');
    }

    // ────────────────────────────────────────────────────────────────
    //  Helper: kirim WA via Fonnte
    // ────────────────────────────────────────────────────────────────

    private function kirimWA(string $noHp, string $pesan): void
    {
        if (empty($noHp)) return;

        try {
            (new FonnteService())->send($noHp, $pesan);
        } catch (\Throwable $e) {
            Log::error('[Fonnte] TugasController: ' . $e->getMessage());
        }
    }

    // ────────────────────────────────────────────────────────────────
    //  Helper: publish ke MQTT
    // ────────────────────────────────────────────────────────────────

    private function publishMqtt($panicButton, int $state, ?int $epoch = null): void
    {
        $epoch = $epoch ?? now('Asia/Jakarta')->timestamp;

        try {
            $mqtt = new MqttService();
            $mqtt->publish([
                'DisID'      => $panicButton->DisID,
                'GUID'       => $panicButton->GUID,
                'GetBlockID' => $panicButton->GetBlockID,
                'GetNumber'  => $panicButton->GetNumber,
                'STATE'      => $state,
                'time'       => $epoch,
            ]);
        } catch (\Throwable $e) {
            Log::error("[MQTT] Publish error (state={$state}): " . $e->getMessage());
        }
    }
}
