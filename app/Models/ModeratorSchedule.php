<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeratorSchedule extends Model
{
    protected $fillable = [
        'moderator_id',
        'slot_date',
        'slot_time',
        'is_available',
    ];

    public function moderator(): BelongsTo { return $this->belongsTo(User::class, 'moderator_id'); }

    /* scope lấy slot trống (chưa đặt) */
    public function scopeFree($q) { $q->where('is_available', true); }
}
