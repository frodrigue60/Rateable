<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Conner\Likeable\Likeable;
use App\Models\SongVariant;

class Comment extends Model
{

    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ratings';

    /* public function song()
    {
        return $this->belongsTo(Song::class);
    } */

    public function song_variant()
    {
        return $this->belongsTo(SongVariant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
