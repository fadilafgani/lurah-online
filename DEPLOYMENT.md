# Deployment ke VPS dengan Docker

Stack: **PHP-FPM 8.3 (Alpine)** + **Caddy 2** (HTTP-only; HTTPS diterminasi oleh proxy
di depan seperti Cloudflare atau Nginx), database MySQL eksternal (Aiven). Aset Vite dan dependency Composer dibuild di dalam image, jadi VPS
tidak perlu PHP, Node, atau Composer terpasang — hanya Docker.

## Ringkasan file

| File | Fungsi |
|---|---|
| `Dockerfile` | Multi-stage: build aset Vite → install vendor Composer → image PHP-FPM → image Caddy |
| `docker-compose.yml` | Dua service: `app` (PHP-FPM) dan `web` (Caddy, port 80) |
| `docker/Caddyfile` | Reverse proxy ke PHP-FPM, header keamanan, cache aset (tanpa TLS) |
| `docker/entrypoint.sh` | Siapkan storage, cache config/route/view, jalankan migrasi |
| `docker/php/php.ini` | Setting PHP produksi (OPcache, limit upload, error ke log) |
| `docker/php/www.conf` | Pool PHP-FPM (jumlah worker, log ke stdout) |
| `.env.production.example` | Template `.env` produksi |
| `deploy.sh` | Skrip deploy/update di VPS (juga dipanggil oleh CI) |
| `.github/workflows/ci.yml` | Test PHP + build aset, jalan di tiap PR & branch |
| `.github/workflows/deploy.yml` | Deploy otomatis ke VPS saat push ke `main` |

---

## 1. Persiapan VPS (sekali saja)

Login sebagai root, lalu:

```bash
# Install Docker + plugin compose
curl -fsSL https://get.docker.com | sh

# Buat user non-root untuk aplikasi
adduser --disabled-password --gecos "" deploy
usermod -aG docker deploy

# Firewall: hanya SSH + HTTP
ufw allow OpenSSH && ufw allow 80 && ufw --force enable
```

Port 443 tidak dipakai lagi — Caddy hanya melayani HTTP di port 80 dan HTTPS
diterminasi oleh proxy di depannya.

> **Penting.** Karena Caddy mempercayai header `X-Forwarded-*`, port 80 sebaiknya
> hanya bisa diakses dari IP proxy, bukan dari seluruh internet. Kalau memakai
> Cloudflare (proxied / orange cloud), batasi dengan:
>
> ```bash
> ufw delete allow 80
> for ip in $(curl -s https://www.cloudflare.com/ips-v4) \
>            $(curl -s https://www.cloudflare.com/ips-v6); do
>     ufw allow from "$ip" to any port 80 proto tcp
> done
> ufw reload
> ```

## 2. Arahkan domain

Arahkan DNS ke proxy/VPS sesuai setup Anda:

- **Cloudflare (proxied):** buat A record ke IP VPS lalu aktifkan orange cloud, dan set
  SSL/TLS mode ke **Full**. Sertifikat untuk pengunjung disediakan Cloudflare.
- **Nginx/load balancer sendiri:** arahkan DNS ke proxy tersebut, lalu proxy
  meneruskan ke `http://<IP_VPS>:80` dengan header `X-Forwarded-Proto: https`,
  `X-Forwarded-For`, dan `Host` yang benar.

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
| `APP_DOMAIN` | Domain tanpa skema, dipakai Caddy sebagai site address |
| `TRUSTED_PROXIES` | Rentang IP proxy yang dipercaya (`private_ranges`, atau daftar IP Cloudflare dipisah spasi) |
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

# Lewat proxy (yang dilihat pengunjung)
curl -I https://domain-anda/up    # harus 200

# Langsung ke Caddy, melewati proxy
curl -I -H "Host: domain-anda" http://<IP_VPS>/up   # harus 200
```

---

## 7. Deploy otomatis lewat GitHub Actions

Setelah langkah 1–6 berhasil sekali secara manual, deploy bisa diotomatiskan.
Alurnya:

```
push / PR ke branch mana pun  ->  ci.yml: test PHP + build aset Vite
push (atau merge) ke main     ->  deploy.yml: test -> SSH ke VPS -> ./deploy.sh <sha> -> cek /up
```

Build image tetap terjadi **di VPS** (lihat kebutuhan RAM ± 2 GB di catatan bawah);
GitHub Actions hanya memicu dan memverifikasinya.

### 7.1 Buat kunci SSH khusus deploy

Kunci ini terpisah dari kunci pribadi Anda supaya bisa dicabut sendiri kalau bocor.
Jalankan **di laptop**, bukan di VPS:

```bash
ssh-keygen -t ed25519 -f ~/.ssh/lurah-deploy -C "github-actions" -N ""

# Pasang kunci publiknya ke user deploy di VPS
ssh-copy-id -i ~/.ssh/lurah-deploy.pub deploy@<IP_VPS>

# Uji: harus bisa masuk tanpa passphrase
ssh -i ~/.ssh/lurah-deploy deploy@<IP_VPS> "docker compose version"
```

### 7.2 Isi secrets di GitHub

Buka **Settings → Secrets and variables → Actions → New repository secret**:

| Secret | Wajib | Nilai | Cara mendapatkan |
|---|---|---|---|
| `VPS_HOST` | ya | IP atau hostname VPS | — |
| `VPS_USER` | ya | `deploy` | user non-root dari langkah 1 |
| `VPS_APP_PATH` | ya | `/home/deploy/lurah-online` | hasil `pwd` di direktori proyek di VPS |
| `VPS_SSH_KEY` | ya | isi **kunci privat** | `cat ~/.ssh/lurah-deploy` — salin utuh termasuk baris `BEGIN`/`END` |
| `VPS_SSH_HOST_KEY` | sangat disarankan | host key VPS | `ssh-keyscan -H <IP_VPS>` |
| `VPS_PORT` | tidak | port SSH kalau bukan `22` | — |

> `VPS_SSH_HOST_KEY` mengunci identitas server. Tanpa itu workflow jatuh ke
> `ssh-keyscan` (trust-on-first-use) dan menampilkan peringatan — artinya runner
> mempercayai server apa pun yang menjawab di alamat itu.

### 7.3 Jalankan

Merge ke `main`, atau **Actions → Deploy ke VPS → Run workflow** untuk memicu manual.

Workflow akan gagal (dan menampilkan 80 baris terakhir log container `app`) kalau
`https://domain/up` tidak membalas 200 dalam 60 detik setelah container naik.

### 7.4 Rollback

`deploy.sh` menerima commit/tag sebagai argumen, jadi rollback = deploy ulang commit lama:

```bash
ssh deploy@<IP_VPS>
cd lurah-online
git log --oneline -10          # cari commit yang masih sehat
./deploy.sh <sha-commit-lama>
```

Perlu diingat: rollback kode **tidak** membatalkan migrasi database yang sudah jalan.
Kalau rilis terakhir mengandung migrasi yang merusak, turunkan dulu dengan
`docker compose exec app php artisan migrate:rollback --step=1`.

### 7.5 Kalau deploy gagal

| Gejala di Actions | Penyebab & solusi |
|---|---|
| `Permission denied (publickey)` | `VPS_SSH_KEY` tidak lengkap (baris `BEGIN`/`END` terpotong), atau kunci publik belum ada di `~/.ssh/authorized_keys` user `deploy` |
| `Host key verification failed` | `VPS_SSH_HOST_KEY` salah atau host key VPS berubah (VPS di-rebuild). Ambil ulang dengan `ssh-keyscan -H <IP_VPS>` |
| `ERROR: .env tidak ditemukan` | Langkah 3 belum dikerjakan di VPS — CI tidak pernah membuat `.env`, file itu hanya ada di server |
| `permission denied while trying to connect to the Docker daemon` | User `deploy` belum masuk grup docker: `usermod -aG docker deploy`, lalu login ulang |
| `GAGAL: /up tidak merespons 200` | Baca log container yang ikut tercetak. Penyebab umum sama dengan tabel Troubleshooting di bawah |
| Build kehabisan memori (`Killed`) | RAM VPS kurang saat `npm ci`. Aktifkan swap (lihat catatan **Kebutuhan RAM**) |

---

## Operasional harian

```bash
# Update ke versi terbaru
./deploy.sh

# Lihat log
docker compose logs -f app        # PHP & Laravel
docker compose logs -f web        # log akses Caddy

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

**TLS ada di proxy, bukan di Caddy.** Caddy dijalankan dengan `auto_https off` dan
hanya mendengarkan HTTP di port 80, jadi tidak ada sertifikat yang dikelola di VPS —
pembaruan sertifikat jadi tanggung jawab Cloudflare / proxy Anda. Konsekuensinya:

- `SESSION_SECURE_COOKIE=true` dan `APP_URL=https://...` tetap benar, karena pengunjung
  memang mengakses lewat HTTPS di proxy.
- Laravel mempercayai `X-Forwarded-*` (`trustProxies` di `bootstrap/app.php`) supaya
  URL, redirect, dan IP pengunjung terbaca benar. Karena itu port 80 wajib dibatasi ke
  IP proxy lewat firewall (lihat langkah 1).
- Volume `caddy_data` / `caddy_config` sudah tidak dipakai. Kalau sebelumnya pernah
  deploy dengan Let's Encrypt, sisa volumenya bisa dibuang:
  `docker volume rm lurah-online_caddy_data lurah-online_caddy_config`

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
| Redirect loop, atau aset/CSS gagal load | Proxy tidak mengirim `X-Forwarded-Proto: https`, atau `TRUSTED_PROXIES` tidak mencakup IP proxy. Di Cloudflare pastikan SSL/TLS mode **Full**, bukan Flexible. |
| IP pengunjung tercatat sebagai IP proxy | `TRUSTED_PROXIES` belum diisi rentang IP proxy. Cek `docker compose logs web`. |
| 502 / tidak bisa diakses dari proxy | Port 80 diblokir firewall, atau proxy menunjuk ke port yang salah (lihat `WEB_HTTP_PORT`). |
| `SQLSTATE[HY000] [2002] Connection refused` | IP VPS belum masuk allowlist Aiven, atau `DB_*` salah. |
| Halaman 500 tanpa detail | Normal di produksi (`APP_DEBUG=false`). Detail ada di `docker compose logs app`. |
| Container `app` restart terus | Cek `docker compose logs app`. Penyebab umum: `APP_KEY` kosong, atau nama route duplikat sehingga `route:cache` gagal. |
| Perubahan Blade/CSS tidak muncul | Cache dibangun saat build image. Jalankan `./deploy.sh` (rebuild), bukan hanya `restart`. |
| Gambar upload 404 | Volume `uploads` tidak ter-mount di service `web`. Cek `docker compose config`. |
