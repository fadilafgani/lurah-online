<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
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
}
