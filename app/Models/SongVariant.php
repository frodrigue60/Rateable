<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Song;
use App\Models\Video;
use willvincent\Rateable\Rateable;
use Conner\Likeable\Likeable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;

class SongVariant extends Model
{
    use HasFactory;
    use Rateable;
    use Likeable;

    protected $fillable = [
        'id',
        'version',
        'song_id',
        'views'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($songVariant) {
            if ($songVariant->video->video_src != null && file_exists(public_path($songVariant->video->video_src))) {
                Storage::disk('public')->delete($songVariant->video->video_src);
            }
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'rateable_id')
            ->whereNotNull('comment')
            ->latest()
            ->take(10);
    }

    public function commentsWithUser()
    {
        return $this->comments()->with(['user:id,name,image']);
    }

    /* public function featuredComments()
    {
        return $this->hasMany(Comment::class, 'rateable_id')
            ->whereNotNull('comment');
    } */


    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function video()
    {
        return $this->hasOne(Video::class);
    }

    public function incrementViews()
    {

        /* DB::table('song_variants')
            ->where('id', $this->id)
            ->increment('views'); */

        $key = 'variant_' . $this->id;

        if (!Session::has($key)) {
            DB::table('song_variants')
                ->where('id', $this->id)
                ->increment('views');

            Session::put($key, true);
        }
    }
}
