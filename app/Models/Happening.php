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
        'status'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

//    public function members()
//    {
//        return $this->belongsToMany(User::class)->withPivot('presence');
//    }
}
