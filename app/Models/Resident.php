<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Resident extends Authenticatable
{
    use HasFactory, Notifiable;

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'nik',
        'name',
        'address',
        'rt',
        'rw',
        'password',
        'status',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function toDisplayArray(): array
    {
        return [
            'id' => $this->id,
            'nik' => (string) $this->nik,
            'name' => $this->name,
            'address' => $this->address ?? '-',
            'rt' => $this->rt ?? '-',
            'rw' => $this->rw ?? '-',
            'status' => $this->status,
            'is_active' => $this->isActive(),
            'must_change_password' => (bool) $this->must_change_password,
            'complaints_count' => $this->complaints()->count(),
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
        ];
    }
}
