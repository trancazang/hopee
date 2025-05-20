<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ForumPostReport extends Model
{
    use CrudTrait;
    protected $fillable = ['user_id', 'post_id', 'reason', 'status'];

    public function post()
    {
        return $this->belongsTo(\TeamTeaTime\Forum\Models\Post::class, 'post_id');
    }public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
