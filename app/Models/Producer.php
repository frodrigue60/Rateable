<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function post()
    {
        return $this->belongsToMany(Studio::class, 'post_producer', 'post_id', 'studio_id');
    }
}
