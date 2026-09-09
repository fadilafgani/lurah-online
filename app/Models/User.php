<?php

namespace App\Models;


use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Admin kelurahan: boleh mengelola akun unit.
     */
    public const ROLE_ADMIN = 'admin';

    /**
     * Akun unit: hanya menangani pengaduan yang ditugaskan ke unitnya, dan
     * tidak boleh melihat halaman maupun menu "Akun Unit".
     */
    public const ROLE_UNIT = 'unit';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unit',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUnit(): bool
    {
        return $this->role === self::ROLE_UNIT;
    }

    /**
     * Batasi query ke akun unit saja.
     */
    public function scopeUnit(Builder $query): void
    {
        $query->where('role', self::ROLE_UNIT);
    }

    /**
     * Bentuk akun untuk dikirim ke halaman admin: dipakai baris daftar akun,
     * kartu "sedang masuk", dan penentuan menu yang boleh tampil di navbar.
     */
    public function toDisplayArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'unit' => $this->unit,
            // Hanya akun unit yang boleh dipindah, direset, atau dihapus dari
            // halaman akun unit; baris akun admin dirender tanpa tombol aksi.
            'is_unit' => $this->isUnit(),
            'is_admin' => $this->isAdmin(),
            'label' => $this->isUnit() ? $this->unit : 'Admin',
            'dibuat' => $this->created_at?->format('j/n/Y'),
        ];
    }
}
