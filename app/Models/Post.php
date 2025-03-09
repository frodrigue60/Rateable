<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use willvincent\Rateable\Rateable;
use Conner\Tagging\Taggable;
use Conner\Likeable\Likeable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Post extends Model
{
    use HasFactory;
    //use Rateable;
    use Taggable;
    //use Likeable;

    protected $fillable = [
        'title',
        'slug',
        'song_id',
        'artist_id',
        'ytlink',
        'scndlink',
        'type',
        'thumbnail',
        'view_count',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            if ($post->thumbnail_src != null && Storage::disk('public')->exists($post->thumbnail)) {
                Storage::disk('public')->delete($post->thumbnail);
            }

            if ($post->banner_src != null && Storage::disk('public')->exists($post->banner)) {
                Storage::disk('public')->delete($post->banner);
            }

            $post->load('songs.songVariants.video');

            foreach ($post->songs as $song) {
                foreach ($song->songVariants as $variant) {
                    if ($variant->video) {
                        $video = $variant->video;

                        //Log::info("Intentando eliminar archivo: " . $video->video_src);

                        if ($video->video_src != null && Storage::disk('public')->exists($video->video_src)) {
                            Storage::disk('public')->delete($video->video_src);
                            //Log::info("Archivo eliminado con éxito");
                        } else {
                            //Log::info("Archivo no eliminado - No existe en: " . $video->video_src);
                        }
                    }
                }
            }
        });
    }

    public function songs()
    {
        return $this->hasMany('App\Models\Song');
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function getUrlAttribute()
    {
        return route('post.show', [
            'anilist_id' => $this->anilist_id,
            'slug' => $this->slug,
        ]);
    }
}
