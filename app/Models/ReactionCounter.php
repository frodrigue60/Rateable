<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReactionCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'reactable_id',
        'reactable_type',
        'likes_count',
        'dislikes_count',
    ];

    // Relación polimórfica
    public function reactable()
    {
        return $this->morphTo();
    }
}
