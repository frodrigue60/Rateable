<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use willvincent\Rateable\Rateable;
use Conner\Tagging\Taggable;
use Conner\Likeable\Likeable;
use App\Models\Post;

class Song extends Model
{
    use HasFactory;
    use Rateable;
    use Taggable;
    use Likeable;

    protected $fillable = [
        'id',
        'song_romaji',
        'song_jp',
        'song_song_en',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
