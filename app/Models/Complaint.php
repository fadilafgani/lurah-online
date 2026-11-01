<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_code',
        'resident_id',
        'name',
        'is_anonymous',
        'phone',
        'email',
        'title',
        'description',
        'location',
        'category',
        'photo',
        'status',
        'unit',
        'handling_note',
        'response_note',
        'result_photo',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
        ];
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function getPublicReporterNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonim';
        }

        return $this->resident?->name ?? $this->name ?? 'Anonim';
    }

    public function getAdminReporterNameAttribute(): string
    {
        $name = $this->resident?->name ?? $this->name ?? 'Anonim';
        if ($this->is_anonymous) {
            return $name . ' (Anonim di Publik)';
        }

        return $name;
    }

    /**
     * ponytail: rule-based visibility check, avoids separate permission tables.
     * Menentukan apakah Foto Hasil dapat dilihat oleh user / warga yang sedang mengakses.
     */
    public function isResultPhotoVisibleTo($user = null, $resident = null): bool
    {
        if (!$this->result_photo) {
            return false;
        }

        // 1. Admin dan Petugas Unit yang ditugaskan dapat melihat
        $isAdmin = $user && (method_exists($user, 'isAdmin') ? $user->isAdmin() : $user->role === 'admin');
        $isAuthorizedUnit = $user && (method_exists($user, 'isUnit') ? $user->isUnit() : $user->role === 'unit')
            && filled($this->unit) && $user->unit === $this->unit;

        if ($isAdmin || $isAuthorizedUnit) {
            return true;
        }

        // 2. Untuk warga atau publik: hanya boleh tampil jika status laporan sudah 'completed' (aturan publikasi)
        if ($this->status !== 'completed') {
            return false;
        }

        // 3. Jika laporan terikat pada akun warga, hanya warga pemilik laporan yang berhak melihat
        if ($this->resident_id) {
            return $resident && (int) $resident->id === (int) $this->resident_id;
        }

        // 4. Laporan tanpa akun warga (legacy/guest) tampil ke pemegang kode tiket jika status completed
        return true;
    }
}
