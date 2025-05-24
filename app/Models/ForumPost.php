<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'forum_posts';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'thread_id',
        'author_id',
        'content',
        'post_id',
        //'sequence',
    ];
    public function thread()
    {
        return $this->belongsTo(Forumthreads::class, 'thread_id');
    }
        public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'author_id');
    }
    public function category()
{
    return $this->hasOneThrough(
        \App\Models\ForumCategories::class,
        \App\Models\Forumthreads::class,
        'id', // khóa chính của forum_threads
        'id', // khóa chính của forum_categories
        'thread_id', // khóa ngoại trong forum_posts
        'category_id' // khóa ngoại trong forum_threads
    );
}
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
