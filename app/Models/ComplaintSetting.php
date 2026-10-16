<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ComplaintSetting extends Model
{
    protected $fillable = [
        'is_daily_limit_active',
        'max_daily_complaints',
        'last_updated_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_daily_limit_active' => 'boolean',
            'max_daily_complaints' => 'integer',
        ];
    }

    public function lastUpdatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by_user_id');
    }

    /**
     * Dapatkan instance pengaturan singleton atau buat default awal jika belum ada
     */
    public static function getSettings(): self
    {
        return static::firstOrCreate([], [
            'is_daily_limit_active' => true,
            'max_daily_complaints' => 2,
        ]);
    }

    /**
     * Tentukan rentang waktu 00:00:00 - 23:59:59.999999 hari berjalan pada zona waktu Asia/Jakarta,
     * dikonversikan ke zona waktu database aplikasi (UTC).
     */
    public static function getTodayRangeJakarta(): array
    {
        $nowJakarta = Carbon::now('Asia/Jakarta');
        $start = $nowJakarta->copy()->startOfDay()->setTimezone(config('app.timezone', 'UTC'));
        $end = $nowJakarta->copy()->endOfDay()->setTimezone(config('app.timezone', 'UTC'));

        return [$start, $end];
    }

    /**
     * Hitung jumlah pengaduan yang telah dibuat oleh akun warga hari ini di zona waktu Asia/Jakarta.
     * Menggunakan withTrashed() agar pengaduan yang dihapus/diarsipkan tidak otomatis mengembalikan kuota.
     */
    public static function getResidentDailyCount(int $residentId): int
    {
        [$start, $end] = static::getTodayRangeJakarta();

        return Complaint::withTrashed()
            ->where('resident_id', $residentId)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    }

    /**
     * Periksa kuota pengaduan harian untuk warga tertentu
     */
    public static function checkQuota(int $residentId): array
    {
        $setting = static::getSettings();
        $count = static::getResidentDailyCount($residentId);

        $limit = $setting->max_daily_complaints;
        $isActive = (bool) $setting->is_daily_limit_active;
        $isExceeded = $isActive && ($count >= $limit);
        $remaining = $isActive ? max(0, $limit - $count) : null;

        return [
            'is_active' => $isActive,
            'max_limit' => $limit,
            'used_today' => $count,
            'remaining' => $remaining,
            'is_exceeded' => $isExceeded,
        ];
    }
}
