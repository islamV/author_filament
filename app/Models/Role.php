<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    /**
     * Guard name defaults to 'web' to match the configured auth guard.
     */
    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'guard_name',
    ];
}
