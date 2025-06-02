<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class AdviceRequest extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'preferred_time',
        'status', 'moderator_id', 'scheduled_at',
        'meeting_link', 'moderator_notes', 'reminded_at',
        'notes',
    ];

    /* ===== Status hằng số ===== */
    public const PENDING   = 'pending';
    public const SCHEDULED = 'scheduled';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';
    public const NO_SHOW   = 'no-show';

    /* ===== Quan hệ ===== */
    public function user(): BelongsTo       { return $this->belongsTo(User::class); }
    public function moderator(): BelongsTo  { return $this->belongsTo(User::class, 'moderator_id'); }
    public function rating()                { return $this->hasOne(AdviceRating::class); }

    /* ===== Scope tiện dùng ===== */
    public function scopePending(Builder $q)   { $q->where('status', self::PENDING); }
    public function scopeScheduled(Builder $q) { $q->where('status', self::SCHEDULED); }
}
