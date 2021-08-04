<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function teams()
    {
        return $this->belongsToMany(
            Team::class,
            'teams_users',
            'user_id',
            'team_id')->withPivot('admin')->using('App\Models\TeamUsersPivot');
    }

    public function invitations()
    {
        return $this->hasMany(
            Invitation::class,
            'user_email',
            'email'
        );
    }

    public function happenings()
    {
        return $this->belongsToMany(Happening::class,
            'happenings_users',
            'user_id',
            'happening_id')->withPivot('presence');
    }
}
