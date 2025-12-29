<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationCode extends Model
{
    protected $fillable = [
        'user_id',     // Owner of the invitation code
        'invitation_code', // The code string
        'used_by',     // User who used this code
        'device_token', // Device token from user who used the code
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The user who OWNS the invitation code.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The user who USED this invitation code.
     */
    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
