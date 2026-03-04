<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Pendaftaran;
use App\Services\FonnteMessageTemplate;
use App\Services\FonnteService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DataPelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with(['user', 'panicButton.wilayah', 'panicButton.lokasi'])
            ->whereHas('user', fn($q) => $q->whereNull('deleted_at'))
            ->latest()
            ->get();

        $pelangganNonaktif = Pelanggan::with(['user' => fn($q) => $q->onlyTrashed(), 'panicButton.wilayah'])
            ->whereHas('user', fn($q) => $q->onlyTrashed())
            ->latest()
            ->get();

        return view('superadmin.data-pelanggan.index', compact('pelanggan', 'pelangganNonaktif'));
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with([
            'user' => fn($q) => $q->withTrashed(),
            'gambar',
            'panicButton.wilayah',
            'panicButton.lokasi',
        ])->findOrFail($id);

        return view('superadmin.data-pelanggan.show', compact('pelanggan'));
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::with(['user', 'panicButton.wilayah'])->findOrFail($id);

        $nama = $pelanggan->user->name;
        $noHp = $pelanggan->user->no_hp;
        $namaWilayah = $pelanggan->panicButton?->wilayah?->nama ?? '-';
        $tanggal = now('Asia/Jakarta')->format('d M Y');

        DB::transaction(function () use ($pelanggan) {
            if ($pelanggan->panicButton) {
                $pelanggan->panicButton->update(['state' => 'Aman']);
            }
            $pelanggan->user->delete();
        });

        $this->kirimWA(
            noHp: $noHp,
            pesan: FonnteMessageTemplate::akunDinonaktifkan(
                nama: $nama,
                namaWilayah: $namaWilayah,
                tanggal: $tanggal,
            )
        );

        return redirect()->route('superadmin.data-pelanggan.index')
            ->with('success', "Akun pelanggan {$nama} berhasil dinonaktifkan.");
    }

    public function restore($id)
    {
        $pelanggan = Pelanggan::with([
            'user' => fn($q) => $q->onlyTrashed(),
            'panicButton.wilayah',
        ])->findOrFail($id);

        $nama = $pelanggan->user->name;
        $noHp = $pelanggan->user->no_hp;
        $namaWilayah = $pelanggan->panicButton?->wilayah?->nama ?? '-';
        $tanggal = now('Asia/Jakarta')->format('d M Y');

        $pelanggan->user->restore();

        $this->kirimWA(
            noHp: $noHp,
            pesan: FonnteMessageTemplate::akunDiaktifkanKembali(
                nama: $nama,
                namaWilayah: $namaWilayah,
                tanggal: $tanggal,
            )
        );

        return redirect()->route('superadmin.data-pelanggan.index')
            ->with('success', "Akun pelanggan {$nama} berhasil diaktifkan kembali.");
    }

    public function forceDelete($id)
    {
        $pelanggan = Pelanggan::with([
            'user' => fn($q) => $q->onlyTrashed(),
            'panicButton.wilayah',
            'gambar',
        ])->findOrFail($id);

        $nama = $pelanggan->user->name;
        $noHp = $pelanggan->user->no_hp;
        $namaWilayah = $pelanggan->panicButton?->wilayah?->nama ?? '-';
        $tanggal = now('Asia/Jakarta')->format('d M Y');

        $this->kirimWA(
            noHp: $noHp,
            pesan: FonnteMessageTemplate::akunDihapusPermanen(
                nama: $nama,
                namaWilayah: $namaWilayah,
                tanggal: $tanggal,
            )
        );

        $fotoKtpPath = $pelanggan->gambar?->foto_ktp;

        DB::transaction(function () use ($pelanggan) {
            Pendaftaran::where('nik', $pelanggan->nik)
                ->where('status', 'Disetujui')
                ->update(['status' => 'Dihapus']);

            if ($pelanggan->panicButton) {
                $pelanggan->panicButton->lokasi?->delete();
                $pelanggan->panicButton->delete();
            }
            $pelanggan->gambar?->delete();
            $pelanggan->delete();
            $pelanggan->user->forceDelete();
        });

        if ($fotoKtpPath && Storage::disk('public')->exists($fotoKtpPath)) {
            Storage::disk('public')->delete($fotoKtpPath);
        }

        return redirect()->route('superadmin.data-pelanggan.index')
            ->with('success', "Akun pelanggan {$nama} berhasil dihapus secara permanen.");
    }

    private function kirimWA(string $noHp, string $pesan): void
    {
        if (empty($noHp)) return;

        try {
            (new FonnteService())->send($noHp, $pesan);
        } catch (\Throwable $e) {
            Log::error('[Fonnte] DataPelangganController: ' . $e->getMessage());
        }
    }
}
