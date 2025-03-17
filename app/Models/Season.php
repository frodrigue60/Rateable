<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function songVariants()
    {
        return $this->hasMany(SongVariant::class);
    }
}
