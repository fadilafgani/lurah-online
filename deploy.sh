#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# Deploy / update aplikasi di VPS.
# Jalankan dari direktori proyek di VPS:  ./deploy.sh
# ---------------------------------------------------------------------------
set -euo pipefail

cd "$(dirname "$0")"

info() { printf '\033[1;34m==>\033[0m %s\n' "$1"; }
fail() { printf '\033[1;31mERROR:\033[0m %s\n' "$1" >&2; exit 1; }

[ -f .env ] || fail ".env tidak ditemukan. Jalankan: cp .env.production.example .env && nano .env"

grep -q '^APP_KEY=.\+' .env || fail "APP_KEY masih kosong di .env"
grep -q '^APP_DOMAIN=.\+' .env || fail "APP_DOMAIN masih kosong di .env"

if git rev-parse --git-dir >/dev/null 2>&1; then
    info "Mengambil kode terbaru dari git"
    git pull --ff-only
fi

info "Build image (composer install + vite build di dalam container)"
docker compose build --pull

info "Menjalankan container versi baru"
docker compose up -d --remove-orphans

info "Menunggu container sehat"
sleep 5
docker compose ps

info "Membersihkan image lama yang tidak terpakai"
docker image prune -f

info "Deploy selesai. Cek log dengan: docker compose logs -f"
