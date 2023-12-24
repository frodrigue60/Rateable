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

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class)->whereNotNull('song_variant_id');
    }

    public function incrementViews()
    {

        DB::table('song_variants')
                ->where('id', $this->id)
                ->increment('views');
        /* $key = 'variant_' . $this->id;

        if (!Session::has($key)) {
            DB::table('song_variants')
                ->where('id', $this->id)
                ->increment('views');

            Session::put($key, true);
        } */
    }
}
