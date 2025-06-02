<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class AdviceRating extends Model
{
    protected $fillable = ['advice_request_id', 'rating', 'comment'];
    public function request(): BelongsTo { return $this->belongsTo(AdviceRequest::class); }
}
