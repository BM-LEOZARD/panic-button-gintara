<?php

namespace App\Http\Controllers;

use App\Events\PanicButtonTriggered;
use App\Models\AlarmPanicButton;
use App\Models\TugasAdmin;
use App\Models\User;
use App\Services\EmailNotificationService;
use App\Services\FonnteMessageTemplate;
use App\Services\FonnteService;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EndUserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        if (!$pelanggan) {
            return redirect()->route('login')->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $panicButton = $pelanggan->panicButton()->with(['wilayah', 'lokasi'])->first();

        $riwayat = AlarmPanicButton::with(['panicButton.wilayah', 'lokasi'])
            ->where('pelanggan_id', $pelanggan->id)
            ->latest('waktu_trigger')
            ->limit(10)
            ->get();

        return view('dashboard', compact('user', 'pelanggan', 'panicButton', 'riwayat'));
    }

    public function trigger(Request $request)
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        if (!$pelanggan) {
            return response()->json(['success' => false, 'message' => 'Data pelanggan tidak ditemukan.'], 404);
        }

        $panicButton = $pelanggan->panicButton()->with('lokasi', 'wilayah')->first();

        if (!$panicButton) {
            return response()->json(['success' => false, 'message' => 'Panic button tidak ditemukan.'], 404);
        }

        $alarmAktif = AlarmPanicButton::where('pelanggan_id', $pelanggan->id)
            ->whereIn('status', ['Menunggu', 'Diproses'])
            ->exists();

        if ($alarmAktif) {
            return response()->json([
                'success' => false,
                'message' => 'Alarm masih aktif. Tim sedang dalam perjalanan menuju lokasi Anda.',
            ], 409);
        }

        $epochGmt7 = now('Asia/Jakarta')->timestamp;
        $alarm = null;

        DB::transaction(function () use ($panicButton, $pelanggan, $epochGmt7, &$alarm) {
            $alarm = AlarmPanicButton::create([
                'panic_button_id' => $panicButton->id,
                'lokasi_panic_button_id' => $panicButton->lokasi?->id,
                'pelanggan_id' => $pelanggan->id,
                'status' => 'Menunggu',
                'waktu_trigger' => now('Asia/Jakarta'),
            ]);

            $panicButton->update([
                'state' => 'Darurat',
                'timestamp' => $epochGmt7,
            ]);
        });

        $this->publishMqtt($panicButton, 1, $epochGmt7);

        broadcast(new PanicButtonTriggered(
            $alarm->load('pelanggan.user', 'panicButton.wilayah'),
            $panicButton->wilayah_id
        ));

        $this->kirimWaKeAdmin($panicButton, $pelanggan, $user, $epochGmt7);

        return response()->json([
            'success' => true,
            'message' => '🚨 Sinyal darurat berhasil dikirim! Tim akan segera menuju lokasi Anda.',
            'waktu' => now('Asia/Jakarta')->format('d M Y, H:i') . ' WIB',
            'epoch' => $epochGmt7,
            'alarm_id' => $alarm->id,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $action = $request->input('_action');

        return match ($action) {
            'nomor_hp' => $this->handleGantiNomor($request, $user),
            'password' => $this->handleGantiPassword($request, $user),
            'email' => $this->handleGantiEmail($request, $user),
            default => back()->with('error', 'Aksi tidak dikenal.'),
        };
    }

    private function handleGantiNomor(Request $request, $user)
    {
        $request->validate([
            'no_hp' => 'required|string|max:15',
            'current_password' => 'required|string',
        ], [
            'no_hp.required' => 'Nomor HP baru wajib diisi.',
            'current_password.required' => 'Password saat ini wajib diisi.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah.')->withInput();
        }

        $nomorLama = $user->no_hp;
        $nomorBaru = $request->no_hp;
        $gantiNomor = $nomorBaru !== $nomorLama;

        $user->update(['no_hp' => $nomorBaru]);

        if ($gantiNomor) {
            $this->kirimNotifikasiGantiNomor($user, $nomorBaru);
        }

        return back()->with('success', 'Nomor HP berhasil diperbarui.');
    }

    private function handleGantiPassword(Request $request, $user)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah.')->withInput();
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        $this->kirimNotifikasiGantiPassword($user);

        return back()->with('success', 'Password berhasil diubah. Notifikasi telah dikirim ke WA dan email Anda.');
    }

    private function handleGantiEmail(Request $request, $user)
    {
        $request->validate([
            'new_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => 'required|string',
        ], [
            'new_email.required' => 'Email baru wajib diisi.',
            'new_email.email' => 'Format email tidak valid.',
            'new_email.unique' => 'Email sudah digunakan oleh akun lain.',
            'current_password.required' => 'Password saat ini wajib diisi.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah.')->withInput();
        }

        $emailLama = $user->email;
        $emailBaru = $request->new_email;

        if ($emailBaru === $emailLama) {
            return back()->with('error', 'Email baru sama dengan email saat ini.')->withInput();
        }

        $user->update([
            'email' => $emailBaru,
            'email_verified_at' => null,
            'otp_verified_at' => null,
        ]);

        $this->kirimNotifikasiGantiEmail($user, $emailLama, $emailBaru);

        return back()->with('success', 'Email berhasil diubah. Notifikasi telah dikirim ke WA dan email lama Anda.');
    }

    private function kirimNotifikasiGantiNomor($user, string $nomorBaru): void
    {
        try {
            $pelanggan = $user->pelanggan;
            $panicButton = $pelanggan?->panicButton()->with('wilayah')->first();
            $namaWilayah = $panicButton?->wilayah?->nama ?? '-';

            $pesan = FonnteMessageTemplate::nomorHpDiperbarui(
                nama: $user->name,
                nomorBaru: $nomorBaru,
                namaWilayah: $namaWilayah,
            );

            (new FonnteService())->send($nomorBaru, $pesan);

            EmailNotificationService::gantiNomorHp(
                toEmail: $user->email,
                nama: $user->name,
                nomorBaru: $nomorBaru,
                namaWilayah: $namaWilayah,
            );
        } catch (\Throwable $e) {
            Log::error('[Notifikasi] GantiNomor: ' . $e->getMessage());
        }
    }

    private function kirimNotifikasiGantiPassword($user): void
    {
        try {
            $pelanggan = $user->pelanggan;
            $panicButton = $pelanggan?->panicButton()->with('wilayah')->first();
            $namaWilayah = $panicButton?->wilayah?->nama ?? '-';

            $pesan = FonnteMessageTemplate::passwordDiubah(
                nama: $user->name,
                namaWilayah: $namaWilayah,
            );

            if (!empty($user->no_hp)) {
                (new FonnteService())->send($user->no_hp, $pesan);
            }

            EmailNotificationService::gantiPassword(
                toEmail: $user->email,
                nama: $user->name,
                namaWilayah: $namaWilayah,
            );
        } catch (\Throwable $e) {
            Log::error('[Notifikasi] GantiPassword: ' . $e->getMessage());
        }
    }

    private function kirimNotifikasiGantiEmail($user, string $emailLama, string $emailBaru): void
    {
        try {
            $pelanggan = $user->pelanggan;
            $panicButton = $pelanggan?->panicButton()->with('wilayah')->first();
            $namaWilayah = $panicButton?->wilayah?->nama ?? '-';

            $pesan = FonnteMessageTemplate::emailDiubah(
                nama: $user->name,
                emailLama: $emailLama,
                emailBaru: $emailBaru,
                namaWilayah: $namaWilayah,
            );

            if (!empty($user->no_hp)) {
                (new FonnteService())->send($user->no_hp, $pesan);
            }

            EmailNotificationService::gantiEmail(
                emailLama: $emailLama,
                nama: $user->name,
                emailBaru: $emailBaru,
                namaWilayah: $namaWilayah,
            );
        } catch (\Throwable $e) {
            Log::error('[Notifikasi] GantiEmail: ' . $e->getMessage());
        }
    }

    private function kirimWaKeAdmin($panicButton, $pelanggan, $user, int $epochGmt7): void
    {
        try {
            $adminIds = TugasAdmin::where('wilayah_cover_id', $panicButton->wilayah_id)
                ->pluck('user_id');

            if ($adminIds->isEmpty()) {
                Log::warning('[Fonnte] Tidak ada admin yang bertugas di wilayah ' . $panicButton->wilayah_id);
                return;
            }

            $admins = User::whereIn('id', $adminIds)
                ->whereNull('deleted_at')
                ->get();

            if ($admins->isEmpty()) return;

            $lokasi  = $panicButton->lokasi;
            $wilayah = $panicButton->wilayah;
            $waktu = \Carbon\Carbon::createFromTimestamp($epochGmt7, 'Asia/Jakarta')->format('d M Y, H:i');

            $pesan = FonnteMessageTemplate::notifikasiAdminPanicButton(
                namaPelanggan: $user->name,
                noHpPelanggan: $user->no_hp,
                alamat: $pelanggan->alamat . ', RT ' . $pelanggan->RT . '/RW ' . $pelanggan->RW,
                blok: $panicButton->GetBlockID,
                nomorRumah: $panicButton->GetNumber,
                namaWilayah: $wilayah->nama,
                latitude: $lokasi?->latitude ?? '-',
                longitude: $lokasi?->longtitude ?? '-',
                waktu: $waktu,
            );

            $fonnte = new FonnteService();

            foreach ($admins as $admin) {
                if (empty($admin->no_hp)) {
                    Log::warning("[Fonnte] Admin {$admin->name} tidak memiliki nomor HP.");
                    continue;
                }

                $result = $fonnte->send($admin->no_hp, $pesan);

                if (!$result['status']) {
                    Log::warning("[Fonnte] Gagal kirim ke admin {$admin->name} ({$admin->no_hp}): " . ($result['message'] ?? '-'));
                } else {
                    Log::info("[Fonnte] Notifikasi panic button terkirim ke admin {$admin->name} ({$admin->no_hp}).");
                }
            }
        } catch (\Throwable $e) {
            Log::error('[Fonnte] kirimWaKeAdmin error: ' . $e->getMessage());
        }
    }

    private function publishMqtt($panicButton, int $state, int $epoch): void
    {
        try {
            $mqtt = new MqttService();
            $mqtt->publish([
                'DisID' => $panicButton->DisID,
                'GUID' => $panicButton->GUID,
                'GetBlockID' => $panicButton->GetBlockID,
                'GetNumber' => $panicButton->GetNumber,
                'STATE' => $state,
                'time' => $epoch,
            ]);
        } catch (\Throwable $e) {
            Log::error('[MQTT] Publish error (trigger): ' . $e->getMessage());
        }
    }
}
