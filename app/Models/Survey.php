<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function happenings()
    {
        return $this->hasMany(Happening::class);
    }
}
