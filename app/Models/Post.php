<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use willvincent\Rateable\Rateable;
use Conner\Tagging\Taggable;
use Conner\Likeable\Likeable;

class Post extends Model
{
    use HasFactory;
    use Rateable;
    use Taggable;
    use Likeable;

    protected $fillable = [
        'title',
        'song_romaji',
        'song_jp',
        'song_en',
        'artist_id',
        'imagesrc',
        'ytlink',
        'scndlink',
        'type',
        'thumbnail',
    ];

    public function artist()
    {
        return $this->belongsTo('App\Models\Artist');
    }
}
