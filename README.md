# Panic Button Gintara Net

Sistem manajemen tombol darurat (panic button) berbasis web yang memungkinkan pelanggan untuk melaporkan keadaan darurat dengan cepat kepada tim respons Gintara Net. Sistem ini mengintegrasikan real-time monitoring, notifikasi WhatsApp, dan koordinasi tim admin untuk respons cepat.

## 🎯 Deskripsi Project

Panic Button Gintara adalah aplikasi web modern yang dirancang untuk memberikan solusi komunikasi darurat yang efisien. Pelanggan dapat menekan tombol panic untuk segera mendapatkan bantuan dari tim Gintara Net dengan sistem notifikasi real-time dan tracking lokasi terintegrasi.

## ✨ Fitur Utama

### Untuk End User (Pelanggan)

- **Dashboard Pelanggan**: Interface user-friendly untuk monitoring status panic button
- **Tombol Panic**: Fitur one-click untuk melaporkan keadaan darurat
- **Tracking Lokasi Real-time**: Menampilkan koordinat latitude/longitude saat panic button ditekan
- **Riwayat Alarm**: Log lengkap semua aktivitas panic button dengan timeline
- **Profil Pelanggan**: Manajemen data pribadi, nomor HP, dan data KTP
- **Notifikasi Real-time**: Broadcast menggunakan Laravel Reverb untuk update status

### Untuk Admin

- **Dashboard Admin**: Overview laporan dan tugas yang ditugaskan
- **Manajemen Laporan**: View detail laporan panic button dengan lokasi dan foto
- **Manajemen Tugas**: Tracking tugas yang diberikan oleh super admin
- **Update Status Alarm**: Ubah status alarm (Menunggu → Diproses → Selesai)
- **Dokumentasi Foto**: Upload dan manajemen foto dokumentasi lapangan

### Untuk Super Admin

- **Dashboard Super Admin**: Monitoring komprehensif seluruh sistem
- **Monitoring Real-time**: Peta dan daftar semua panic buttons dengan status
- **Manajemen Data Pelanggan**: CRUD pelanggan, data KTP, dan asosiasi panic button
- **Manajemen Data Admin**: Tambah, edit, hapus admin dengan role management
- **Manajemen Wilayah**: Kelola area coverage service
- **Data Pendaftar**: Verifikasi dan proses pending registration
- **Laporan Pelanggan**: Analytics dan reporting per pelanggan
- **Kinerja Admin**: Metrics kinerja tim admin berdasarkan respon time
- **Tugasan Admin**: Delegasi tugas kepada tim admin

## 🛠️ Tech Stack

### Backend

- **Framework**: Laravel 12.0
- **Database**: MySQL
- **Queue**: Database Queue
- **Broadcast**: Laravel Reverb (WebSocket)
- **Message Queue**: MQTT (broker.emqx.io)
- **Email**: SMTP (Gmail)
- **SMS/WhatsApp**: Fonnte API

### Frontend

- **Templating**: Blade PHP
- **CSS**: Tailwind CSS
- **JS Framework**: Alpine.js
- **Chart Library**: Highcharts
- **Alert UI**: SweetAlert2
- **Scrollbar**: jQuery Custom Scrollbar
- **Build Tool**: Vite

### DevOps

- **PHP**: ^8.2
- **Concurrent**: npm concurrently untuk development

## 📋 Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Node.js 16+ dan npm
- Composer
- Git

## 🚀 Instalasi & Setup

### 1. Clone Repository

```bash
git clone https://github.com/BM-LEOZARD/panic-button-gintara.git
cd panic-button-gintara
```

### 2. Install Dependencies

```bash
# Backend dependencies
composer install

# Frontend dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env` untuk konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=panic-button-gintara
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Migration

```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
```

*Pastikan data di file UserSeeder sudah anda ganti

### 6. Setup Storage Link

```bash
php artisan storage:link
```

Perintah ini membuat symbolic link untuk folder `storage/app/public` ke `public/storage`, memungkinkan akses publik ke file-file:
- `foto_ktp`: Foto KTP pelanggan
- `dokumen_foto`: Dokumentasi foto lapangan dari admin

### 7. Setup Services

Pastikan konfigurasi di `.env`:

```env
# Timezone
APP_TIMEZONE=Asia/Jakarta

# Fonnte (WhatsApp API)
FONNTE_TOKEN=your_token_here

# MQTT Configuration
MQTT_HOST=broker.emqx.io
MQTT_PORT=1883
MQTT_TOPIC=
MQTT_CLIENT_ID_PREFIX=

# Reverb (WebSocket)
BROADCAST_CONNECTION=reverb
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=9090

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="app name"
```

## 🏃 Running Application

### Development Mode

```bash
npm run dev
```

Atau jalankan semua service secara bersamaan:

```bash
php artisan dev
```

Command ini akan menjalankan:

- Laravel Development Server (Port 8000)
- Queue Listener
- Laravel Pail (Logs)
- Vite Dev Server

### Production Mode

```bash
npm run build
php artisan serve
```

## 🔐 Authentication & Authorization

### User Roles

1. **End User (Pelanggan)**: Customer yang menggunakan panic button
2. **Admin**: Tim respons yang menangani alarm
3. **Super Admin**: Pengelola sistem

## 📊 Database Schema

### Key Models

- **User**: User system dengan roles
- **Pelanggan**: Data pelanggan/customer
- **PanicButton**: Device panic button dengan identifikasi unik (DisID, GUID)
- **LokasiPanicButton**: Koordinat lokasi saat panic button ditekan
- **AlarmPanicButton**: Riwayat alarm dan status penanganan
- **TugasAdmin**: Delegasi tugas kepada admin
- **Pendaftaran**: Pending registrasi pelanggan baru
- **GambarDataPelanggan**: Foto KTP pelanggan
- **DokumenFoto**: Dokumentasi foto lapangan dari admin

## 🔄 API & Real-time Features

### WebSocket Events (Reverb)

- `PanicButtonTriggered`: Notifikasi saat panic button ditekan
- `AlarmDiproses`: Notifikasi saat admin mulai proses alarm
- `AlarmSelesai`: Notifikasi saat alarm selesai ditangani

### MQTT Topics

- `panic/button`: Komunikasi dengan device panic button

### Notification Services

- **WhatsApp**: Notifikasi WhatsApp via Fonnte API
- **Email**: Notifikasi email untuk admin dan super admin
- **Real-time**: Broadcast via Reverb untuk update status real-time

## 🧪 Testing

Jalankan unit tests:

```bash
php artisan test
```

Atau dengan PHPUnit:

```bash
./vendor/bin/phpunit
```

## 📝 Environment Variables
AWr9JaTuECNHo4naZxHf
| Variable         | Deskripsi                   | Example                |
| ---------------- | --------------------------- | ---------------------- |
| `FONNTE_TOKEN`   | Token Fonnte untuk WhatsApp | `ABc1DeFgHIJKl2mnOpQr` |
| `MQTT_HOST`      | MQTT Broker Host            | `broker.emqx.io`       |
| `REVERB_APP_KEY` | WebSocket App Key           | `abc123`    |
| `MAIL_USERNAME`  | Email SMTP                  | `user@example.com`  |
| `DB_DATABASE`    | Nama database               | `panic-button` |

## 🚨 Common Issues & Solutions

### Q: Panic button tidak menerima notifikasi?

**A**: Pastikan:

- Queue listener berjalan: `php artisan queue:work`
- Reverb WebSocket service berjalan: `php artisan reverb:start`
- Fonnte token valid di `.env`
- Database queue table ada: `php artisan migrate`

### Q: Real-time broadcast tidak bekerja?

**A**: Pastikan:

- Reverb service berjalan
- Client terhubung ke WebSocket yang benar
- Browser mendukung WebSocket

### Q: MQTT connection failed?

**A**: Periksa:

- MQTT host dan port di `.env` sudah benar
- Internet connection aktif
- MQTT_CLIENT_ID unik

## 🤝 Contributing

1. Fork repository
2. Buat branch fitur: `git checkout -b feature/AmazingFeature`
3. Commit changes: `git commit -m 'Add AmazingFeature'`
4. Push ke branch: `git push origin feature/AmazingFeature`
5. Buka Pull Request

## 📄 License

Project ini dilisensikan di bawah MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## 👥 Support & Contact

Untuk pertanyaan atau support:

- Email: bmpr0j3ct@gmail.com

## 📦 Version

Current Version: 1.0.0

---

**Last Updated**: Maret 2026
**Maintainer**: Gintara Net Development Team
