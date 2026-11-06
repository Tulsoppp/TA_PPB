<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'enrolled' => 'integer',
        'quota' => 'integer',
        'sks' => 'integer',
        'semester' => 'integer',
    ];

    public function krsItems(): HasMany
    {
        return $this->hasMany(KrsItem::class, 'course_id');
    }

    public function quotaRequests(): HasMany
    {
        return $this->hasMany(QuotaRequest::class, 'course_id');
    }

    public function quotaReports(): HasMany
    {
        return $this->hasMany(QuotaReport::class, 'course_id');
    }

    public function isFull(): bool
    {
        return $this->enrolled >= $this->quota;
    }
}
