<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SongVariant;

class Video extends Model
{
    use HasFactory;

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function songVariants()
    {
        return $this->belongsTo(SongVariant::class);
    }
}
