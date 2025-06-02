<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Musonza\Chat\Traits\Messageable;



class User extends Authenticatable
{
    use CrudTrait;
    use HasFactory, Notifiable;
    use Messageable;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // public function setPasswordAttribute($value)
    // {
    //     // Chỉ hash và set nếu có giá trị nhập
    //     if (!empty($value)) {
    //         $this->attributes['password'] = bcrypt($value);
    //     }
    // }

    public function getParticipantDetailsAttribute()
{
    return [
        'name' => $this->name,
        'avatar' => $this->avatar
            ? asset('storage/avatars/' . $this->avatar)  // nếu bạn lưu file
            : asset('/images/default-avatar.png')        // ảnh mặc định
    ];
}
    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('images/default-avatar.png'); // ảnh mặc định nếu chưa upload
    }
}
