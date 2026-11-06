<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvisingProgress extends Model
{
    use HasFactory;

    protected $table = 'advising_progress';
    protected $guarded = ['id'];

    protected $casts = [
        'current_ipk' => 'decimal:2',
        'is_hardfile_printed' => 'boolean',
        'verified_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }
}
