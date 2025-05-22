<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumPostVote extends Model
{
    protected $fillable = ['user_id', 'post_id', 'vote_type'];

    public function post()
    {
        return $this->belongsTo(\TeamTeaTime\Forum\Models\Post::class, 'post_id');
    }
        public function user() { return $this->belongsTo(User::class); }


}
