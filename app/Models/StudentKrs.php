<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudentKrs extends Model
{
    use HasFactory;

    protected $table = 'student_krs';
    protected $guarded = ['id'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(KrsItem::class, 'student_krs_id');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'krs_items', 'student_krs_id', 'course_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function getTotalSksAttribute(): int
    {
        return $this->courses()->sum('sks');
    }
}
