<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SongVariant;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($video) {
            if ($video->video_src != null && Storage::disk('public')->exists($video->video_src)) {
                Storage::disk('public')->delete($video->video_src);
            }
        });
    }

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function songVariant()
    {
        return $this->belongsTo(SongVariant::class);
    }
}
