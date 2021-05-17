<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    #quan hệ 1 - n song-user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
