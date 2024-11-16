<?php

namespace App\Models;

use Altwaireb\World\Models\Country as Model;

class Country extends Model
{
    //
    public function state()
    {
        return $this->hasMany(State::class);
    }
}
