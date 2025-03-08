<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
    ];



    public function convegrations()
    {
        return $this->belongsToMany(Convegration::class, 'participents')
            ->latest('last_massege_id')
            ->withPivot(['joined_at', 'role']);
    }

    public function sentmessages()
    {
        return $this->hasMany(Messege::class, 'user_id', 'id');
    }
    public function receivedmessages()
    {
        return $this->belongsToMany(Messege::class, 'recipients')->withPivot(['read_at', 'deleted_at']);
    }
}
