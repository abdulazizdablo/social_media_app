<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

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
        'password' => 'hashed',
    ];


    public function posts(): HasMany
    {


        return $this->hasMany(Post::class);
    }


    public function profile(): HasOne
    {

        return $this->hasOne(Profile::class);
    }

    public function followedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_followed_users', 'followed_user_id');
    }


    public function followingUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_followed_users', 'user_id');
    }


    public function hasLiked($model)
    {
        $user = auth()->user();
        $likes = $model->likes;

        foreach ($likes as $like) {
            if ($like->user_id == $user->id) {
                return true;
            }
        }

        return false;

    }
}

   
