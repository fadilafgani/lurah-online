# Deployment ke VPS dengan Docker

Stack: **PHP-FPM 8.3 (Alpine)** + **Caddy 2** (auto-HTTPS Let's Encrypt), database MySQL
eksternal (Aiven). Aset Vite dan dependency Composer dibuild di dalam image, jadi VPS
tidak perlu PHP, Node, atau Composer terpasang — hanya Docker.

## Ringkasan file

| File | Fungsi |
|---|---|
| `Dockerfile` | Multi-stage: build aset Vite → install vendor Composer → image PHP-FPM → image Caddy |
| `docker-compose.yml` | Dua service: `app` (PHP-FPM) dan `web` (Caddy + TLS) |
| `docker/Caddyfile` | Reverse proxy ke PHP-FPM, auto-HTTPS, header keamanan, cache aset |
| `docker/entrypoint.sh` | Siapkan storage, cache config/route/view, jalankan migrasi |
| `docker/php/php.ini` | Setting PHP produksi (OPcache, limit upload, error ke log) |
| `docker/php/www.conf` | Pool PHP-FPM (jumlah worker, log ke stdout) |
| `.env.production.example` | Template `.env` produksi |
| `deploy.sh` | Skrip deploy/update di VPS |

---

## 1. Persiapan VPS (sekali saja)

Login sebagai root, lalu:

```bash
# Install Docker + plugin compose
curl -fsSL https://get.docker.com | sh

# Buat user non-root untuk aplikasi
adduser --disabled-password --gecos "" deploy
usermod -aG docker deploy

# Firewall: hanya SSH, HTTP, HTTPS
ufw allow OpenSSH && ufw allow 80 && ufw allow 443 && ufw --force enable
```

## 2. Arahkan domain

Buat DNS record ke IP VPS **sebelum** menjalankan container — Caddy butuh domain yang
sudah resolve untuk menerbitkan sertifikat:

```
A    lurah-online.example.com    <IP_VPS>
```

Verifikasi: `dig +short lurah-online.example.com`

## 3. Ambil kode & isi konfigurasi

```bash
su - deploy
git clone <URL_REPO> lurah-online
cd lurah-online

cp .env.production.example .env
nano .env
```

Yang **wajib** diisi di `.env`:

| Variabel | Keterangan |
|---|---|
| `APP_KEY` | Lihat langkah 4 |
| `APP_URL` | `https://domain-anda` |
| `APP_DOMAIN` | Domain tanpa skema, dipakai Caddy untuk menerbitkan sertifikat |
| `LETSENCRYPT_EMAIL` | Email notifikasi sertifikat |
| `DB_HOST` / `DB_PORT` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | Kredensial Aiven |
| `MAIL_*` | Kredensial SMTP (untuk fitur lupa kata sandi) |

Pastikan `APP_ENV=production` dan `APP_DEBUG=false`.

## 4. Generate APP_KEY

```bash
docker compose build app
docker compose run --rm --no-deps app php artisan key:generate --show
```

Salin hasilnya (termasuk prefiks `base64:`) ke `APP_KEY=` di `.env`.

## 5. Izinkan IP VPS di Aiven

Di konsol Aiven → service database → **Allowed IP addresses**, tambahkan IP publik VPS.
Cek IP: `curl -s ifconfig.me`

## 6. Deploy

```bash
./deploy.sh
```

Skrip ini melakukan `git pull`, build image, dan `docker compose up -d`. Saat container
`app` start, entrypoint otomatis: menyiapkan direktori storage, membuat symlink
`public/storage`, membangun cache config/route/view, lalu menjalankan
`php artisan migrate --force` (karena `RUN_MIGRATIONS=true`).

Cek hasilnya:

```bash
docker compose ps                 # kedua service harus "healthy"
docker compose logs -f            # ikuti log
curl -I https://domain-anda/up    # harus 200
```

Sertifikat TLS diterbitkan otomatis pada request pertama (butuh beberapa detik).

---

## Operasional harian

```bash
# Update ke versi terbaru
./deploy.sh

# Lihat log
docker compose logs -f app        # PHP & Laravel
docker compose logs -f web        # akses & TLS

# Jalankan perintah artisan
docker compose exec app php artisan migrate:status
docker compose exec app php artisan tinker

# Restart tanpa rebuild
docker compose restart

# Mode maintenance
docker compose exec app php artisan down --render="errors::503"
docker compose exec app php artisan up
```

### Backup

Yang perlu di-backup ada dua: **upload pengguna** (volume Docker) dan **database**
(dikelola Aiven, sudah ada automated backup di sana).

```bash
# Backup file upload
docker run --rm \
  -v lurah-online_uploads:/data:ro \
  -v "$PWD":/backup \
  alpine tar czf /backup/uploads-$(date +%F).tar.gz -C /data .

# Restore
docker run --rm \
  -v lurah-online_uploads:/data \
  -v "$PWD":/backup \
  alpine tar xzf /backup/uploads-2026-01-01.tar.gz -C /data
```

Simpan juga salinan `.env` di tempat aman — file ini tidak masuk git maupun image.

---

## Catatan penting

**Migrasi otomatis.** `RUN_MIGRATIONS=true` menjalankan migrasi setiap container start.
Kalau ingin mengontrol manual, set `RUN_MIGRATIONS=false` di `.env` lalu jalankan
`docker compose exec app php artisan migrate --force` sendiri.

**Data upload persisten lewat volume.** Volume `uploads` di-mount ke
`storage/app/public` (baca-tulis) di container `app`, dan ke `public/storage`
(baca-saja) di container `web` supaya Caddy bisa melayani filenya. Jangan hapus volume
ini — `docker compose down -v` akan menghapus semua upload pengguna. Gunakan
`docker compose down` (tanpa `-v`).

**Sertifikat TLS persisten.** Volume `caddy_data` menyimpan sertifikat. Jangan hapus,
karena Let's Encrypt punya rate limit (5 sertifikat per domain per minggu).

**Queue worker belum diperlukan.** Aplikasi ini belum punya job/notification yang
di-queue, jadi tidak ada service worker. Kalau nanti menambahkan job, tambahkan service
berikut ke `docker-compose.yml`:

```yaml
  queue:
    build: { context: ., target: app }
    image: lurah-online-app:latest
    restart: unless-stopped
    env_file: .env
    environment:
      RUN_MIGRATIONS: "false"
    command: php artisan queue:work --tries=3 --max-time=3600
    volumes:
      - uploads:/var/www/html/storage/app/public
    networks: [internal]
```

**Kebutuhan RAM.** Build menjalankan `npm ci` + `composer install`, butuh ± 2 GB RAM.
Kalau VPS lebih kecil, aktifkan swap:

```bash
fallocate -l 2G /swapfile && chmod 600 /swapfile && mkswap /swapfile && swapon /swapfile
echo '/swapfile none swap sw 0 0' >> /etc/fstab
```

**Menaikkan kapasitas.** Default pool PHP-FPM `pm.max_children = 20` (± 800 MB saat
penuh). Sesuaikan di `docker/php/www.conf` jika RAM VPS lebih besar, lalu rebuild.

**Rotasi log.** Log container ditulis oleh Docker; batasi ukurannya dengan membuat
`/etc/docker/daemon.json`:

```json
{ "log-driver": "json-file", "log-opts": { "max-size": "10m", "max-file": "3" } }
```

lalu `systemctl restart docker`.

---

## Troubleshooting

| Gejala | Penyebab & solusi |
|---|---|
| Sertifikat gagal diterbitkan | DNS belum mengarah ke VPS, atau port 80/443 diblokir firewall. Cek `docker compose logs web`. |
| `SQLSTATE[HY000] [2002] Connection refused` | IP VPS belum masuk allowlist Aiven, atau `DB_*` salah. |
| Halaman 500 tanpa detail | Normal di produksi (`APP_DEBUG=false`). Detail ada di `docker compose logs app`. |
| Container `app` restart terus | Cek `docker compose logs app`. Penyebab umum: `APP_KEY` kosong, atau nama route duplikat sehingga `route:cache` gagal. |
| Perubahan Blade/CSS tidak muncul | Cache dibangun saat build image. Jalankan `./deploy.sh` (rebuild), bukan hanya `restart`. |
| Gambar upload 404 | Volume `uploads` tidak ter-mount di service `web`. Cek `docker compose config`. |
