<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Result extends Model
{
    use HasFactory;

    protected $fillable = ['test_id', 'min_score', 'max_score', 'level', 'description', 'advice'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
