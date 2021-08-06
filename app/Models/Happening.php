<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Happening extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'place',
        'team_id',
        'start_at',
        'end_at',
        'status',
        'survey_id'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class,
            'happenings_users',
            'happening_id',
            'user_id')->withPivot('presence');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
