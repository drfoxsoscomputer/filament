<?php

namespace App\Models;

use Altwaireb\World\Models\City as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    //
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
