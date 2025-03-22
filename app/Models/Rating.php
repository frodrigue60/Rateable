<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Rating extends Model
{
    use HasFactory;

    public $fillable = ['rating'];

    public function rateable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        //Old laravel versions
        $userClassName = Config::get('auth.model');

        //New laravel versions
        if (is_null($userClassName)) {
            $userClassName = Config::get('auth.providers.users.model');
        }

        return $this->belongsTo($userClassName);
    }
}
