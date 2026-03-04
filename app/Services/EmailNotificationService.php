<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * EmailNotificationService
 */
class EmailNotificationService
{
    // ─────────────────────────────────────────────────────────────────
    //  Public methods
    // ─────────────────────────────────────────────────────────────────

    /**
     * Notifikasi ganti No. HP → dikirim ke email pelanggan
     */
    public static function gantiNomorHp(
        string $toEmail,
        string $nama,
        string $nomorBaru,
        string $namaWilayah
    ): void {
        self::send(
            view: 'emails.ganti-nomor-hp',
            to: $toEmail,
            nama: $nama,
            subject: '📱 Nomor HP Akun Anda Telah Diperbarui — Gintara Net',
            data: [
                'nama'        => $nama,
                'nomorBaru'   => $nomorBaru,
                'namaWilayah' => $namaWilayah,
                'waktu'       => now('Asia/Jakarta')->format('d M Y, H:i') . ' WIB',
            ],
        );
    }

    /**
     * Notifikasi ganti Password → dikirim ke email pelanggan
     */
    public static function gantiPassword(
        string $toEmail,
        string $nama,
        string $namaWilayah
    ): void {
        self::send(
            view: 'emails.ganti-password',
            to: $toEmail,
            nama: $nama,
            subject: '🔐 Password Akun Anda Telah Diubah — Gintara Net',
            data: [
                'nama'        => $nama,
                'namaWilayah' => $namaWilayah,
                'waktu'       => now('Asia/Jakarta')->format('d M Y, H:i') . ' WIB',
            ],
        );
    }

    /**
     * Notifikasi ganti Email:
     *   1. Dikirim ke EMAIL LAMA  → konfirmasi perubahan (tanpa tampilkan email lama)
     *   2. Dikirim ke EMAIL BARU  → pemberitahuan email terhubung ke sistem
     */
    public static function gantiEmail(
        string $emailLama,
        string $nama,
        string $emailBaru,
        string $namaWilayah
    ): void {
        $waktu = now('Asia/Jakarta')->format('d M Y, H:i') . ' WIB';

        self::send(
            view: 'emails.ganti-email-lama',
            to: $emailLama,
            nama: $nama,
            subject: '✉️ Alamat Email Akun Anda Telah Diubah — Gintara Net',
            data: [
                'nama'        => $nama,
                'emailBaru'   => $emailBaru,
                'namaWilayah' => $namaWilayah,
                'waktu'       => $waktu,
            ],
        );

        self::send(
            view: 'emails.ganti-email-baru',
            to: $emailBaru,
            nama: $nama,
            subject: '✅ Email Anda Telah Terhubung ke Sistem Panic Button — Gintara Net',
            data: [
                'nama'        => $nama,
                'emailBaru'   => $emailBaru,
                'namaWilayah' => $namaWilayah,
                'waktu'       => $waktu,
            ],
        );
    }

    // ─────────────────────────────────────────────────────────────────
    //  Private helper
    // ─────────────────────────────────────────────────────────────────

    private static function send(
        string $view,
        string $to,
        string $nama,
        string $subject,
        array  $data
    ): void {
        try {
            Mail::send($view, $data, function ($msg) use ($to, $nama, $subject) {
                $msg->to($to, $nama)->subject($subject);
            });
        } catch (\Throwable $e) {
            Log::error("[Email] Gagal kirim '{$subject}' ke {$to}: " . $e->getMessage());
        }
    }
}
