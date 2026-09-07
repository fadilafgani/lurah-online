#!/bin/sh
# ---------------------------------------------------------------------------
# Entrypoint aplikasi: siapkan storage, cache konfigurasi, jalankan migrasi
# (opsional), lalu serahkan proses ke perintah utama (php-fpm).
# ---------------------------------------------------------------------------
set -eu

log() { printf '[entrypoint] %s\n' "$1"; }

cd /var/www/html

# --- Validasi konfigurasi wajib -------------------------------------------
if [ -z "${APP_KEY:-}" ]; then
    log "FATAL: APP_KEY belum diset. Jalankan 'php artisan key:generate --show' lalu isi di .env"
    exit 1
fi

# --- Direktori runtime (bisa berupa volume yang baru & kosong) -------------
mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# Symlink public/storage -> storage/app/public (idempoten)
if [ ! -e public/storage ]; then
    php artisan storage:link --quiet || log "WARN: storage:link gagal, lanjut"
fi

# --- Cache framework -------------------------------------------------------
# Dibangun saat start (bukan saat build) supaya nilai .env runtime terpakai.
log "Membangun cache konfigurasi, route, dan view..."
php artisan optimize:clear --quiet || true
php artisan package:discover --quiet
php artisan config:cache --quiet
php artisan route:cache --quiet
php artisan view:cache --quiet
php artisan event:cache --quiet

# artisan dijalankan sebagai root, jadi file cache yang baru dibuat harus
# dikembalikan ke www-data agar worker php-fpm bisa menulis/membacanya.
chown -R www-data:www-data storage bootstrap/cache

# --- Migrasi database (opsional) -------------------------------------------
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    log "Menunggu database siap..."
    i=0
    until php artisan migrate:status >/dev/null 2>&1 || [ "$i" -ge 30 ]; do
        i=$((i + 1))
        sleep 2
    done

    if [ "$i" -ge 30 ]; then
        log "FATAL: database tidak dapat dihubungi setelah 60 detik"
        exit 1
    fi

    log "Menjalankan migrasi..."
    php artisan migrate --force --no-interaction
fi

log "Siap. Menjalankan: $*"
exec "$@"
