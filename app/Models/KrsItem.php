<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KrsItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function studentKrs(): BelongsTo
    {
        return $this->belongsTo(StudentKrs::class, 'student_krs_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}

