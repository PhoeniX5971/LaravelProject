<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $fillable = ['username', 'email', 'password', 'profile_picture', 'bio'];
    public $timestamps = true;

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    // Implement JWTSubject methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
