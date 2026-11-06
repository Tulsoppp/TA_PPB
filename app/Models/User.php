<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'ips' => 'decimal:2',
            'ipk' => 'decimal:2',
            'ukt_paid_at' => 'datetime',
            'unmet_prerequisites' => 'array',
        ];
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isDosenWali(): bool
    {
        return $this->role === 'dosen_wali';
    }

    public function isKaprodi(): bool
    {
        return in_array($this->role, ['kaprodi', 'admin']);
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function advisees(): HasMany
    {
        return $this->hasMany(User::class, 'advisor_id');
    }

    public function krs(): HasOne
    {
        return $this->hasOne(StudentKrs::class, 'user_id');
    }

    public function mbkmApplication(): HasOne
    {
        return $this->hasOne(MbkmApplication::class, 'user_id');
    }

    public function advisingProgress(): HasOne
    {
        return $this->hasOne(AdvisingProgress::class, 'user_id');
    }

    public function quotaRequests(): HasMany
    {
        return $this->hasMany(QuotaRequest::class, 'user_id');
    }

    public function advisingSchedules(): HasMany
    {
        return $this->hasMany(AdvisingSchedule::class, 'advisor_id');
    }

    public function quotaReports(): HasMany
    {
        return $this->hasMany(QuotaReport::class, 'user_id');
    }
}
