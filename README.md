# KaDes Online (Kepala Desa Online)

**Platform Layanan Digital dan Pengaduan Masyarakat Desa Banjarsari, Garut.**

---

## 📌 Tentang Aplikasi

**KaDes Online** adalah sistem platform layanan digital dan pengaduan aspirasi masyarakat warga Desa Banjarsari, Kecamatan Bayongbong, Kabupaten Garut. Aplikasi ini dirancang untuk mempermudah warga dalam menyampaikan pengaduan atau keluhan fasilitas umum secara transparan, melacak status tindak lanjut penanganan, serta memudahkan perangkat desa dan unit-unit penanganan dalam merespons permasalahan di masyarakat.

### Fitur Utama

- **Pengaduan Warga Berbasis NIK**: Pengaduan resmi warga yang terdata di Desa Banjarsari dengan proteksi pembatasan kuota dan lokasi.
- **Upload Bukti & Penanganan**: Dilengkapi unggah foto bukti keluhan dari kamera/galeri serta upload foto bukti hasil penanganan dari petugas lapangan.
- **Pelacakan Tiket Realtime**: Lacak progres tindak lanjut pengaduan melalui kode tiket unik.
- **Laporan Publik**: Tampilan transparansi pengaduan yang telah diselesaikan untuk publik.
- **Dashboard Admin Desa & Unit**: Manajemen laporan, disposisi penugasan ke unit terkait, filter kata terlarang, notifikasi pengaduan baru, dan pengelolaan data kependudukan warga.

---

## 🛠️ Persyaratan Sistem

- PHP >= 8.3
- Composer >= 2.0
- Node.js & npm
- MySQL / SQLite

---

## 🚀 Panduan Instalasi Lokal

1. Salin repositori dan masuk ke direktori:
   ```bash
   git clone <URL_REPO>
   cd lurah-online
   ```

2. Instal dependensi PHP dan Node:
   ```bash
   composer install
   npm install
   ```

3. Siapkan environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Jalankan migrasi database:
   ```bash
   php artisan migrate
   ```

5. Build aset frontend:
   ```bash
   npm run build
   ```

6. Jalankan server lokal:
   ```bash
   npm run dev
   # dan di terminal lain:
   php artisan serve
   ```

---

## 🧪 Menjalankan Pengujian

```bash
php artisan test
```

---

## 📄 Lisensi

Hak Cipta &copy; {{ date('Y') }} Pemerintah Desa Banjarsari, Garut. Dilindungi undang-undang.
