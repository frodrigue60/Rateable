<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use willvincent\Rateable\Rateable;
use Conner\Tagging\Taggable;
use Conner\Likeable\Likeable;
use App\Models\Post;
use App\Models\SongVariant;
use Illuminate\Support\Facades\Storage;

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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($song) {

            $song->load('songVariants.video');

            foreach ($song->songVariants as $variant) {
                if ($variant->video) {

                    $video = $variant->video;
                    
                    if ($video->video_src != null && Storage::disk('public')->exists($video->video_src)) {
                        Storage::disk('public')->delete($video->video_src);
                    } 
                }
            }
        });
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class);
    }

    /* public function comments()
    {
        return $this->hasMany(Comment::class, 'rateable_id');
    } */

    public function videos()
    {
        return $this->hasMany(Video::class)->whereNull('song_variant_id');
    }

    public function songVariants()
    {
        return $this->hasMany(SongVariant::class);
    }

    public function getNameAttribute()
    {
        if ($this->song_romaji != null) {
            return $this->song_romaji;
        } elseif ($this->song_jp != null) {
            return $this->song_jp;
        } else {
            return 'n/a';
        }
    }
}
