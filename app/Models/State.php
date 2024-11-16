<?php

namespace App\Models;

use Altwaireb\World\Models\State as Model;

class State extends Model
{
    //
    // public function country()
    // {
    //     return $this->belongsTo(Country::class);
    // }

    public function city()
    {
        return $this->hasMany(City::class);
    }
}
