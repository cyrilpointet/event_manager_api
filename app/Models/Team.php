<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUsersPivot extends Pivot
{
    protected $casts = [
        'admin' => 'boolean'
    ];
}

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'teams_users',
            'team_id',
            'user_id')->withPivot('admin')->using('App\Models\TeamUsersPivot');
    }
}
