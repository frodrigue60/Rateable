<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\SongVariant;
use Illuminate\Support\Facades\Storage;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'song_romaji',
        'song_jp',
        'song_en',
        'spoiler',
        'theme_num',
        'type',
        'slug',
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

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class);
    }

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
        } elseif ($this->song_en != null) {
            return $this->song_en;
        } else {
            return 'n/a';
        }
    }
}
