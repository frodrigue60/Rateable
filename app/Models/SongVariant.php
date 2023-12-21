<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Song;
use App\Models\Video;

class SongVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'version',
        'song_id',
    ];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class)->whereNotNull('song_variant_id');
    }
}
