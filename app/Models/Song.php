<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'song_romaji',
        'song_jp',
        'song_song_en',
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }
}
