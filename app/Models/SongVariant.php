<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Song;
use App\Models\Video;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Rateable;

class SongVariant extends Model
{
    use HasFactory;
    use Rateable;

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
            if ($songVariant->video && file_exists(public_path($songVariant->video->video_src))) {
                Storage::disk('public')->delete($songVariant->video->video_src);
            }
        });
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function likes()
    {
        return $this->reactions()->where('type', 1);
    }

    public function dislikes()
    {
        return $this->reactions()->where('type', -1);
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getDislikesCountAttribute()
    {
        return $this->dislikes()->count();
    }

    // Método para verificar si el usuario actual ha dado like
    public function liked()
    {
        if (Auth::check()) { // Verifica si el usuario está autenticado
            return $this->reactions()
                ->where('user_id', Auth::id())
                ->where('type', 1)
                ->exists();
        }
        return false;
    }

    // Método para verificar si el usuario actual ha dado dislike
    public function disliked()
    {
        if (Auth::check()) { // Verifica si el usuario está autenticado
            return $this->reactions()
                ->where('user_id', Auth::id())
                ->where('type', -1)
                ->exists();
        }
        return false;
    }

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
        $key = 'variant_' . $this->id;

        if (!Session::has($key)) {
            DB::table('song_variants')
                ->where('id', $this->id)
                ->increment('views');

            Session::put($key, true);
        }
    }

    public function getViewsStringAttribute()
    {
        if ($this->views >= 1000000) {
            $views = number_format(intval($this->views / 1000000), 0) . 'M';
        } elseif ($this->views >= 1000) {
            $views = number_format(intval($this->views / 1000), 0) . 'K';
        } else {
            $views = $this->views;
        }

        return $views;
    }

    public function getUrlAttribute()
    {
        return route('variants.show', [
            'anime_slug' => $this->song->post->slug,
            'song_slug' => $this->song->slug,
            'variant_version_number' => $this->version_number,
        ]);
    }

    public function getScoreStringAttribute()
    {
        $score_string = '';
        if (Auth::User()) {
            switch (Auth::User()->score_format) {
                case 'POINT_5':
                    $score = round($this->averageRating / 20);
                    $score_string = $score != null ? $score . '/5' : 'N/A';
                    break;
                case 'POINT_10':
                    $score = round($this->averageRating / 10);
                    $score_string = $score != null ? $score . '/10' : 'N/A';
                    break;
                case 'POINT_10_DECIMAL':
                    $score = round($this->averageRating / 10, 1);
                    $score_string = $score != null ? $score . '/10' : 'N/A';
                    break;
                case 'POINT_100':
                    $score = round($this->averageRating);
                    $score_string = $score != null ? $score . '/100' : 'N/A';
                    break;
                default:
                    $score = round($this->averageRating);
                    $score_string = $score != null ? $score . '/10' : 'N/A';
                    break;
            }
        } else {
            $score = round($this->averageRating);
            $score_string = $score != null ? $score . '/100' : 'N/A';
        }

        return $score_string;
    }

    // Método para obtener los posts que un usuario ha dado like
    public function scopeWhereLikedBy($query, $userId)
    {
        return $query->whereHas('favorites', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    // Relación con el contador de reacciones (nombre corregido)
    public function reactionsCounter()
    {
        return $this->morphOne(ReactionCounter::class, 'reactable');
    }

    // Método para actualizar los contadores
    public function updateReactionCounters()
    {
        $likesCount = $this->reactions()->where('type', 1)->count();
        $dislikesCount = $this->reactions()->where('type', -1)->count();

        $this->reactionsCounter()->updateOrCreate(
            ['reactable_id' => $this->id, 'reactable_type' => self::class],
            ['likes_count' => $likesCount, 'dislikes_count' => $dislikesCount]
        );
    }

    // Relación polimórfica para favoritos
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    // Método para verificar si el usuario actual ha marcado este post como favorito
    public function isFavorited()
    {
        if (Auth::check()) {
            return $this->favorites()->where('user_id', Auth::id())->exists();
        }
        return false;
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }
    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
