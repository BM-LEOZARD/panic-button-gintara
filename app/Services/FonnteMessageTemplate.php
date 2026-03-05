<?php

namespace App\Services;

class FonnteMessageTemplate
{
    // ────────────────────────────────────────────────────────────────
    //  1. Pendaftaran disetujui
    // ────────────────────────────────────────────────────────────────

    public static function pendaftaranDisetujui(
        string $nama,
        string $email,
        string $password,
        string $namaWilayah
    ): string {
        $pesan  = "✅ *Pendaftaran Anda Telah Disetujui*\n\n";
        $pesan .= "Halo, *{$nama}*! 👋\n\n";
        $pesan .= "Pendaftaran layanan Panic Button Anda di wilayah *{$namaWilayah}* telah resmi *disetujui* oleh tim Gintara Net.\n\n";
        $pesan .= "📋 *Detail Akun Anda:*\n";
        $pesan .= "• Email    : {$email}\n";
        $pesan .= "• Password : {$password}\n\n";
        $pesan .= "🔐 *Langkah Selanjutnya:*\n";
        $pesan .= "1. Login menggunakan email & password di atas\n";
        $pesan .= "2. Segera ganti password setelah login pertama\n";
        $pesan .= "3. Pastikan perangkat Panic Button Anda sudah aktif\n\n";
        $pesan .= "⚠️ _Jangan bagikan informasi akun Anda kepada siapapun._\n\n";
        $pesan .= "🔗 Login ke akun Anda: https://sos.gintara.net/login\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  2. Pendaftaran ditolak
    // ────────────────────────────────────────────────────────────────

    public static function pendaftaranDitolak(
        string $nama,
        string $namaWilayah,
        string $alasan
    ): string {
        $pesan  = "❌ *Pendaftaran Anda Tidak Dapat Disetujui*\n\n";
        $pesan .= "Halo, *{$nama}*.\n\n";
        $pesan .= "Mohon maaf, pendaftaran layanan Panic Button Anda di wilayah *{$namaWilayah}* *tidak dapat kami setujui* saat ini.\n\n";
        $pesan .= "📋 *Alasan Penolakan:*\n";
        $pesan .= "_\"{$alasan}\"_\n\n";
        $pesan .= "🔄 *Apa yang Bisa Anda Lakukan?*\n";
        $pesan .= "Anda dapat mendaftar kembali melalui halaman pendaftaran kami dengan memperbaiki data sesuai alasan di atas.\n\n";
        $pesan .= "💬 Jika ada pertanyaan, jangan ragu untuk menghubungi tim administrator Gintara Net.\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  3. Notifikasi ke Admin saat panic button ditekan
    // ────────────────────────────────────────────────────────────────

    public static function notifikasiAdminPanicButton(
        string $namaPelanggan,
        string $noHpPelanggan,
        string $alamat,
        string $blok,
        string $nomorRumah,
        string $namaWilayah,
        string $latitude,
        string $longitude,
        string $waktu
    ): string {
        $pesan  = "🚨 *ALARM DARURAT — PANIC BUTTON DITEKAN*\n\n";
        $pesan .= "Pelanggan di wilayah Anda membutuhkan bantuan segera!\n\n";
        $pesan .= "👤 *Data Pelanggan:*\n";
        $pesan .= "• Nama    : {$namaPelanggan}\n";
        $pesan .= "• No. HP  : {$noHpPelanggan}\n";
        $pesan .= "• Alamat  : {$alamat}\n";
        $pesan .= "• Blok/No : {$blok} / {$nomorRumah}\n";
        $pesan .= "• Wilayah : {$namaWilayah}\n\n";
        $pesan .= "📍 *Koordinat Lokasi:*\n";
        $pesan .= "https://maps.google.com/?q={$latitude},{$longitude}\n\n";
        $pesan .= "🕐 *Waktu Trigger:* {$waktu} WIB\n\n";
        $pesan .= "⚡ Segera login ke sistem dan ambil tugas ini:\n";
        $pesan .= "🔗 https://sos.gintara.net/portal-login\n\n";
        $pesan .= "_Notifikasi otomatis — Sistem Panic Button Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  4. Admin mengambil tugas → tim menuju lokasi
    // ────────────────────────────────────────────────────────────────

    public static function timMenujuLokasi(
        string $namaPelanggan,
        string $namaAdmin,
        string $noHpAdmin,
        string $namaWilayah,
        string $waktu
    ): string {
        $pesan  = "🚨 *Permintaan Darurat Anda Sedang Ditangani*\n\n";
        $pesan .= "Halo, *{$namaPelanggan}*!\n\n";
        $pesan .= "Sinyal darurat yang Anda kirimkan pada pukul *{$waktu} WIB* telah diterima dan sedang dalam penanganan.\n\n";
        $pesan .= "👤 *Petugas yang Bertugas:*\n";
        $pesan .= "• Nama    : {$namaAdmin}\n";
        $pesan .= "• No. HP  : {$noHpAdmin}\n";
        $pesan .= "• Wilayah : {$namaWilayah}\n\n";
        $pesan .= "📍 Tim kami sedang *dalam perjalanan menuju lokasi Anda*. Harap tetap tenang dan berada di posisi Anda.\n\n";
        $pesan .= "🔔 Anda akan mendapat notifikasi lanjutan setelah penanganan selesai.\n\n";
        $pesan .= "⚠️ _Jangan menekan panic button kembali selama proses penanganan berlangsung._\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  5. Admin menyelesaikan tugas
    // ────────────────────────────────────────────────────────────────

    public static function penangananSelesai(
        string $namaPelanggan,
        string $namaAdmin,
        string $noHpAdmin,
        string $namaWilayah,
        string $waktuSelesai,
        string $keterangan
    ): string {
        $pesan  = "✅ *Penanganan Darurat Telah Selesai*\n\n";
        $pesan .= "Halo, *{$namaPelanggan}*!\n\n";
        $pesan .= "Tim kami telah selesai menangani laporan darurat Anda. Berikut ringkasan penanganan:\n\n";
        $pesan .= "📋 *Detail Penanganan:*\n";
        $pesan .= "• Petugas       : {$namaAdmin}\n";
        $pesan .= "• No. HP        : {$noHpAdmin}\n";
        $pesan .= "• Wilayah       : {$namaWilayah}\n";
        $pesan .= "• Waktu Selesai : {$waktuSelesai} WIB\n";
        $pesan .= "• Keterangan    : {$keterangan}\n\n";
        $pesan .= "🛡️ Panic button Anda kini kembali dalam status *AMAN* dan siap digunakan.\n\n";
        $pesan .= "Jika masih ada kendala, jangan ragu untuk menghubungi kami.\n\n";
        $pesan .= "_Terima kasih telah menggunakan layanan Gintara Net._\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  6. Ganti nomor HP
    // ────────────────────────────────────────────────────────────────

    public static function nomorHpDiperbarui(
        string $nama,
        string $nomorBaru,
        string $namaWilayah
    ): string {
        $pesan  = "🔄 *Nomor HP Anda Telah Berhasil Diperbarui*\n\n";
        $pesan .= "Halo, *{$nama}*!\n\n";
        $pesan .= "Nomor HP akun Panic Button Anda telah berhasil diperbarui. Mulai sekarang, semua notifikasi sistem akan dikirimkan ke nomor ini (*{$nomorBaru}*).\n\n";
        $pesan .= "📋 *Informasi Perubahan:*\n";
        $pesan .= "• Nama     : {$nama}\n";
        $pesan .= "• No. HP   : {$nomorBaru}\n";
        $pesan .= "• Wilayah  : {$namaWilayah}\n\n";
        $pesan .= "✅ Nomor Anda kini telah terhubung dengan sistem Panic Button Gintara Net.\n\n";
        $pesan .= "⚠️ _Jika Anda tidak merasa melakukan perubahan ini, segera hubungi admin wilayah Anda._\n\n";
        $pesan .= "🔗 Login ke akun Anda: https://sos.gintara.net/login\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  7. Ganti Password — notifikasi ke pelanggan via WA
    // ────────────────────────────────────────────────────────────────

    public static function passwordDiubah(
        string $nama,
        string $namaWilayah
    ): string {
        $waktu = now('Asia/Jakarta')->format('d M Y, H:i');

        $pesan  = "🔐 *Password Akun Anda Telah Diubah*\n\n";
        $pesan .= "Halo, *{$nama}*!\n\n";
        $pesan .= "Password akun Panic Button Anda berhasil diubah pada *{$waktu} WIB*.\n\n";
        $pesan .= "📋 *Informasi Akun:*\n";
        $pesan .= "• Nama    : {$nama}\n";
        $pesan .= "• Wilayah : {$namaWilayah}\n\n";
        $pesan .= "🔒 _Tips Keamanan: Jangan bagikan password Anda kepada siapapun, termasuk kepada admin._\n\n";
        $pesan .= "⚠️ _Jika Anda tidak merasa mengubah password, segera hubungi admin wilayah Anda dan amankan akun Anda._\n\n";
        $pesan .= "🔗 Login ke akun Anda: https://sos.gintara.net/login\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  8. Ganti Email — notifikasi ke pelanggan via WA
    // ────────────────────────────────────────────────────────────────

    public static function emailDiubah(
        string $nama,
        string $emailLama,
        string $emailBaru,
        string $namaWilayah
    ): string {
        $waktu = now('Asia/Jakarta')->format('d M Y, H:i');

        $pesan  = "✉️ *Alamat Email Akun Anda Telah Diubah*\n\n";
        $pesan .= "Halo, *{$nama}*!\n\n";
        $pesan .= "Alamat email akun Panic Button Anda berhasil diubah pada *{$waktu} WIB*.\n\n";
        $pesan .= "📋 *Detail Perubahan:*\n";
        $pesan .= "• Nama        : {$nama}\n";
        $pesan .= "• Email Lama  : {$emailLama}\n";
        $pesan .= "• Email Baru  : {$emailBaru}\n";
        $pesan .= "• Wilayah     : {$namaWilayah}\n\n";
        $pesan .= "✅ Gunakan email baru Anda untuk login ke sistem berikutnya.\n\n";
        $pesan .= "⚠️ _Jika Anda tidak merasa melakukan perubahan ini, segera hubungi admin wilayah Anda._\n\n";
        $pesan .= "🔗 Login ke akun Anda: https://sos.gintara.net/login\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  9. Akun dinonaktifkan oleh SuperAdmin
    // ────────────────────────────────────────────────────────────────

    public static function akunDinonaktifkan(
        string $nama,
        string $namaWilayah,
        string $tanggal
    ): string {
        $pesan  = "🚫 *Akun Anda Telah Dinonaktifkan*\n\n";
        $pesan .= "Halo, *{$nama}*.\n\n";
        $pesan .= "Kami ingin memberitahukan bahwa akun layanan Panic Button Anda di wilayah *{$namaWilayah}* telah *dinonaktifkan* oleh tim administrator pada tanggal *{$tanggal}*.\n\n";
        $pesan .= "📌 *Dampak Penonaktifan:*\n";
        $pesan .= "• Anda tidak dapat login ke sistem\n";
        $pesan .= "• Perangkat Panic Button Anda tidak aktif\n";
        $pesan .= "• Seluruh data tetap tersimpan dengan aman\n\n";
        $pesan .= "💬 Jika Anda merasa ini adalah kesalahan atau ingin mengajukan reaktivasi, silakan hubungi tim administrator Gintara Net.\n\n";
        $pesan .= "🔗 Login ke akun Anda: https://sos.gintara.net/login\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  10. Akun dihapus permanen oleh SuperAdmin
    // ────────────────────────────────────────────────────────────────

    public static function akunDihapusPermanen(
        string $nama,
        string $namaWilayah,
        string $tanggal
    ): string {
        $pesan  = "🗑️ *Akun Anda Telah Dihapus Secara Permanen*\n\n";
        $pesan .= "Halo, *{$nama}*.\n\n";
        $pesan .= "Kami ingin memberitahukan bahwa akun layanan Panic Button Anda di wilayah *{$namaWilayah}* telah *dihapus secara permanen* dari sistem Gintara Net pada tanggal *{$tanggal}*.\n\n";
        $pesan .= "📌 *Yang Perlu Anda Ketahui:*\n";
        $pesan .= "• Seluruh data akun, perangkat, dan riwayat telah dihapus\n";
        $pesan .= "• Perangkat Panic Button tidak lagi terdaftar dalam sistem\n";
        $pesan .= "• Anda tidak dapat lagi menggunakan layanan ini dengan data yang sama\n\n";
        $pesan .= "💬 Jika ingin mendaftar kembali, Anda dapat mengajukan pendaftaran baru melalui halaman pendaftaran kami.\n\n";
        $pesan .= "_Terima kasih telah menggunakan layanan Gintara Net._\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }

    // ────────────────────────────────────────────────────────────────
    //  11. Akun diaktifkan kembali oleh SuperAdmin
    // ────────────────────────────────────────────────────────────────

    public static function akunDiaktifkanKembali(
        string $nama,
        string $namaWilayah,
        string $tanggal
    ): string {
        $pesan  = "✅ *Akun Anda Telah Diaktifkan Kembali*\n\n";
        $pesan .= "Halo, *{$nama}*!\n\n";
        $pesan .= "Kabar baik! Akun layanan Panic Button Anda di wilayah *{$namaWilayah}* telah *diaktifkan kembali* oleh tim administrator pada tanggal *{$tanggal}*.\n\n";
        $pesan .= "📌 *Yang Dapat Anda Lakukan Sekarang:*\n";
        $pesan .= "• Login kembali ke sistem menggunakan email & password Anda\n";
        $pesan .= "• Perangkat Panic Button Anda kini aktif kembali\n";
        $pesan .= "• Seluruh riwayat dan data Anda tetap tersimpan\n\n";
        $pesan .= "💬 Jika ada pertanyaan atau kendala saat login, silakan hubungi tim administrator Gintara Net.\n\n";
        $pesan .= "🔗 Login ke akun Anda: https://sos.gintara.net/login\n\n";
        $pesan .= "_Salam,_\n";
        $pesan .= "_Tim Gintara Net_ 🚨";
        return $pesan;
    }
}
