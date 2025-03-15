<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRequest;
use App\Models\Comment;
use App\Models\Favorite;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'score_format',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userRequests()
    {
        return $this->hasMany(UserRequest::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function isStaff()
    {
        $user = Auth::user()->type;
        if ($user == 'admin' || $user == 'editor' || $user == 'creator') {
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin()
    {
        $user = Auth::user()->type;
        if ($user == 'admin') {
            return true;
        } else {
            return false;
        }
    }
    public function isEditor()
    {
        $user = Auth::user()->type;
        if ($user == 'editor') {
            return true;
        } else {
            return false;
        }
    }
    public function isCreator()
    {
        $user = Auth::user()->type;
        if ($user == 'creator') {
            return true;
        } else {
            return false;
        }
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
