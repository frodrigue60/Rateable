<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use willvincent\Rateable\Rateable;
use Conner\Tagging\Taggable;
use Conner\Likeable\Likeable;
use App\Models\Post;
use App\Models\SongVariant;

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

    public function artists()
    {
        return $this->belongsToMany(Artist::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'rateable_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class)->whereNull('song_variant_id');
    }

    public function songVariants()
    {
        return $this->hasMany(SongVariant::class);
    }
}
