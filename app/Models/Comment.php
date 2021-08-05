<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'happening_id',
        'user_id'
    ];

    public function happening()
    {
        return $this->belongsTo(Happening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
