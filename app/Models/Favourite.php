<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $fillable = [
        'user_id',
        "favoritable_type",
        "favoritable_id",
    ];
    public function favoritable()
    {
        return $this->morphTo();
    }
}
