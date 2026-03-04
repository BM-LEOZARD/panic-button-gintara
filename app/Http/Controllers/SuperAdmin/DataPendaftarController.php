<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\GambarDataPelanggan;
use App\Models\LokasiPanicButton;
use App\Models\PanicButton;
use App\Models\Pelanggan;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Services\FonnteService;
use App\Services\FonnteMessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataPendaftarController extends Controller
{
    const KODE_PERUSAHAAN = 'GNTR';
    const KODE_PERANGKAT = 'PB';

    public function index()
    {
        $pendaftaran = Pendaftaran::with('wilayah')->latest()->get();
        return view('superadmin.data-pendaftar.index', compact('pendaftaran'));
    }

    public function show($id)
    {
        $pendaftaran = Pendaftaran::with('wilayah')->findOrFail($id);

        $kodeWilayah = $pendaftaran->wilayah->kode_wilayah;
        $nomorUrut = $this->generateNomorUrut($pendaftaran->wilayah_cover_id);
        $disId = $kodeWilayah . '-' . $nomorUrut;

        $sessionKey = 'guid_pendaftaran_' . $id;

        if (!session()->has($sessionKey)) {
            $guid = null;
            $kodeAcak = null;
            $maxTry = 10;

            for ($i = 0; $i < $maxTry; $i++) {
                $kodeAcak = strtoupper(Str::random(6));
                $guidCoba = implode('-', [
                    self::KODE_PERUSAHAAN,
                    self::KODE_PERANGKAT,
                    $kodeWilayah,
                    $kodeAcak,
                    $nomorUrut,
                ]);

                if (!PanicButton::where('GUID', $guidCoba)->exists()) {
                    $guid = $guidCoba;
                    break;
                }
            }

            if (!$guid) {
                abort(500, 'Gagal generate GUID unik. Silakan coba lagi.');
            }

            session()->put($sessionKey, [
                'guid' => $guid,
                'kode_acak' => $kodeAcak,
            ]);
        }

        $sessionData = session()->get($sessionKey);
        $guid = $sessionData['guid'];
        $kodeAcak = $sessionData['kode_acak'];

        return view('superadmin.data-pendaftar.show', compact(
            'pendaftaran',
            'guid',
            'kodeAcak',
            'nomorUrut',
            'disId'
        ));
    }

    public function setujui(Request $request, $id)
    {
        $request->validate([
            'GUID' => [
                'required',
                'string',
                'regex:/^GNTR-PB-[A-Z0-9]+-[A-Z0-9]{6}-[0-9]{3}$/',
                'unique:panic_button,GUID',
            ],
            'DisID' => 'required|string',
        ]);

        $pendaftaran = Pendaftaran::with('wilayah')->findOrFail($id);

        if ($pendaftaran->status !== 'Menunggu') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $kodeWilayah = $pendaftaran->wilayah->kode_wilayah;
        if (!str_contains($request->GUID, "-{$kodeWilayah}-")) {
            return back()->with('error', 'Kode wilayah pada GUID tidak sesuai.');
        }

        $defaultPassword = 'qwerty123';
        $panicButton = null;

        DB::transaction(function () use ($pendaftaran, $request, $defaultPassword, &$panicButton) {
            $user = User::create([
                'name' => $pendaftaran->name,
                'username' => $pendaftaran->username,
                'role' => 'EndUser',
                'jenis_kelamin' => $pendaftaran->jenis_kelamin,
                'no_hp' => $pendaftaran->no_hp,
                'email' => $pendaftaran->email,
                'password' => Hash::make($defaultPassword),
            ]);

            $pelanggan = Pelanggan::create([
                'user_id' => $user->id,
                'nik' => $pendaftaran->nik,
                'ttl' => $pendaftaran->ttl,
                'alamat' => $pendaftaran->alamat,
                'RT' => $pendaftaran->RT,
                'RW' => $pendaftaran->RW,
                'desa' => $pendaftaran->desa,
                'kecamatan' => $pendaftaran->kecamatan,
                'kelurahan' => $pendaftaran->kelurahan,
            ]);

            $panicButton = PanicButton::create([
                'pelanggan_id' => $pelanggan->id,
                'wilayah_id' => $pendaftaran->wilayah_cover_id,
                'GUID' => $request->GUID,
                'DisID' => $request->DisID,
                'GetBlockID' => $pendaftaran->GetBlockID,
                'GetNumber' => $pendaftaran->GetNumber,
                'state' => 'Aman',
            ]);

            LokasiPanicButton::create([
                'panic_button_id' => $panicButton->id,
                'latitude' => $pendaftaran->latitude,
                'longtitude' => $pendaftaran->longtitude,
            ]);

            GambarDataPelanggan::create([
                'pelanggan_id' => $pelanggan->id,
                'foto_ktp' => $pendaftaran->foto_ktp,
            ]);

            $pendaftaran->update([
                'status' => 'Disetujui',
                'waktu_verifikasi' => now(),
                'panic_button_id'  => $panicButton->id,
            ]);
        });

        session()->forget('guid_pendaftaran_' . $id);

        $this->kirimWADisetujui(
            noHp: $pendaftaran->no_hp,
            nama: $pendaftaran->name,
            email: $pendaftaran->email,
            password: $defaultPassword,
            namaWilayah: $pendaftaran->wilayah->nama,
        );

        return redirect()->route('superadmin.data-pendaftar.index')
            ->with('success', "Pendaftaran {$pendaftaran->name} disetujui. Akun panic button berhasil dibuat.");
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string|max:500',
        ]);

        $pendaftaran = Pendaftaran::with('wilayah')->findOrFail($id);

        if ($pendaftaran->status !== 'Menunggu') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $pendaftaran->update([
            'status' => 'Ditolak',
            'waktu_verifikasi'  => now(),
            'catatan_penolakan' => $request->catatan_penolakan,
        ]);

        $this->kirimWADitolak(
            noHp: $pendaftaran->no_hp,
            nama: $pendaftaran->name,
            namaWilayah: $pendaftaran->wilayah->nama,
            alasan: $request->catatan_penolakan,
        );

        return redirect()->route('superadmin.data-pendaftar.index')
            ->with('success', "Pendaftaran {$pendaftaran->name} telah ditolak.");
    }

    private function kirimWADisetujui(
        string $noHp,
        string $nama,
        string $email,
        string $password,
        string $namaWilayah
    ): void {
        if (empty($noHp)) {
            Log::warning('[Fonnte] Nomor HP kosong, pesan disetujui tidak dikirim.');
            return;
        }

        try {
            $fonnte = new FonnteService();
            $pesan  = FonnteMessageTemplate::pendaftaranDisetujui(
                nama: $nama,
                email: $email,
                password: $password,
                namaWilayah: $namaWilayah,
            );

            $result = $fonnte->send($noHp, $pesan);

            if (!$result['status']) {
                Log::warning("[Fonnte] Pesan disetujui ke {$noHp} gagal: " . ($result['message'] ?? '-'));
            }
        } catch (\Throwable $e) {
            Log::error('[Fonnte] Exception kirim WA disetujui: ' . $e->getMessage());
        }
    }

    private function kirimWADitolak(
        string $noHp,
        string $nama,
        string $namaWilayah,
        string $alasan
    ): void {
        if (empty($noHp)) {
            Log::warning('[Fonnte] Nomor HP kosong, pesan ditolak tidak dikirim.');
            return;
        }

        try {
            $fonnte = new FonnteService();
            $pesan = FonnteMessageTemplate::pendaftaranDitolak(
                nama: $nama,
                namaWilayah: $namaWilayah,
                alasan: $alasan,
            );

            $result = $fonnte->send($noHp, $pesan);

            if (!$result['status']) {
                Log::warning("[Fonnte] Pesan ditolak ke {$noHp} gagal: " . ($result['message'] ?? '-'));
            }
        } catch (\Throwable $e) {
            Log::error('[Fonnte] Exception kirim WA ditolak: ' . $e->getMessage());
        }
    }

    private function generateNomorUrut(int $wilayahCoverId): string
    {
        $jumlah = PanicButton::where('wilayah_id', $wilayahCoverId)->count();
        return str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);
    }

    private function generateUsername(string $nik): string
    {
        $base = 'user_' . substr($nik, 0, 8);
        $username = $base;
        $counter  = 1;
        while (User::withTrashed()->where('username', $username)->exists()) {
            $username = $base . '_' . $counter++;
        }
        return $username;
    }
}
